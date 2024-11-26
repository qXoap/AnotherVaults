<?php

namespace xoapp\anothervaults\commands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xoapp\anothervaults\commands\sub\CreateSubCommand;
use xoapp\anothervaults\commands\sub\DeleteSubCommand;
use xoapp\anothervaults\commands\sub\ListSubCommand;
use xoapp\anothervaults\menu\InventoryManager;
use xoapp\anothervaults\session\SessionFactory;
use xoapp\anothervaults\Vaults;

class VaultCommand extends BaseCommand
{

    public function __construct(private readonly Vaults $vaults)
    {
        parent::__construct($this->vaults, "vault");
        $this->setAliases(['vault', 'v']);
        $this->setPermission("anothervaults.command");
    }

    protected function prepare(): void
    {
        $subCommands = [
            new CreateSubCommand($this->vaults),
            new DeleteSubCommand($this->vaults),
            new ListSubCommand($this->vaults)
        ];

        array_walk($subCommands,
            fn (BaseSubCommand $command) => $this->registerSubCommand($command)
        );

        $this->registerArgument(0, new RawStringArgument("name"));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $session = SessionFactory::getInstance()->get($sender->getName());

        if (is_null($session)) {
            return;
        }

        if (is_null($args['name'])) {
            $sender->sendMessage(TextFormat::colorize("&cUse /vault help"));
            return;
        }

        $vault = $session->getVault($args['name']);

        if (is_null($vault)) {
            $sender->sendMessage(TextFormat::colorize("&cThis vault doesn't exists"));
            return;
        }

        InventoryManager::openVault($sender, $vault);
    }
}