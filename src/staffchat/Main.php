<?php

declare(strict_types=1);

namespace staffchat;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\{Config, TextFormat};
use pocketmine\command\{Command, CommandSender};

class Main extends PluginBase implements Listener{
	
	public function onEnable() : void{
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
		switch ($cmd->getName()) {
			case "staffchat":
				if($sender->hasPermission("staffchat.cmd") or $sender->isOp()){
					if(!isset($args[0])) {
						$sender->sendMessage("§l§f» §cUsage: §7/staffchat <message>");
						return false;
					}
					$msg = str_replace(["{player}", "{msg}", "&"], [$sender->getName(), implode(" ", $args), "§"], $this->getConfig()->get("FormatChat"));
					$this->getServer()->getLogger()->info($msg);
					$count = 0;
					foreach($this->getServer()->getOnlinePlayers() as $players){
						if($players->isOp() or $players->hasPermission("staffchat.cmd")){
							$players->sendMessage($msg);
							++$count;
						}
					}
				}else{
					$sender->sendMessage("§cUnknown command. Try /help for a list of commands");
				}
			break;
		}
		return true;
	}
}