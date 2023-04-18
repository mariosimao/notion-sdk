<?php

namespace Notion\Blocks\Renderer\Markdown;

use Notion\Blocks\BlockInterface;
use Notion\Blocks\Heading1;
use Notion\Blocks\Renderer\BlockRendererInterface;
use Notion\Blocks\Renderer\MarkdownRenderer;

final class Heading1Renderer implements BlockRendererInterface
{
    public static function render(BlockInterface $block, int $depth = 0): string
    {
        if (!$block instanceof Heading1) {
            return "";
        }

        $main = RichTextRenderer::render(...$block->text);
        $markdown = MarkdownRenderer::ident("# {$main}", $depth);

        if ($block->children === null) {
            return $markdown;
        }

        foreach ($block->children as $child) {
            $markdown .= "\n\n" . MarkdownRenderer::renderBlock($child, $depth + 1);
        }
        return $markdown;
    }
}
