<?php

namespace xoapp\anothervaults;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;
use xoapp\anothervaults\commands\VaultCommand;
use xoapp\anothervaults\session\SessionFactory;

class Vaults extends PluginBase
{
    use SingletonTrait {
        setInstance as private;
        reset as private;
    }

    protected function onEnable(): void
    {
        self::setInstance($this);

        /** LOAD DIRS */
        if (!is_dir(Path::join($this->getDataFolder(), "players/"))) {
            mkdir(Path::join($this->getDataFolder(), "players/"), 0777, true);
        }

        /** LOAD VIRIONS */
        if (!InvMenuHandler::isRegistered()) InvMenuHandler::register($this);

        /** LOAD COMMAND */
        $this->getServer()
            ->getCommandMap()
            ->register("vault", new VaultCommand($this));

        /** LOAD HANDLER */
        $this->getServer()
            ->getPluginManager()
            ->registerEvents(new EventHandler(), $this);
    }

    protected function onDisable(): void
    {
        SessionFactory::getInstance()->close();
    }

    public function getMaxVaults(): int
    {
        return $this->getConfig()->get("maxVaults", 4);
    }
}