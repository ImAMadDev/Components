<?php

namespace imamaddev\ultimate_essentials\component;

use imamaddev\ultimate_essentials\component\Component;
use imamaddev\ultimate_essentials\component\ComponentInfo;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\player\Player;
use pocketmine\world\particle\ExplodeParticle;

class DoubleJumpComponent extends Component implements Listener {

	/** @var array<string, bool> */
	private array $canDoubleJump = [];

	public static function getInfo(): ComponentInfo {
		return new ComponentInfo(
			"Double Jump",
			"ImAMadDev",
			"1.0.0",
			"1.0.0",
			"Allows players to double jump",
			"https://cdn-icons-png.flaticon.com/512/5969/5969113.png"
		);
	}

	public function onToggleSneak(PlayerJumpEvent $event): void {
		$player = $event->getPlayer();

		if (isset($this->canDoubleJump[$player->getName()]) && $this->canDoubleJump[$player->getName()]) {
			$player->getWorld()->addParticle($player->getPosition(), new ExplodeParticle());
			$player->setMotion($player->getMotion()->add(0, $player->getJumpVelocity(), 0));
			$player->sendMessage("Double Jump Activated!");
			$this->canDoubleJump[$player->getName()] = false;
		}
	}

	public function onMove(PlayerMoveEvent $event): void {
		$player = $event->getPlayer();

		if (!$player->isOnGround()) {
			$this->canDoubleJump[$player->getName()] = true;
		}
	}
}
