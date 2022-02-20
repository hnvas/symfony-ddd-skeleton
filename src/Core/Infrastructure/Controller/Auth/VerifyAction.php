<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Auth;

use App\Core\Application\UseCases\VerifyUserEmail;
use App\Core\Domain\Model\User;
use App\Core\Infrastructure\Http\Response\ApiEmptyResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VerifyAction
 * @package App\Core\Infrastructure\Action\Auth
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 *
 * @Route("/verify", name="verify_action", methods={"GET"})
 */
class VerifyAction extends AbstractController
{

    private VerifyUserEmail $verifyUserEmail;

    public function __construct(VerifyUserEmail $verifyUserEmail)
    {
        $this->verifyUserEmail = $verifyUserEmail;
    }

    /**
     * @throws \App\Core\Application\Exceptions\InvalidDataException
     */
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var User $user */
        $user = $this->getUser();

        $this->verifyUserEmail->execute($request->getUri(), $user);

        return new ApiEmptyResponse();
    }

}
