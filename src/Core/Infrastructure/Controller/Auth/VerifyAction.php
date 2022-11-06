<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Controller\Auth;

use App\Core\Application\Services\UseCases\VerifyUserEmail;
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
 */
#[Route(path: '/verify', name: 'verify_action', methods: ['GET'])]
class VerifyAction extends AbstractController
{

    public function __construct(
        private readonly VerifyUserEmail $verifyUserEmail
    ){}

    /**
     * @throws \App\Core\Application\Exceptions\InvalidDataException
     * @throws \App\Core\Application\Exceptions\ApplicationException
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
