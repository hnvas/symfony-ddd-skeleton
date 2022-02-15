<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\EventListeners\Entity;

use App\Core\Domain\Model\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class UserHandler
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface            $mailer;

    public function __construct(
        VerifyEmailHelperInterface $helper,
        MailerInterface            $mailer
    ) {
        $this->verifyEmailHelper = $helper;
        $this->mailer            = $mailer;
    }

    public function postPersist(User $user, LifecycleEventArgs $event): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'email_verification',
            strval($user->getId()),
            $user->getEmail()
        );

        $email = new TemplatedEmail();
        $email->to($user->getEmail());
        $email->htmlTemplate('mail/email-verification.html.twig');
        $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

        $this->mailer->send($email);
    }

}
