<?php

namespace xoapp\anothervaults\commands\sub;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xoapp\anothervaults\extension\CustomVault;
use xoapp\anothervaults\session\SessionFactory;
use xoapp\anothervaults\Vaults;

class CreateSubCommand extends BaseSubCommand
{

    public function __construct(Vaults $vaults)
    {
        parent::__construct($vaults, "create");
    }

    protected function prepare(): void
    {
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
            $sender->sendMessage(TextFormat::colorize("&cUse /vault create <name>"));
            return;
        }

        $vault = $session->getVault($args['name']);

        if (!is_null($vault)) {
            $sender->sendMessage(TextFormat::colorize("&cThis vault already exists"));
            return;
        }

        $maxVaults = Vaults::getInstance()->getMaxVaults();
        $playerVaults = sizeof($session->getVaults());

        if ($playerVaults >= $maxVaults) {
            $sender->sendMessage(TextFormat::colorize("&cYou have exceeded the vault limit!"));
            return;
        }

        $session->putVault(new CustomVault($args['name']));

        $sender->sendMessage(TextFormat::colorize(
            "&aVault &e" . $args['name'] . "&a Successfully Created"
        ));
    }
}