<?php

namespace xoapp\anothervaults\session;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class SessionFactory
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    /** @var Session[] */
    private array $sessions = [];

    public function __construct()
    {
        self::setInstance($this);
    }

    public function create(Player $player): void
    {
        $this->sessions[$player->getName()] = new Session($player->getName());
    }

    public function get(string $name): ?Session
    {
        return $this->sessions[$name] ?? null;
    }

    public function delete(string $name): void
    {
        unset($this->sessions[$name]);
    }

    public function getAll(): array
    {
        return $this->sessions;
    }

    public function close(): void
    {
        foreach ($this->sessions as $session) {
            $session->save();
        }
    }
}