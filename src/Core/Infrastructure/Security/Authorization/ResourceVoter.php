<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Security\Authorization;

use App\Core\Domain\Repository\PermissionRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ResourceVoter extends Voter
{

    const CREATE = 'create';
    const READ   = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const INDEX  = 'index';

    /**
     * @var \App\Core\Domain\Repository\PermissionRepositoryInterface
     */
    private PermissionRepositoryInterface $repository;

    public function __construct(PermissionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     *
     * @return bool
     */
    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, $this->actions())) {
            return false;
        }

        if (!$subject instanceof Request) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param Request $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $resource = preg_replace('/(\d+)?(\?.*)?/', '', $subject->getRequestUri());
        $roles    = $token->getRoleNames();
        $method   = "get" . ucfirst($attribute);

        if (in_array('ROLE_ADMIN', $roles)) {
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

    /**
     * @return string[]
     */
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
