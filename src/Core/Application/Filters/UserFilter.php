<?php
declare(strict_types = 1);

namespace App\Core\Application\Filters;

use App\Core\Application\Filters\Fields\TextField;

class UserFilter extends BaseFilter
{

    use TextField;

    public function email(string $value): void
    {
        $this->valueLike($this->queryAlias("email"), $value);
    }

    public function name(string $value): void
    {
        $this->valueLike($this->queryAlias("name"), $value);
    }

}
