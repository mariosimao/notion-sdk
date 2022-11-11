<?php

namespace Notion\Blocks;

use Notion\Exceptions\BlockException;
use Notion\Common\RichText;
use Notion\Exceptions\HeadingException;

/**
 * @psalm-import-type BlockMetadataJson from BlockMetadata
 * @psalm-import-type RichTextJson from \Notion\Common\RichText
 *
 * @psalm-type Heading2Json = array{
 *      heading_2: array{
 *          rich_text: RichTextJson[],
 *          is_toggleable: bool,
 *          children?: BlockMetadataJson[]
 *      },
 * }
 *
 * @psalm-immutable
 */
class Heading2 implements BlockInterface
{
    /**
     * @param RichText[] $text
     * @param BlockInterface[]|null $children
     */
    private function __construct(
        private readonly BlockMetadata $metadata,
        public readonly array $text,
        public readonly bool $isToggleable,
        public readonly array|null $children,
    ) {
        $metadata->checkType(BlockType::Heading2);
    }

    public static function fromText(RichText ...$text): self
    {
        $block = BlockMetadata::create(BlockType::Heading2);

        return new self($block, $text, false, []);
    }

    public static function fromString(string $content): self
    {
        $block = BlockMetadata::create(BlockType::Heading2);
        $text = [ RichText::fromString($content) ];

        return new self($block, $text, false, []);
    }

    public static function fromArray(array $array): self
    {
        /** @psalm-var BlockMetadataJson $array */
        $block = BlockMetadata::fromArray($array);

        /** @psalm-var Heading2Json $array */
        $heading = $array["heading_2"];

        $text = array_map(fn($t) => RichText::fromArray($t), $heading["rich_text"]);

        $isToggleable = $heading["is_toggleable"];

        $children = null;
        if ($isToggleable) {
            $children = array_map(fn($b) => BlockFactory::fromArray($b), $heading["children"] ?? []);
        }

        return new self($block, $text, $isToggleable, $children);
    }

    public function toArray(): array
    {
        $array = $this->metadata->toArray();

        $array["heading_2"] = [
            "rich_text" => array_map(fn(RichText $t) => $t->toArray(), $this->text),
            "is_toggleable" => $this->isToggleable,
            "children" => array_map(fn($b) => $b->toArray(), $this->children ?? [])
        ];

        return $array;
    }

    public function toString(): string
    {
        $string = "";
        foreach ($this->text as $richText) {
            $string = $string . $richText->plainText;
        }

        return $string;
    }

    public function metadata(): BlockMetadata
    {
        return $this->metadata;
    }

    public function changeText(RichText ...$text): self
    {
        return new self($this->metadata, $text, $this->isToggleable, $this->children);
    }

    public function addText(RichText $text): self
    {
        $texts = $this->text;
        $texts[] = $text;

        return new self($this->metadata, $texts, $this->isToggleable, $this->children);
    }

    public function toggllify(): self
    {
        return new self($this->metadata, $this->text, true, []);
    }

    public function untogglify(): self
    {
        if (!empty($this->children)) {
            throw HeadingException::untogglifyWithChildren();
        }

        return new self($this->metadata, $this->text, false, null);
    }

    public function addChild(BlockInterface $child): self
    {
        if (!$this->isToggleable) {
            throw BlockException::noChindrenSupport();
        }

        return new self(
            $this->metadata,
            $this->text,
            $this->isToggleable,
            [...$this->children, $child],
        );
    }

    public function changeChildren(BlockInterface ...$children): self
    {
        if (!$this->isToggleable) {
            throw BlockException::noChindrenSupport();
        }

        return new self($this->metadata, $this->text, $this->isToggleable, $children);
    }

    public function archive(): BlockInterface
    {
        return new self(
            $this->metadata->archive(),
            $this->text,
            $this->isToggleable,
            $this->children,
        );
    }
}
