<?php

declare(strict_types=1);

namespace SamuelStidham\LinkedList\Tests;

use PHPUnit\Framework\TestCase;
use SamuelStidham\LinkedList\Contracts\Sortable;
use SamuelStidham\LinkedList\LinkedListException;
use SamuelStidham\LinkedList\SortedLinkedList;

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

class SortedLinkedListTest extends TestCase
{
    public function testIntInsertionAndSorting(): void
    {
        $list = new SortedLinkedList();
        $list->insert(50);
        $list->insert(10);
        $list->insert(75);
        $list->insert(25);

        $this->assertSame(4, $list->count());
        $this->assertSame([10, 25, 50, 75], iterator_to_array($list));
    }

    public function testStringInsertionAndSorting(): void
    {
        $list = new SortedLinkedList();
        $list->insert('banana');
        $list->insert('apple');
        $list->insert('date');
        $list->insert('cherry');

        $this->assertSame(['apple', 'banana', 'cherry', 'date'], iterator_to_array($list));
    }

    public function testContainsWithEarlyExitOptimization(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $list->insert(50);

        $this->assertTrue($list->contains(10));
        $this->assertFalse($list->contains(30));
        $this->assertFalse($list->contains(99));
    }

    // --- Removal Tests ---
    public function testRemovalOfHeadMiddleAndTail(): void
    {
        $list = new SortedLinkedList();
        $list->insert(20);
        $list->insert(10);
        $list->insert(30);

        // Remove middle
        $this->assertTrue($list->remove(20));
        $this->assertSame([10, 30], iterator_to_array($list));

        // Remove head
        $this->assertTrue($list->remove(10));
        $this->assertSame([30], iterator_to_array($list));

        // Remove tail
        $this->assertTrue($list->remove(30));
        $this->assertSame([], iterator_to_array($list));
    }


    public function testRemoveNonExistentValue(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $list->insert(30);

        $this->assertFalse($list->remove(5));
        $this->assertFalse($list->remove(40));
        $this->assertFalse($list->remove(20));

        $this->assertSame([10, 30], iterator_to_array($list));
    }

    public function testRemoveFromEmptyList(): void
    {
        $list = new SortedLinkedList();
        $this->assertFalse($list->remove(1));
    }

    // --- Type Safety and Exception Tests ---
    public function testInsertMixedTypeThrowsException(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);

        $this->expectException(LinkedListException::class);
        $this->expectExceptionMessage("Cannot mix types. This list holds 'integer' values, but 'string' was provided.");
        $list->insert('a');
    }

    public function testInsertInvalidInitialTypeThrowsException(): void
    {
        $list = new SortedLinkedList();

        $this->expectException(LinkedListException::class);
        $this->expectExceptionMessage("Initial value must be an 'int', 'string', or implement 'Sortable'.");
        $list->insert(new \DateTime());
    }

    public function testTypeResetsAfterRemovalOfLastItem(): void
    {
        $list = new SortedLinkedList();
        $list->insert('initial');

        $list->remove('initial');
        $this->assertSame(0, $list->count());

        $list->insert(99);
        $this->assertSame([99], iterator_to_array($list));
    }

    public function testContainsWithInconsistentComparisonTypeThrowsException(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $this->expectException(LinkedListException::class);
        $this->expectExceptionMessage('Comparison failed due to inconsistent types.');
        $list->contains(new \stdClass());
    }

    public function testCustomSortableObjectSorting(): void
    {
        $list = new SortedLinkedList();
        $list->insert(new CustomSortableItem(2, 'Second'));
        $list->insert(new CustomSortableItem(1, 'First'));

        $array = iterator_to_array($list);
        $this->assertSame(1, $array[0]->id);
        $this->assertSame(2, $array[1]->id);
    }
}
