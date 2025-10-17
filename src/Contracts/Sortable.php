<?php

declare(strict_types=1);

namespace SamuelStidham\LinkedList\Contracts;

/** Represents an object that can be sorted. */
interface Sortable
{
    /**
     * Compares the current value with another value.
     *
     * @param Sortable|int|string $other The other value to compare with.
     * @return int -1 if current < other, 0 if current == other, 1 if current > other
     */
    public function compareTo(Sortable|int|string $other): int;
}
