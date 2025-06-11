<?php

namespace SocialSpy\sqrrl;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat as C;

use function strtolower;

class Main extends PluginBase {

    public const PREFIX = "§9SocialSpy §6»§r ";

    private array $socialSpy = [];

    public function onEnable(): void {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public function isSocialSpyEnabled(string $playerName): bool {
        return isset($this->socialSpy[$playerName]);
    }

    public function disableSocialSpy(string $playerName): void {
        unset($this->socialSpy[$playerName]);
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool {
        $name = $sender->getName();
        switch(strtolower($cmd->getName())) {
            case "socialspy":
            case "ss":
                if (!$sender instanceof Player) {
                    $sender->sendMessage(self::PREFIX . C::DARK_RED . "Use this command InGame.");
                    return false;
                }
        }
        if(!$sender->hasPermission("socialspy.command")){
            $sender->sendMessage(self::PREFIX . C::DARK_RED . "You do not have permission to use this command");
            return false;
        }

        if(!isset($this->socialSpy[$name])) {
            $this->socialSpy[$name] = true;
            $sender->sendMessage(self::PREFIX . C::GREEN . "You have enabled SocialSpy");
        }else{
            $this->disableSocialSpy($name);
            $sender->sendMessage(self::PREFIX . C::DARK_RED . "You have disabled SocialSpy");
        }
        return true;
    }
}
