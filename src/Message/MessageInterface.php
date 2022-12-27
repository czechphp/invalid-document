<?php

declare(strict_types=1);

namespace Czechphp\InvalidDocument\Message;

interface MessageInterface
{
    public function isRegistered(): bool;
}
