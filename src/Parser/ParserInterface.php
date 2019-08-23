<?php

namespace Czechphp\InvalidDocument\Parser;

use Czechphp\InvalidDocument\Message\MessageInterface;

interface ParserInterface
{
    public function parse(string $content) : MessageInterface;
}
