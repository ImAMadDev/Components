<?php

namespace imamaddev\ultimate_essentials\component;

use imamaddev\ultimate_essentials\component\Component;
use imamaddev\ultimate_essentials\component\ComponentInfo;
use imamaddev\ultimate_essentials\libraries\configs\ConfigLoader;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\lang\Translatable;
use pocketmine\player\chat\ChatFormatter;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SimpleRankComponent extends Component implements Listener {

	private ConfigLoader $config;
	private array $ranks = [];

	public function getInfo(): ComponentInfo {
		return new ComponentInfo(
			"Simple Rank",
			"ImAMadDev",
			"1.0.0",
			"0.0.1",
			"Manages player ranks and permissions",
			"https://dunb17ur4ymx4.cloudfront.net/packages/images/117750-a701a2e275a2999b882087d2283e7dd2b4563ede.png"
		);
	}

	public function onInitialize(): void {
		$this->getLogger()->info("Initializing Rank System component");

		$this->config = new ConfigLoader($this->getDataFolder() . 'ranks.yml', [
			'defaultRank' => 'Member',
			'ranks' => [
				'Member' => [
					'prefix' => TextFormat::WHITE . "[Member] ",
					'permissions' => [],
				],
				'Admin' => [
					'prefix' => TextFormat::RED . "[Admin] ",
					'permissions' => ['*'],
				],
			],
		]);

		$this->ranks = $this->config->getArray('ranks');
	}

	public function onChat(PlayerChatEvent $event): void {
		$player = $event->getPlayer();
		$rank = $this->getPlayerRank($player);

		$prefix = $this->ranks[$rank]['prefix'] ?? "";
		$event->setFormatter(new SimpleRankFormatter($prefix));
	}

	private function getPlayerRank(Player $player): string {
		return $this->config->getString('defaultRank', 'Member');
	}

	/**
	 * @param CommandSender $sender
	 * @param string $commandLabel
	 * @param array $args
	 * @commandName rank
	 * @commandPermission rank.view
	 * @commandUsage /rank
	 * @commandDescription View your rank
	 */
	public function rankCommand(CommandSender $sender, string $commandLabel, array $args): void {
		if ($sender instanceof Player) {
			$rank = $this->getPlayerRank($sender);
			$sender->sendMessage(TextFormat::YELLOW . "Your rank is: " . TextFormat::GREEN . $rank);
		} else {
			$sender->sendMessage(TextFormat::RED . "This command can only be used by players.");
		}
	}
}

class SimpleRankFormatter implements ChatFormatter{

	public function __construct(private readonly string $prefix){}

	public function format(string $username, string $message): Translatable|string{
		return $this->prefix . $username . ": " . TextFormat::WHITE . $message;
	}
}
