<?php

namespace xoapp\anothervaults\extension;

use xoapp\anothervaults\library\serializer\Serializer;

class CustomVault
{

    public function __construct(
        private readonly string $name,
        private array $contents = []
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContents(): array
    {
        return $this->contents;
    }

    public function setContents(array $contents): void
    {
        $this->contents = $contents;
    }

    public function serialize(): string
    {
        return Serializer::serialize($this->contents);
    }
}