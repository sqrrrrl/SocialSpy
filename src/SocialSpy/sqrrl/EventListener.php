<?php

namespace SocialSpy\sqrrl;

use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;
use pocketmine\event\server\CommandEvent;

use function igbinary_serialize;

class EventListener implements Listener {

    public function __construct(private Main $plugin) {}

    public function onLeave(PlayerQuitEvent $event){
        $name = $event->getPlayer()->getName();
        if ($this->plugin->isSocialSpyEnabled($name)){
            $this->plugin->disableSocialSpy($name);
        }
    }

    /**
     * @param CommandEvent $event
     * @priority MONITOR
     */
    public function onPlayerCommand(CommandEvent $event) {
        $server = $this->plugin->getServer();
        $console = new ConsoleCommandSender($server, $server->getLanguage());
        $command = $event->getCommand();
        $sender = $event->getSender();
        $console->sendMessage("§9SocialSpy §6»§r " . TextFormat::GRAY . TextFormat::ITALIC . $sender->getName() . TextFormat::RESET . ": " . TextFormat::AQUA . "/" . $command);
        if ($this->plugin->getConfig()->get("webhook") == "on"){
            if (!$sender->hasPermission("socialspy.hide")) {
                $webhook_url = $this->plugin->getConfig()->get("url");
                $webhook_content = [
                    "content" => $sender->getName() . ": /" . $command,
                    "username" => "SocialSpy"
                ];
                $server->getAsyncPool()->submitTask(new WebhookTask($webhook_url, igbinary_serialize($webhook_content)));
            }
        }
        foreach ($server->getOnlinePlayers() as $player) {
            if ($this->plugin->isSocialSpyEnabled($player->getName())) {
                if (!$sender->hasPermission("socialspy.hide")) {
                    $player->sendMessage("§9SocialSpy §6»§r " . TextFormat::GRAY . TextFormat::ITALIC . $sender->getName() . TextFormat::RESET . ": " . TextFormat::AQUA . "/" . $command);
                }
            }
        }
    }
}