<?php

namespace SocialSpy\superbobby2000;

use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\command\ConsoleCommandSender;

use function in_array;

class EventListener implements Listener {

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerCommand(PlayerCommandPreprocessEvent $event)
    {
        $console = new ConsoleCommandSender();
        $message = $event->getMessage();
        $player = $event->getPlayer();
        $m = substr("$message", 0, 1);
        if ($m == '/') {
            $console->sendMessage('§9SocialSpy §6»§r ' . TextFormat::GRAY . TextFormat::ITALIC . $player->getName() . TextFormat::RESET . ": " . TextFormat::AQUA . $message);
            foreach (Server::getInstance()->getOnlinePlayers() as $p) {
                if (in_array($p->getName(), Main::$SocialSpy)) {
                    if (!$player->hasPermission("socialspy.hide")) {
                        $p->sendMessage('§9SocialSpy §6»§r ' . TextFormat::GRAY . TextFormat::ITALIC . $player->getName() . TextFormat::RESET . ": " . TextFormat::AQUA . $message);
                    }
                }
            }
        }
    }
}