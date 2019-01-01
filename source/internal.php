<?php

namespace Pre\Standard\Internal;

use Pre\Standard\Exception\AstMissingException;
use function Pre\Standard\Internal\flattened;

use Yay\Ast;
use Yay\Engine;
use Yay\Token;
use Yay\TokenStream;

function named($name, $prefix = null): string
{
    if ($prefix) {
        return $prefix . ucwords($name);
    }

    return $name;
}

function store(Ast $ast)
{
    if (!isset($GLOBALS["PRE_AST"])) {
        $GLOBALS["PRE_AST"] = [];
    }

    $tokens = flattened($ast->tokens());
    $GLOBALS["PRE_AST"][md5($tokens)] = $ast;
}

function match(TokenStream $stream)
{
    $hash = md5((string) $stream);

    if (isset($GLOBALS["PRE_AST"][$hash])) {
        return $GLOBALS["PRE_AST"][$hash];
    }

    throw new AstMissingException();
}

function first($item)
{
    if (is_array($item)) {
        return first(array_shift($item));
    }

    return $item;
}

function flattened(array $parts): string
{
    $name = "";

    foreach ($parts as $part) {
        if ($part instanceof Ast) {
            $part = $part->unwrap();
        }

        if (is_array($part)) {
            $name .= flattened($part);
        }

        if (is_string($part)) {
            $name .= $part;
        }

        if ($part instanceof Token) {
            $name .= $part->value();
        }
    }

    return $name;
}

function aerated(array $items): array
{
    $aerated = [];

    foreach (array_filter($items) as $i => $item) {
        array_push($aerated, $item);

        if ($i < count($items) - 1) {
            array_push($aerated, new Token(T_WHITESPACE, " "));
        }
    }

    return $aerated;
}

function streamed($source, Engine $engine): TokenStream
{
    if (is_array($source)) {
        $source = array_map(function ($item) {
            if (is_array($item)) {
                $item = first($item);
            }

            if (is_string($item)) {
                $item = new Token(T_STRING, $item);
            }

            return $item;
        }, $source);

        return TokenStream::fromSequence(...$source);
    }

    if (is_string($source)) {
        return TokenStream::fromSource(
            $engine->expand($source, $engine->currentFileName(), Engine::GC_ENGINE_DISABLED)
        );
    }
}
