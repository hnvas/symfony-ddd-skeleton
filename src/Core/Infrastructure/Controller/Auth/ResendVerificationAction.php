<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Auth;

use App\Core\Application\Services\UseCases\SendUserEmailVerification;
use App\Core\Domain\Model\User;
use App\Core\Infrastructure\Http\Response\ApiEmptyResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ResendVerificationAction
 * @package App\Core\Infrastructure\Controller\Action\Auth
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
#[Route('/resend', name: 'resend_verification_action', methods: ['GET'])]
class ResendVerificationAction extends AbstractController
{

    public function __construct(
        private readonly SendUserEmailVerification $emailVerification
    ){}

    /**
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        /** @var \App\Core\Infrastructure\Security\AuthUser $user */
        $user = $this->getUser();

        $this->emailVerification->execute($user->model());

        return new ApiEmptyResponse();
    }
}
