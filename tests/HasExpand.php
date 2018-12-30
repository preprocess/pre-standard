<?php

namespace Pre\Standard\Tests;

use Yay\Engine;

trait HasExpand
{
    protected function expand($code)
    {
        $macro = "";

        if (property_exists($this, "macro")) {
            $macro = $this->macro;
        }

        $engine = new Engine();

        $code = $engine->expand(
            $macro . $code,
            $engine->currentFileName(),
            Engine::GC_ENGINE_DISABLED
        );

        return trim($code);
    }
}
