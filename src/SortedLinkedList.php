<?php

declare(strict_types=1);

namespace SamuelStidham\LinkedList;

use Countable;
use IteratorAggregate;
use SamuelStidham\LinkedList\Contracts\Sortable;
use Traversable;

class SortedLinkedList implements IteratorAggregate, Countable
{
    /** @var ?Node<T> */
    private ?node $head = null;

    /** @var int */
    private int $length = 0;

    /** @var ?string */
    private ?string $dataType = null;

    /**
     * Inserts a value while maintaining sorted order and enforcing type safety.
     *
     * @param T $value
     * @throws LinkedListException If a type mismatch occurs.
     */
    public function insert(mixed $value): void
    {
        $this->validateType($value);
        $this->head = $this->recursiveInsert($this->head, $value);
        $this->length++;
    }

    /**
     * @param T $value
     * @return bool True if removed, False otherwise.
     */
    public function remove(mixed $value): bool
    {
        if ($this->length === 0) {
            return false;
        }

        [$newHead, $removed] = $this->recursiveRemove($this->head, $value);

        if ($removed) {
            $this->head = $newHead;
            $this->length--;
            if ($this->length === 0) {
                $this->dataType = null;
            }
        }
        return $removed;
    }

    /**
     * Checks if a value is present in the list.
     * @param T $value
     */
    public function contains(mixed $value): bool
    {
        $current = $this->head;
        while ($current !== null) {
            $comparison = $this->compare($current->getValue(), $value);
            if ($comparison === 0) {
                return true;
            }
            if ($comparison > 0) {
                return false;
            }
            $current = $current->getNext();
        }
        return false;
    }

    public function count(): int
    {
        return $this->length;
    }

    /** @return Traversable<int, T> */
    public function getIterator(): Traversable
    {
        $current = $this->head;
        while ($current !== null) {
            yield $current->getValue();
            $current = $current->getNext();
        }
    }

    /**
     * @param ?Node<T> $current
     * @param T $value
     * @return Node<T>
     */
    private function recursiveInsert(?Node $current, mixed $value): Node
    {
        if ($current === null) {
            return new Node($value);
        }

        $comparison = $this->compare($value, $current->getValue());

        if ($comparison <= 0) {
            return new Node($value, $current);
        }

        $newNext = $this->recursiveInsert($current->getNext(), $value);

        return new Node($current->getValue(), $newNext);
    }

    /**
     * @param ?Node<T> $current
     * @param T $value
     * @return array{0: ?Node<T>, 1: bool}
     */
    private function recursiveRemove(?Node $current, mixed $value): array
    {
        if ($current === null) {
            return [null, false];
        }

        $comparison = $this->compare($value, $current->getValue());

        if ($comparison < 0) {
            return [$current, false];
        }

        if ($comparison === 0) {
            return [$current->getNext(), true];
        }

        [$newNext, $removed] = $this->recursiveRemove($current->getNext(), $value);

        if ($removed) {
            return [new Node($current->getValue(), $newNext), true];
        }

        return [$current, false];
    }

    /**
     * Compares two values based on their type.
     */
    private function compare(mixed $a, mixed $b): int
    {
        if (is_scalar($a) && is_scalar($b)) {
            return $a <=> $b;
        }

        if ($a instanceof Sortable && $b instanceof Sortable) {
            return $a->compareTo($b);
        }

        throw new LinkedListException('Comparison failed due to inconsistent types.');
    }

    /**
     * Ensures all inserted values match the established type.
     * @throws LinkedListException
     */
    private function validateType(mixed $value): void
    {
        $type = is_object($value) ? $value::class : gettype($value);

        if ($this->dataType === null) {
            if (!is_int($value) && !is_string($value) && !($value instanceof Sortable)) {
                throw new LinkedListException("Initial value must be an 'int', 'string', or implement 'Sortable'.");
            }
            $this->dataType = $type;
            return;
        }

        if ($this->dataType !== $type) {
            throw LinkedListException::typeMismatch($this->dataType, $type);
        }
    }
}
