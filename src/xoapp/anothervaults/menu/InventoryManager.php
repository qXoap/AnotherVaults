<?php

namespace xoapp\anothervaults\menu;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use xoapp\anothervaults\extension\CustomVault;

class InventoryManager
{

    public static function openVault(Player $player, CustomVault $vault): void
    {
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->setName(TextFormat::colorize(
            "&c" . $player->getName() . " &7 - &2" . $vault->getName()
        ));

        $menu->getInventory()->setContents($vault->getContents());

        $menu->setInventoryCloseListener(
            function (Player $player, Inventory $inventory) use ($vault) : void {
                $vault->setContents($inventory->getContents());
                $player->sendMessage(TextFormat::colorize("&aVault successfully updated"));
            }
        );

        $menu->send($player);
    }
}