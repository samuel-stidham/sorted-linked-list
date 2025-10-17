<?php

declare(strict_types=1);

namespace SamuelStidham\LinkedList\Tests;

use SamuelStidham\LinkedList\Contracts\Sortable;

class CustomSortableItem implements Sortable
{
    public function __construct(public int $id, public string $name)
    {
    }

    public function compareTo(Sortable|int|string $other): int
    {
        if ($other instanceof self) {
            return $this->id <=> $other->id;
        }

        return 0;
    }
}
