<?php

namespace SocialSpy\sqrrl;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat as C;

use function in_array;
use function strtolower;
use function array_search;

class Main extends PluginBase {

    public const PREFIX = "§9SocialSpy §6»§r ";

    public static array $SocialSpy = [];

    protected static self $main;

    public function onEnable(): void {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        self::$main = $this;
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public static function getMain(): self {
        return self::$main;
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

        if(!in_array($name, self::$SocialSpy)) {
            self::$SocialSpy[] = $name;
            $sender->sendMessage(self::PREFIX . C::GREEN . "You have enabled SocialSpy");
        }else{
            unset(self::$SocialSpy[array_search($name, self::$SocialSpy)]);
            $sender->sendMessage(self::PREFIX . C::DARK_RED . "You have disabled SocialSpy");
        }
        return true;
    }
}
