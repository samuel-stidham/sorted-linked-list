<?php

declare(strict_types=1);

namespace SamuelStidham\LinkedList;

/**
 * Exception thrown for linked list errors.
 */
class LinkedListException extends \Exception
{
    /**
     * Creates an exception for type mismatch errors.
     *
     * @param string $expected The expected type.
     * @param string $actual The actual type provided.
     * @return self The created exception.
     */
    public static function typeMismatch(string $expected, string $actual): self
    {
        return new self("Cannot mix types. This list holds '{$expected}' values, but '{$actual}' was provided.");
    }
}
