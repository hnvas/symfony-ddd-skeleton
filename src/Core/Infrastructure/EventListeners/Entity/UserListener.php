<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\EventListeners\Entity;

use App\Core\Application\Services\UseCases\SendUserEmailVerification;
use App\Core\Domain\Model\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Class UserListener
 * @package App\Core\Infrastructure\EventListeners\Entity
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
class UserListener
{

    private SendUserEmailVerification $emailVerification;

    public function __construct(SendUserEmailVerification $emailVerification)
    {
        $this->emailVerification = $emailVerification;
    }

    public function postPersist(User $user, LifecycleEventArgs $event): void
    {
        if (!$user->isEmailVerified()) {
            $this->emailVerification->execute($user);
        }
    }
}
