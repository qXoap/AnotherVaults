<?php

namespace xoapp\anothervaults\commands\sub;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xoapp\anothervaults\session\SessionFactory;
use xoapp\anothervaults\Vaults;

class ListSubCommand extends BaseSubCommand
{

    public function __construct(Vaults $vaults)
    {
        parent::__construct($vaults, "list");
    }

    protected function prepare(): void
    {
        // TODO: Implement prepare() method.
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

        $vaults = $session->getVaults();

        if (empty($vaults)) {
            $sender->sendMessage(TextFormat::colorize("&cYour session has no vaults"));
            return;
        }

        $keys = implode(",", array_keys($vaults));

        $sender->sendMessage(TextFormat::colorize(
            "&aAvailable Vaults &e(" . sizeof($vaults) . "&7: " . $keys
        ));
    }
}