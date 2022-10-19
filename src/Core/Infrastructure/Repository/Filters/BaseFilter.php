<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Repository\Filters;

use App\Core\Domain\Functions\Str;
use Doctrine\ORM\QueryBuilder;

/**
 * Class BaseFilter
 * @package App\Core\Infrastructure\Repository\Filters
 * @author  Henrique Vasconcelos <henriquenvasconcelos@gmail.com>
 */
abstract class BaseFilter
{

    /**
     * @var array
     */
    protected array $queryParams;

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected QueryBuilder $builder;

    /**
     * @param array $queryParams
     * @param \Doctrine\ORM\QueryBuilder $builder
     */
    public function __construct(array $queryParams, QueryBuilder $builder)
    {
        $this->queryParams = $queryParams;
        $this->builder     = $builder;
    }

    /**
     * Apply the filters to the builder.
     *
     * @return QueryBuilder
     */
    public function apply(): QueryBuilder
    {
        $filters = array_merge($this->defaultFilters(), $this->filters());

        foreach ($filters as $name => $value) {
            $method = Str::camel($name);

            if (!method_exists($this, $method) || is_null($value) || $value == '') {
                continue;
            }

            $this->$method($value);
        }

        return $this->builder;
    }

    /**
     * Get all request filters data.
     *
     * @return array
     */
    protected function filters(): array
    {
        return $this->queryParams;
    }

    /**
     * Default filters data.
     *
     * @return array
     */
    protected function defaultFilters(): array
    {
        return [
            'order' => ['id' => 'DESC']
        ];
    }

    /**
     * Sets the alias for provided column name of the current query
     *
     * @param string $column
     *
     * @return string
     */
    protected function queryAlias(string $column): string
    {
        return current($this->builder->getRootAliases()) . ".$column";
    }

    /**
     * @param array $order
     */
    protected function order(array $order)
    {
        foreach ($order as $column => $sort) {
            $this->builder->addOrderBy($this->queryAlias($column), $sort);
        }
    }
}
