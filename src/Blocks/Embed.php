<?php

namespace Notion\Blocks;

use Notion\Exceptions\BlockException;

/**
 * @psalm-import-type BlockMetadataJson from BlockMetadata
 *
 * @psalm-type EmbedJson = array{
 *      embed: array{ url: string },
 * }
 *
 * @psalm-immutable
 */
class Embed implements BlockInterface
{
    private function __construct(
        private readonly BlockMetadata $metadata,
        public readonly string $url,
    ) {
        $metadata->checkType(BlockType::Embed);
    }

    public static function create(string $url = ""): self
    {
        $metadata = BlockMetadata::create(BlockType::Embed);

        return new self($metadata, $url);
    }

    public static function fromArray(array $array): self
    {
        /** @psalm-var BlockMetadataJson $array */
        $metadata = BlockMetadata::fromArray($array);

        /** @psalm-var EmbedJson $array */
        $url = $array["embed"]["url"];

        return new self($metadata, $url);
    }

    public function toArray(): array
    {
        $array = $this->metadata->toArray();

        $array["embed"] = [ "url" => $this->url ];

        return $array;
    }

    /** @internal */
    public function toUpdateArray(): array
    {
        return [
            "embed" => [
                "url" => $this->url
            ],
            "archived" => $this->metadata()->archived,
        ];
    }

    public function metadata(): BlockMetadata
    {
        return $this->metadata;
    }

    public function changeUrl(string $url): self
    {
        return new self($this->metadata, $url);
    }

    public function addCHild(BlockInterface $child): self
    {
        throw BlockException::noChindrenSupport();
    }

    public function changeChildren(BlockInterface ...$children): self
    {
        throw BlockException::noChindrenSupport();
    }

    public function archive(): BlockInterface
    {
        return new self(
            $this->metadata->archive(),
            $this->url,
        );
    }
}
