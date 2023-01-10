<?php

namespace Notion\Pages\Properties;

use Notion\Common\RichText;

/**
 * @psalm-type NumberJson = array{
 *      id: string,
 *      type: "number",
 *      number: int|float|null,
 * }
 *
 * @psalm-immutable
 */
class Number implements PropertyInterface
{
    private function __construct(
        private readonly PropertyMetadata $metadata,
        public readonly int|float|null $number
    ) {
    }

    public static function create(int|float $number): self
    {
        $property = PropertyMetadata::create("", PropertyType::Number);

        return new self($property, $number);
    }

    public static function fromArray(array $array): self
    {
        /** @psalm-var NumberJson $array */

        $property = PropertyMetadata::fromArray($array);

        $number = $array["number"];

        return new self($property, $number);
    }

    public function toArray(): array
    {
        $array = $this->metadata->toArray();

        $array["number"] = $this->number;

        return $array;
    }

    public function metadata(): PropertyMetadata
    {
        return $this->metadata;
    }

    public function changeNumber(int|float $number): self
    {
        return new self($this->metadata, $number);
    }

    public function isEmpty(): bool
    {
        return $this->number === null;
    }
}
