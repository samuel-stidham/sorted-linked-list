# SortedLinkedList PHP Library

## ShipMonk Backend Engineer Technical Challenge

This repository contains the solution to the ShipMonk technical challenge: implementing a generic, type-safe, and sorted linked list library in PHP. The implementation focuses on **maintainability**, **usability**, and **modern PHP best practices**.

The core implementation uses a recursive, functional approach to insertion and removal, ensuring the list always remains sorted.

## Key Features & Best Practices

- **Sorted Data Structure:** Values are always maintained in ascending order upon insertion.
- **Type Safety:** Prevents mixing `int` and `string` values after the list's type is established.
- **Extensibility:** Uses the `Sortable` interface to allow the list to hold custom objects with defined comparison logic (adheres to the **Open/Closed Principle**).
- **PHP Idioms:** Implements standard PHP interfaces (`\Countable` and `\IteratorAggregate`) for native functions like `count($list)` and `foreach ($list as $item)`.
- **Immutable Nodes:** Nodes are designed to be immutable, improving state predictability.

## Technology Stack & Tooling

To demonstrate senior-level PHP skills and compliance with standard engineering practices, the project is configured with the following tools:

| Tool             | Purpose                                                 | Status    |
| :--------------- | :------------------------------------------------------ | :-------- |
| **PHP 8.2+**     | Runtime environment. Running PHP 8.4 locally.           | Required  |
| **PHPUnit**      | Unit testing framework (with 100% coverage achieved).   | Testing   |
| **PHPStan**      | Static analysis (configured to **Level 9** strictness). | Quality   |
| **PHP-CS-Fixer** | Automated code style fixing (configured to **PSR-12**). | Standards |

## Installation and Setup

1.  **Clone the repository:**

    ```bash
    git clone git@github.com:samuel-stidham/sorted-linked-list.git
    cd sorted-linked-list
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

## Usage Example

```php
<?php

use SamuelStidham\LinkedList\SortedLinkedList;

// --- Integer List ---
$intList = new SortedLinkedList();
$intList->insert(50);
$intList->insert(10);
$intList->insert(25);

echo count($intList); // Output: 3

foreach ($intList as $value) {
    echo $value . " "; // Output: 10 25 50
}

// --- Type Safety Example ---
try {
    $intList->insert("string");
} catch (\SamuelStidham\LinkedList\LinkedListException $e) {
    echo "\nError: " . $e->getMessage();
    // Output: Error: Cannot mix types. This list holds 'integer' values, but 'string' was provided.
}
```

## Running Quality Tools

The following Composer scripts are configured for immediate quality checks and standards enforcement:

| Command                | Action                                                                    |
| :--------------------- | :------------------------------------------------------------------------ |
| `composer run test`    | Executes the PHPUnit test suite. **(Achieves 100% coverage)**             |
| `composer run fix-cs`  | Fixes code style issues automatically using PSR-12 standard.              |
| `composer run analyse` | Runs PHPStan at Level 9 to find advanced errors and type inconsistencies. |

**To run the tests with full code coverage generation (requires Xdebug/PCOV):**

```bash
XDEBUG_MODE=coverage php -d zend.assertions=1 -d assert.exception=1 vendor/bin/phpunit --coverage-html reports/coverage
```

### Code Standards Check (PHPCS)

To ensure code quality, the project adheres to the PSR-12 coding standard. The current status shows no violations:

```text
composer run cs-check
> phpcs --standard=PSR12 src tests -v --colors
Registering sniffs in the PSR12 standard... DONE (60 sniffs registered)
Creating file list... DONE (6 files in queue)
Changing into directory /home/samuelstidham/Code/snhu-academic/sorted-linked-list/src/Contracts
Processing Sortable.php [PHP => 82 tokens in 17 lines]... DONE in 1ms (0 errors, 0 warnings)
Changing into directory /home/samuelstidham/Code/snhu-academic/sorted-linked-list/src
Processing SortedLinkedList.php [PHP => 1322 tokens in 181 lines]... DONE in 15ms (0 errors, 0 warnings)
Processing Node.php [PHP => 235 tokens in 45 lines]... DONE in 3ms (0 errors, 0 warnings)
Processing LinkedListException.php [PHP => 116 tokens in 23 lines]... DONE in 1ms (0 errors, 0 warnings)
Changing into directory /home/samuelstidham/Code/snhu-academic/sorted-linked-list/tests
Processing CustomSortableItem.php [PHP => 135 tokens in 23 lines]... DONE in 2ms (0 errors, 0 warnings)
Processing SortedLinkedListTest.php [PHP => 1095 tokens in 139 lines]... DONE in 13ms (0 errors, 0 warnings)
```
