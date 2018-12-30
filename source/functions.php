<?php

namespace Pre\Standard\Parser {
    use Pre\Standard\Parser\ArgumentParser;
    use Pre\Standard\Parser\ArgumentsParser;
    use Pre\Standard\Parser\TypeParser;

    use Yay\Parser;

    function argument(string $prefix = null): Parser
    {
        return (new ArgumentParser())->parse($prefix);
    }

    function arguments(string $prefix = null): Parser
    {
        return (new ArgumentsParser())->parse($prefix);
    }

    function type(string $prefix = null): Parser
    {
        return (new TypeParser())->parse($prefix);
    }
}

namespace Pre\Standard\Expander {
    use Pre\Standard\Expander\ArgumentExpander;
    use Pre\Standard\Expander\ArgumentsExpander;
    use function Pre\Standard\streamed;

    use Yay\Engine;
    use Yay\TokenStream;

    function argument(TokenStream $stream, Engine $engine): TokenStream
    {
        return (new ArgumentExpander())->expand($stream, $engine);
    }

    function arguments(TokenStream $stream, Engine $engine): TokenStream
    {
        return (new ArgumentsExpander())->expand($stream, $engine);
    }

    function studly(TokenStream $stream, Engine $engine): TokenStream
    {
        $stream = \str_replace(["-", "_"], " ", $stream);
        $stream = \str_replace(" ", "", \ucwords($stream));
        return streamed($stream, $engine);
    }
}

namespace Pre\Standard {
    use Pre\Standard\Exception\AstMissingException;

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

        array_push($GLOBALS["PRE_AST"], $ast);
    }

    function match(TokenStream $stream)
    {
        $tokens = [];

        do {
            array_push($tokens, $stream->current());
        } while ($stream->next());

        foreach ($GLOBALS["PRE_AST"] as $ast) {
            if (json_encode($tokens) === json_encode($ast->tokens())) {
                return $ast;
            }
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

    function flatten(array $parts): string
    {
        $name = "";

        foreach ($parts as $part) {
            if ($part instanceof Ast) {
                $part = $part->unwrap();
            }

            if (is_array($part)) {
                $name .= flatten($part);
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

    function streamed($from, Engine $engine): TokenStream
    {
        if (is_array($from)) {
            $from = array_map(function ($item) {
                if (is_array($item)) {
                    $item = first($item);
                }

                if (is_string($item)) {
                    $item = new Token(T_STRING, $item);
                }

                return $item;
            }, $from);

            return TokenStream::fromSequence(...$from);
        }

        if (is_string($from)) {
            return TokenStream::fromSource(
                $engine->expand($from, $engine->currentFileName(), Engine::GC_ENGINE_DISABLED)
            );
        }
    }
}
