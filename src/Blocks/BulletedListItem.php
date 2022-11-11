<?php

namespace Notion\Blocks;

use Notion\Common\RichText;

/**
 * Bulleted list item
 *
 * @psalm-import-type BlockMetadataJson from BlockMetadata
 * @psalm-import-type RichTextJson from \Notion\Common\RichText
 *
 * @psalm-type BulletedListItemJson = array{
 *      bulleted_list_item: array{
 *          rich_text: list<RichTextJson>,
 *          children?: list<BlockMetadataJson>,
 *      },
 * }
 *
 * @psalm-immutable
 */
class BulletedListItem implements BlockInterface
{
    /**
     * @param RichText[] $text
     * @param BlockInterface[] $children
     */
    private function __construct(
        private readonly BlockMetadata $metadata,
        public readonly array $text,
        public readonly array $children,
    ) {
        $this->metadata->checkType(BlockType::BulletedListItem);
    }

    /**
     * Create empty bulleted list item
     */
    public static function create(): self
    {
        $metadata = BlockMetadata::create(BlockType::BulletedListItem);

        return new self($metadata, [], []);
    }

    /**
     * Create bulleted list item from a string
     */
    public static function fromString(string $content): self
    {
        $metadata = BlockMetadata::create(BlockType::BulletedListItem);
        $text = [ RichText::fromString($content) ];

        return new self($metadata, $text, []);
    }

    public static function fromArray(array $array): self
    {
        /** @psalm-var BlockMetadataJson $array */
        $metadata = BlockMetadata::fromArray($array);

        /** @psalm-var BulletedListItemJson $array */
        $item = $array["bulleted_list_item"];

        $text = array_map(fn($t) => RichText::fromArray($t), $item["rich_text"]);

        $children = array_map(fn($b) => BlockFactory::fromArray($b), $item["children"] ?? []);

        return new self($metadata, $text, $children);
    }

    public function toArray(): array
    {
        $array = $this->metadata->toArray();

        $array["bulleted_list_item"] = [
            "rich_text" => array_map(fn(RichText $t) => $t->toArray(), $this->text),
            "children"  => array_map(fn(BlockInterface $b) => $b->toArray(), $this->children),
        ];

        return $array;
    }

    /** Get item content as string */
    public function toString(): string
    {
        return RichText::multipleToString(...$this->text);
    }

    public function metadata(): BlockMetadata
    {
        return $this->metadata;
    }

    public function changeText(RichText ...$text): self
    {
        return new self($this->metadata->update(), $text, $this->children);
    }

    /**
     * add text to list item
     */
    public function addText(RichText $text): self
    {
        $texts = $this->text;
        $texts[] = $text;

        return new self($this->metadata, $texts, $this->children);
    }

    public function changeChildren(BlockInterface ...$children): self
    {
        $hasChildren = (count($children) > 0);

        return new self(
            $this->metadata->updateHasChildren($hasChildren),
            $this->text,
            $children,
        );
    }

    public function addChild(BlockInterface $child): self
    {
        $children = $this->children;
        $children[] = $child;

        return new self(
            $this->metadata->updateHasChildren(true),
            $this->text,
            $children,
        );
    }

    public function archive(): BlockInterface
    {
        return new self(
            $this->metadata->archive(),
            $this->text,
            $this->children,
        );
    }
}
