<?php
declare(strict_types = 1);

namespace App\Core\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Module implements Entity
{
    private ?int        $id;
    private string      $name;
    private bool        $enabled = false;
    private ?Collection $permissions;

    public function __construct(string $name, bool $enabled = false)
    {
        $this->name        = $name;
        $this->enabled     = $enabled;
        $this->permissions = new ArrayCollection([]);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function permissions(): ?Collection
    {
        return $this->permissions;
    }
}
