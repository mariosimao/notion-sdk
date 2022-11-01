<?php

namespace Notion\Databases;

/**
 * @psalm-type DatabaseParentJson = array{
 *      type: "page_id"|"workspace",
 *      page_id?: string,
 * }
 *
 * @psalm-immutable
 */
class DatabaseParent
{
    private function __construct(
        public readonly DatabaseParentType $type,
        public readonly string|null $id,
    ) {
    }

    public static function page(string $pageId): self
    {
        return new self(DatabaseParentType::Page, $pageId);
    }

    public static function workspace(): self
    {
        return new self(DatabaseParentType::Workspace, null);
    }

    /**
     * @param DatabaseParentJson $array
     *
     * @internal
     */
    public static function fromArray(array $array): self
    {
        $type = DatabaseParentType::from($array["type"]);

        $id = $array["page_id"] ?? null;

        return new self($type, $id);
    }

    public function toArray(): array
    {
        $array = [];

        if ($this->isPage()) {
            $array["page_id"] = $this->id;
        }
        if ($this->isWorkspace()) {
            $array["workspace"] = true;
        }

        return $array;
    }

    public function isPage(): bool
    {
        return $this->type === DatabaseParentType::Page;
    }

    public function isWorkspace(): bool
    {
        return $this->type === DatabaseParentType::Workspace;
    }
}
