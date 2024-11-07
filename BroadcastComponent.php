<?php

use imamaddev\ultimate_essentials\component\Component;
use imamaddev\ultimate_essentials\component\ComponentInfo;
use imamaddev\ultimate_essentials\libraries\configs\ConfigLoader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class BroadcastComponent extends Component implements Listener{

	private ConfigLoader $config;

	public static function getInfo(): ComponentInfo{
		return new ComponentInfo("Broadcast", "ImAMadDev", "1.0.0", "0.0.1", "Broadcast messages", "https://cdn-icons-png.flaticon.com/512/5969/5969113.png");
	}

	public function onInitialize(): void{
		$this->config = new ConfigLoader($this->getDataFolder() . 'config.yml', ["interval" => 120, "prefix" => "§7[§eBroadcast§7] §r", "messages" => ["§aWelcome to my server", "§3Enjoy your stay", "§7Be respectful of others"]]);
	}

	public function onStartup(): void{
		$this->registerRepeatingTask(function(){
			$messages = $this->config->getArray('messages');
			$this->getServer()->broadcastMessage($messages[array_rand($messages)]);
		}, $this->config->getInt('interval') * 20);
	}
}
