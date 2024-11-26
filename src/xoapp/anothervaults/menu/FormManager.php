<?php

namespace xoapp\anothervaults\menu;

use dktapps\pmforms\ModalForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xoapp\anothervaults\extension\CustomVault;
use xoapp\anothervaults\session\Session;

class FormManager
{

    public static function openConfirmDelete(Session $session, CustomVault $vault): void
    {
        $form = new ModalForm(
            "Confirm Vault Deletion",
            "Are you sure you want to delete this vault? \n If you delete it, you will lose all the items inside. \n Press confirm to proceed with the vault deletion.",
            function (Player $player, bool $success) use ($session, $vault) : void {
                if (!$success) {
                    return;
                }

                $session->deleteVault($vault->getName());
                $player->sendMessage(TextFormat::colorize("&aVault successfully removed"));
            },
            "Confirm", "Cancel"
        );

        $session->getPlayer()?->sendForm($form);
    }
}