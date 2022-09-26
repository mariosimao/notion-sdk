<?php

namespace Notion\Pages\Properties;

use Notion\Common\File;

/**
 * @psalm-import-type FileJson from \Notion\Common\File
 *
 * @psalm-type FilesJson = array{
 *      id: string,
 *      type: "files",
 *      files: FileJson[],
 * }
 *
 * @psalm-immutable
 */
class Files implements PropertyInterface
{
    /** @param File[] $files */
    private function __construct(
        private readonly PropertyMetadata $metadata,
        public readonly array $files,
    ) {
    }

    public static function create(File ...$files): self
    {
        $property = PropertyMetadata::create("", PropertyType::Files);

        return new self($property, $files);
    }

    public static function fromArray(array $array): self
    {
        /** @psalm-var FilesJson $array */
        $property = PropertyMetadata::fromArray($array);

        $files = array_map(fn($f) => File::fromArray($f), $array["files"]);

        return new self($property, $files);
    }

    public function toArray(): array
    {
        $array = $this->metadata->toArray();

        $array["files"] = array_map(fn($f) => $f->toArray(), $this->files);

        return $array;
    }

    public function metadata(): PropertyMetadata
    {
        return $this->metadata;
    }

    public function changeFiles(File ...$files): self
    {
        return new self($this->metadata, $files);
    }

    public function addFile(File $file): self
    {
        $files = array_merge($this->files, [$file]);

        return new self($this->metadata, $files);
    }
}
