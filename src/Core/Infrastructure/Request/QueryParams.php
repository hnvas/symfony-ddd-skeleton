<?php
declare(strict_types = 1);

namespace App\Core\Infrastructure\Request;

use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class QueryParams
{

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
        return (int)$this->request->query->get('page', 1);
    }

    public function limit(): int
    {
        return (int)$this->request->get('limit', 10);
    }

    public function order(): InputBag
    {
        return $this->request->query->get('order', ['id' => 'ASC']);
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
