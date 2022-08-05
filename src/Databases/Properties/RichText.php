<?php

namespace Notion\Databases\Properties;

/**
 * @psalm-type RichTextJson = array{
 *      id: string,
 *      name: string,
 *      type: "rich_text",
 *      rich_text: array<empty, empty>,
 * }
 *
 * @psalm-immutable
 */
class RichText implements PropertyInterface
{
    private const TYPE = Property::TYPE_RICH_TEXT;

    private Property $property;

    private function __construct(Property $property)
    {
        $this->property = $property;
    }

    public static function create(string $propertyName): self
    {
        $property = Property::create("", $propertyName, self::TYPE);

        return new self($property);
    }

    public function property(): Property
    {
        return $this->property;
    }

    public static function fromArray(array $array): self
    {
        /** @psalm-var RichTextJson $array */
        $property = Property::fromArray($array);

        return new self($property);
    }

    public function toArray(): array
    {
        $array = $this->property->toArray();
        $array[self::TYPE] = new \stdClass();

        return $array;
    }
}
