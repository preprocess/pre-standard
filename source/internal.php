<?php

namespace Pre\Standard\Internal;

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

function streamed(array $source, Engine $engine): TokenStream
{
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
