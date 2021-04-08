<?php

namespace SocialSpy\superbobby2000;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\command\ConsoleCommandSender;

use function in_array;

class EventListener implements Listener {

    public function onLeave(PlayerQuitEvent $event){
        $name = $event->getPlayer()->getName();
        if (in_array($name, Main::$SocialSpy)){
            unset(Main::$SocialSpy[array_search($name, Main::$SocialSpy)]);
        }
    }

    public function onPlayerCommand(PlayerCommandPreprocessEvent $event) {
        $console = new ConsoleCommandSender();
        $message = $event->getMessage();
        $player = $event->getPlayer();
        $m = substr("$message", 0, 1);
        if ($m == '/') {
            $console->sendMessage('§9SocialSpy §6»§r ' . TextFormat::GRAY . TextFormat::ITALIC . $player->getName() . TextFormat::RESET . ": " . TextFormat::AQUA . $message);
            if (Main::getMain()->getConfig()->get("webhook") == "on"){
                if (!$player->hasPermission("socialspy.hide")) {
                    $webhook_url = Main::getMain()->getConfig()->get("url");
                    $webhook_content = [
                        "content" => $player->getName() . ": " . $message,
                        "username" => "SocialSpy"
                    ];
                    Main::getMain()->getServer()->getAsyncPool()->submitTask(new WebhookTask($webhook_url, serialize($webhook_content)));
                }
            }
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