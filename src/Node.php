<?php

declare(strict_types=1);

namespace SamuelStidham\LinkedList;

use SamuelStidahm\LinkedList\Contracts\Sortable;

/**
 * @template T of (int|string|Sortable)
 */
class Node
{
    /** @var T */
    private mixed $value;

    /** @var ?self<T> */
    private ?self $next;

    /**
     * @param T $value
     * @param ?self<T> $next
     */
    public function __construct(mixed $value, ?self $next = null)
    {
        $this->value = $value;
        $this->next = $next;
    }

    /**
     * @return T
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @return ?self<T>
     */
    public function getNext(): ?self
    {
        return $this->next;
    }
}
