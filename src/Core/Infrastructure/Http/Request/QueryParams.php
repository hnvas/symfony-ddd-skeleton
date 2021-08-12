<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Http\Request;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class QueryParams
{

    const DEFAULT_LIMIT = 10;
    const DEFAULT_PAGE = 1;
    const DEFAULT_ORDER = 'ASC';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private Request $request;

    /**
     * QueryFilter constructor.
     *
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function all(): array
    {
        return $this->request->query->all();
    }

    public function route(): string
    {
        return $this->request->get('_route');
    }

    public function page(): int
    {
        return (int)$this->request->query->get('page', self::DEFAULT_PAGE);
    }

    public function limit(): int
    {
        return (int)$this->request->get('limit', self::DEFAULT_LIMIT);
    }

    public function order(): InputBag
    {
        return $this->request->query->get('order', ['id' => self::DEFAULT_ORDER]);
    }

    public function criteria(): array
    {
        $params = $this->request->query->all();

        return array_filter(
            $params,
            fn($k) => !in_array($k, ['order', 'limit', 'page']),
            ARRAY_FILTER_USE_KEY);
    }

}
