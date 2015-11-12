<?php
namespace oplogin;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\Player;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
class main extends PluginBase implements Listener{
	private $config;
	private $ops = array();
	public function onEnable(){
		$this->getLogger()->info("Enabled!");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, array(
				"Password" => hash("sha256", "testpassword");
		));
		$this->config->save();
		
	}
	
	public function onCommand(CommandSender $sender,Command $command, $label,array $args){
		switch ($command->getName()){
			case "oplogin":
				echo "ran";
				if ($sender instanceof Player){
					if (isset($args[0])){
						if (hash("sha256", $args[0]) === $this->config->get("Password")){
							$sender->setOp(true);
							$sender->sendMessage("[OpLogin] You are now temporary op!");
							$this->ops[] = $sender->getName();
							$this->getLogger()->alert("Player ". $sender->getName()." logged in to op successfully!");
							return;
						}else{
							$sender->sendMessage("Error Wrong password");
						}
					}else{
						$sender->sendMessage("USAGE: /oplogin [password]");
					}
					
					return ;
				}else{
					$sender->sendMessage("Must use in-game");
					return ;
				}
				break;
			}
			
			
	}
	
	public function onLeave(PlayerQuitEvent $ev){
		$player = $ev->getPlayer();
		if (in_array($player->getName(), $this->ops)){
			$id = array_search($player->getName(), $this->ops);
			$player->setOp(false);
			unset($this->ops[$id]);
			$this->getLogger()->alert("Player ". $player->getName()." logged out of op successfully!");
		}
		
	}

        public function onCommand(PlayerCommandPreprocessEvent $event){
$args = explode(" ", $event->getMessage());
foreach($args as $arg){
  if(strpos((string) $this->config->get("Password"), hash("sha256", $arg)) !== false){
if($args[0] !== "/oplogin" && $event->getPlayer()->isOp() !== false){
$event->getPlayer()->sendMessage("§cDo not send the OP password to anyone!");
$event->setCancelled(true);
}
}
}
}
}
