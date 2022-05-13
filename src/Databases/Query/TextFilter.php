<?php

namespace Notion\Databases\Query;

/** @psalm-immutable */
class TextFilter implements Filter, Condition
{
    private const OPERATOR_EQUALS = "equals";
    private const OPERATOR_DOES_NOT_EQUAL = "does_not_equal";
    private const OPERATOR_CONTAINS = "contains";
    private const OPERATOR_DOES_NOT_CONTAIN = "does_not_contain";
    private const OPERATOR_STARTS_WITH = "starts_with";
    private const OPERATOR_ENDS_WITH = "ends_with";
    private const OPERATOR_IS_EMPTY = "is_empty";
    private const OPERATOR_IS_NOT_EMPTY = "is_not_empty";

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
            self::OPERATOR_CONTAINS,
            ""
        );
    }

    public function propertyType(): string
    {
        return $this->propertyType;
    }

    public function propertyName(): string
    {
        return $this->propertyName;
    }

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
            $this->propertyType => $this->propertyName,
            "rich_text" => [
                $this->operator => $this->value
            ],
        ];
    }

    public function equals(string $value): self
    {
        return new self($this->propertyName, self::OPERATOR_EQUALS, $value);
    }

    public function doesNotEqual(string $value): self
    {
        return new self($this->propertyName, self::OPERATOR_DOES_NOT_EQUAL, $value);
    }

    public function contains(string $value): self
    {
        return new self($this->propertyName, self::OPERATOR_CONTAINS, $value);
    }

    public function doesNotContain(string $value): self
    {
        return new self($this->propertyName, self::OPERATOR_DOES_NOT_CONTAIN, $value);
    }

    public function startsWith(string $value): self
    {
        return new self($this->propertyName, self::OPERATOR_STARTS_WITH, $value);
    }

    public function endsWith(string $value): self
    {
        return new self($this->propertyName, self::OPERATOR_ENDS_WITH, $value);
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
