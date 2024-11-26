<?php

namespace xoapp\anothervaults\data;

use pocketmine\utils\Config;
use Symfony\Component\Filesystem\Path;
use xoapp\anothervaults\Vaults;

class DataCreator
{
    private Config $config;

    public function __construct(string $name)
    {
        $path = Path::join(Vaults::getInstance()->getDataFolder(), "players/" . $name . ".json");

        $this->config = new Config($path, Config::JSON);
    }

    public function set(string $Key, mixed $Value): void
    {
        $this->config->set($Key, $Value);
        $this->config->save();
    }

    public function get(string $Key): mixed
    {
        return $this->config->get($Key);
    }

    public function delete(string $key): void
    {
        $this->config->remove($key);
        $this->config->save();
    }

    public function getSavedData(): array
    {
        return $this->config->getAll();
    }
}