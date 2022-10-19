<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Security\Authorization;

use App\Core\Domain\Enum\UserRoleEnum;
use App\Core\Domain\Repository\PermissionRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class ResourceVoter
 * @package App\Core\Infrastructure\Security\Authorization
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class ResourceVoter extends Voter
{
    const CREATE = 'create';
    const READ   = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const INDEX  = 'index';

    private PermissionRepositoryInterface $repository;

    public function __construct(PermissionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (
            !in_array($attribute, $this->actions()) ||
            !$subject instanceof Request
        ) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(
        string $attribute,
        $subject,
        TokenInterface $token
    ): bool {
        $route    = $subject->get('_route');
        $resource = str_replace("_$attribute", '', $route);
        $roles    = $token->getRoleNames();
        $method   = "can" . ucfirst($attribute);

        /** @var \App\Core\Domain\Model\User $user */
        $user = $token->getUser()->model();

        if (!$user->isActive() || !$user->isEmailVerified()) {
            return false;
        }

        if (in_array(UserRoleEnum::ROLE_ADMIN, $roles)) {
            return true;
        }

        $permission = $this->repository->findOneBy([
            'role'     => $roles,
            'resource' => $resource
        ]);

        if (empty($permission)) {
            return false;
        }

        return $permission->{$method}();
    }

    private function actions(): array
    {
        return [
            self::CREATE,
            self::READ,
            self::UPDATE,
            self::DELETE,
            self::INDEX
        ];
    }
}
