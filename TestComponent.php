<?php

use imamaddev\ultimate_essentials\component\Component;
use imamaddev\ultimate_essentials\component\ComponentInfo;
use imamaddev\ultimate_essentials\libraries\configs\ConfigLoader;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\utils\TextFormat;

class TestComponent extends Component implements Listener{

	private ConfigLoader $config;

	public function getInfo(): ComponentInfo{
		return new ComponentInfo(
			"Test Component",
			"ImAMadDev",
			"1.0.0",
			"1.0.0",
			"Test component description",
			"https://cdn-icons-png.flaticon.com/512/5969/5969113.png",
			[
				'Double Jump'
			]
		);
	}

	public function onInitialize(): void{
		$this->getLogger()->info("Initializing component");
		$this->config = new ConfigLoader($this->getDataFolder() . 'settings.yml', [
			'welcomeMessage' => "Welcome to Test Component",
		]);
	}

	public function onStartup(): void{
		$this->getLogger()->info("Startup component");
		$this->registerRepeatingTask(function(){
			foreach($this->getServer()->getOnlinePlayers() as $player){
				$player->sendTip("TPS: " . $this->getServer()->getTicksPerSecond());
			}
		}, 20);
	}

	public function onShutdown(): void{
		$this->getLogger()->info("Shutdown component");
	}

	public function playerJoin(PlayerToggleSneakEvent $event): void{
		$player = $event->getPlayer();
		if($event->isSneaking()){
			$player->sendMessage($this->config->getString('welcomeMessage'));
		}
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @return void
	 * @commandName test
	 * @commandPermission test.command
	 * @commandUsage /test
	 * @commandDescription Test command
	 * @commandAliases tester
	 */
	public function testCommand(CommandSender $sender, string $commandLabel, array $args): void{
		$sender->sendMessage(TextFormat::GREEN . 'Hello');
	}
}