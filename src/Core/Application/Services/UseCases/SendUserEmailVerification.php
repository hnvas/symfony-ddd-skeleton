<?php
declare(strict_types = 1);

namespace App\Core\Application\Services\UseCases;

use App\Core\Domain\Model\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class SendUserEmailVerification
{
    private const TEMPLATE           = 'mail/email-verification.html.twig';
    private const VERIFICATION_ROUTE = 'verify_action';
    private const SUBJECT            = 'Confirmação de registro';

    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface            $mailer;
    private string                     $clientHost;
    private string                     $clientScheme;

    public function __construct(
        VerifyEmailHelperInterface $verifyEmailHelper,
        MailerInterface            $mailer,
        ContainerBagInterface      $containerBag
    ) {
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailer            = $mailer;
        $this->clientHost        = $containerBag->get('client_host');
        $this->clientScheme      = $containerBag->get('client_scheme');
    }

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function execute(User $user): void
    {
        $signedUrl = $this->generateSignedUrl($user);

        $email = new TemplatedEmail();
        $email->to($user->email());
        $email->subject(self::SUBJECT);
        $email->htmlTemplate(self::TEMPLATE);
        $email->context([
            'signedUrl' => $signedUrl,
            'username'  => $user->name()
        ]);

        $this->mailer->send($email);
    }

    private function generateSignedUrl(User $user): string
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            self::VERIFICATION_ROUTE,
            strval($user->id()),
            $user->email()
        );

        return $this->replaceClientUrl($signatureComponents->getSignedUrl());
    }

    private function replaceClientUrl(string $url): string
    {
        $urlParts = parse_url($url);

        return sprintf("%s://%s%s?%s",
            $this->clientScheme,
            $this->clientHost,
            $urlParts['path'],
            $urlParts['query']
        );
    }

}
