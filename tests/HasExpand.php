<?php

namespace Pre\Standard\Tests;

use PHPUnit\Framework\Warning;
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

        $code = $engine->expand($macro . $code, $engine->currentFileName(), Engine::GC_ENGINE_DISABLED);
        $code = trim($code);

        $encoded = base64_encode("<?php\n\n" . $code);
        $path = __DIR__;

        $command = "node -e '
            const atob = require(\"atob\")
            const prettier = require(\"prettier\")

            prettier.resolveConfig(\"{$path}\").then(options => {
                try {
                    const formatted = prettier.format(atob(\"{$encoded}\").trim(), options)
                    console.log(formatted)
                } catch (e) {
                    console.log(\"error\")
                    console.log(e)
                }
            })
        '";

        exec($command, $output);

        if ($output[0] === "error") {
            $this->warn($code);
            return $code;
        }

        $output = join("\n", $output);
        $output = trim(substr($output, 5));

        return $output;
    }

    protected function warn($message, Exception $previous = null)
    {
        $message = "Prettier won't format: " . PHP_EOL . PHP_EOL . $message;

        $result = $this->getTestResultObject();
        $result->addWarning($this, new Warning($message, 0, $previous), time());

        $this->setTestResultObject($result);
    }
}
