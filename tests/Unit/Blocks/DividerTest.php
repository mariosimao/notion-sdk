<?php

namespace Notion\Test\Unit\Blocks;

use Notion\Blocks\BlockFactory;
use Notion\Blocks\Divider;
use Notion\Blocks\Exceptions\BlockTypeException;
use Notion\Common\Date;
use Notion\NotionException;
use PHPUnit\Framework\TestCase;

class DividerTest extends TestCase
{
    public function test_create_divider(): void
    {
        $divider = Divider::create();

        $this->assertEquals("divider", $divider->block()->type());
    }

    public function test_create_from_array(): void
    {
        $array = [
            "object"           => "block",
            "id"               => "04a13895-f072-4814-8af7-cd11af127040",
            "created_time"     => "2021-10-18T17:09:00.000Z",
            "last_edited_time" => "2021-10-18T17:09:00.000Z",
            "archived"         => false,
            "has_children"     => false,
            "type"             => "divider",
            "divider"          => new \stdClass(),
        ];

        $divider = Divider::fromArray($array);

        $this->assertTrue($divider->block()->isDivider());

        $this->assertEquals($divider, BlockFactory::fromArray($array));
    }

    public function test_error_on_wrong_type(): void
    {
        $this->expectException(BlockTypeException::class);
        $array = [
            "object"           => "block",
            "id"               => "04a13895-f072-4814-8af7-cd11af127040",
            "created_time"     => "2021-10-18T17:09:00.000Z",
            "last_edited_time" => "2021-10-18T17:09:00.000Z",
            "archived"         => false,
            "has_children"     => false,
            "type"             => "wrong-type",
            "divider"          => new \stdClass(),
        ];

        Divider::fromArray($array);
    }

    public function test_transform_in_array(): void
    {
        $divider = Divider::create();

        $expected = [
            "object"           => "block",
            "created_time"     => $divider->block()->createdTime()->format(Date::FORMAT),
            "last_edited_time" => $divider->block()->createdTime()->format(Date::FORMAT),
            "archived"         => false,
            "has_children"     => false,
            "type"             => "divider",
            "divider"          => new \stdClass(),
        ];

        $this->assertEquals($expected, $divider->toArray());
    }

    public function test_no_children_support(): void
    {
        $block = Divider::create();

        $this->expectException(NotionException::class);
        /** @psalm-suppress UnusedMethodCall */
        $block->changeChildren([]);
    }

    public function test_array_for_update_operations(): void
    {
        $block = Divider::create();

        $array = $block->toUpdateArray();

        $this->assertCount(2, $array);
    }
}
