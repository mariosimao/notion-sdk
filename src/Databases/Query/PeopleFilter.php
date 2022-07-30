<?php

namespace Notion\Databases\Query;

/** @psalm-immutable */
class PeopleFilter implements Filter, Condition
{
    private const OPERATOR_CONTAINS = "contains";
    private const OPERATOR_DOES_NOT_CONTAIN = "does_not_contain";
    private const OPERATOR_IS_EMPTY = "is_empty";
    private const OPERATOR_IS_NOT_EMPTY = "is_not_empty";

    /** @var "property" */
    private string $propertyType = "property";
    private string $propertyName;
    /** @var self::OPERATOR_* */
    private string $operator;
    private string|bool $value;

    /** @param self::OPERATOR_* $operator */
    private function __construct(
        string $propertyName,
        string $operator,
        string|bool $value,
    ) {
        $this->propertyName = $propertyName;
        $this->operator = $operator;
        $this->value = $value;
    }

    public static function property(string $propertyName): self
    {
        return new self(
            $propertyName,
            self::OPERATOR_IS_NOT_EMPTY,
            true
        );
    }

    public static function createdBy(): self
    {
        return new self(
            "created_by",
            self::OPERATOR_IS_NOT_EMPTY,
            true
        );
    }

    public static function lastEditedBy(): self
    {
        return new self(
            "last_edited_by",
            self::OPERATOR_IS_NOT_EMPTY,
            true
        );
    }

    /** @return "property" */
    public function propertyType(): string
    {
        return $this->propertyType;
    }

    public function propertyName(): string
    {
        return $this->propertyName;
    }

    /** @return static::OPERATOR_* */
    public function operator(): string
    {
        return $this->operator;
    }

    public function value(): string|bool
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            $this->propertyType() => $this->propertyName,
            "people" => [
                $this->operator => $this->value
            ],
        ];
    }

    public function contains(string $userId): self
    {
        return new self($this->propertyName, self::OPERATOR_CONTAINS, $userId);
    }

    public function doesNotContain(string $userId): self
    {
        return new self($this->propertyName, self::OPERATOR_DOES_NOT_CONTAIN, $userId);
    }

    public function isEmpty(): self
    {
        return new self($this->propertyName, self::OPERATOR_IS_EMPTY, true);
    }

    public function isNotEmpty(): self
    {
        return new self($this->propertyName, self::OPERATOR_IS_NOT_EMPTY, true);
    }
}