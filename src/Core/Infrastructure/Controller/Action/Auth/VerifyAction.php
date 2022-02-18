<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Action\Auth;

use App\Core\Infrastructure\Http\Response\ApiEmptyResponse;
use App\Core\Infrastructure\Http\Response\ApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

/**
 * Class VerifyAction
 * @package App\Core\Infrastructure\Action\Auth
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/auth/verify", name="email_verification", methods={"GET"})
 */
class VerifyAction extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
    }

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        try {
            $this->verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                strval($user->getId()),
                $user->getUserIdentifier()
            );
        } catch (VerifyEmailExceptionInterface $e) {
            return new JsonResponse([
                "message" => $e->getReason()
            ], Response::HTTP_BAD_REQUEST);
        }

        return new ApiEmptyResponse();
    }

}
