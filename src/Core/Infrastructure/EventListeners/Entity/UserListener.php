<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\EventListeners\Entity;

use App\Core\Application\Services\Mail\UserEmailVerification;
use App\Core\Domain\Model\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserListener
{
    private UserEmailVerification $emailVerification;

    public function __construct(UserEmailVerification $emailVerification)
    {
        $this->emailVerification = $emailVerification;
    }

    public function postPersist(User $user, LifecycleEventArgs $event): void
    {
        if (!$user->isEmailVerified()) {
            $this->emailVerification->send($user);
        }
    }
}
