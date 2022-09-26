<?php

namespace Notion\Databases\Query;

/** @psalm-immutable */
class DateFilter implements Filter, Condition
{
    private const TYPE_PROPERTY = "property";
    private const TYPE_TIMESTAMP = "timestamp";

    private static array $validOperators = [
        Operator::Equals,
        Operator::Before,
        Operator::After,
        Operator::OnOrBefore,
        Operator::IsEmpty,
        Operator::IsNotEmpty,
        Operator::OnOrAfter,
        Operator::PastWeek,
        Operator::PastMonth,
        Operator::PastYear,
        Operator::NextWeek,
        Operator::NextMonth,
        Operator::NextYear,
    ];

    /**
     * @psalm-param self::TYPE_* $propertyType
     */
    private function __construct(
        private readonly string $propertyType,
        private readonly string $propertyName,
        private readonly Operator $operator,
        private readonly string|bool|array $value,
    ) {
        if (!in_array($operator, self::$validOperators)) {
            throw new \Exception("Invalid operator");
        }
    }

    public static function property(string $propertyName): self
    {
        return new self(
            self::TYPE_PROPERTY,
            $propertyName,
            Operator::IsNotEmpty,
            true
        );
    }

    public static function createdTime(): self
    {
        return new self(
            self::TYPE_TIMESTAMP,
            "created_time",
            Operator::IsNotEmpty,
            true
        );
    }

    public static function lastEditedTime(): self
    {
        return new self(
            self::TYPE_TIMESTAMP,
            "last_edited_time",
            Operator::IsNotEmpty,
            true
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

    public function operator(): Operator
    {
        return $this->operator;
    }

    public function value(): string|bool|array
    {
        return $this->value;
    }

    public function toArray(): array
    {
        return [
            $this->propertyType() => $this->propertyName,
            "date" => [
                $this->operator->value => $this->value
            ],
        ];
    }

    public function equals(string $value): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::Equals, $value);
    }

    public function before(string $value): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::Before, $value);
    }

    public function after(string $value): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::After, $value);
    }

    public function onOrBefore(string $value): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::OnOrBefore, $value);
    }

    public function isEmpty(): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::IsEmpty, true);
    }

    public function isNotEmpty(): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::IsNotEmpty, true);
    }

    public function onOrAfter(string $value): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::OnOrAfter, $value);
    }

    public function pastWeek(): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::PastWeek, []);
    }

    public function pastMonth(): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::PastMonth, []);
    }

    public function pastYear(): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::PastYear, []);
    }

    public function nextWeek(): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::NextWeek, []);
    }

    public function nextMonth(): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::NextMonth, []);
    }

    public function nextYear(): self
    {
        return new self($this->propertyType, $this->propertyName, Operator::NextYear, []);
    }
}
