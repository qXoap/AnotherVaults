<?php

namespace xoapp\anothervaults;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use xoapp\anothervaults\session\SessionFactory;

class EventHandler implements Listener
{

    public function onPlayerJoin(PlayerJoinEvent $event): void
    {
        SessionFactory::getInstance()->create($event->getPlayer());
    }

    public function onPlayerQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();
        $session = SessionFactory::getInstance()->get($player->getName());

        if (is_null($session)) {
            return;
        }

        $session->save();
        SessionFactory::getInstance()->delete($player->getName());
    }
}