<?php

namespace Mine\MineRewards\item;

use Mine\MineRewards\Main;
use Mine\MineRewards\task\TickTask;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class Reward extends Item {

    /**
     * Reward constructor.
     */
    public function __construct() {
        parent::__construct(Main::getInstance()->getConfig()->get("mining-reward-id"));
    }

    /**
     * @param Player $player
     * @param Vector3 $directionVector
     *
     * @return bool
     */
    public function onClickAir(Player $player, Vector3 $directionVector): bool {
        $itemEntity = $player->getWorld()->getFolderName()->dropItem($player->add(0, 3, 0), $this, $directionVector->multiply(0.5), 1000);
        $player->sendMessage(Main::getPrefix() . TextFormat::GREEN . "Opening reward...!");
        $player->getInventory()->setItemInHand($this->pop());
        Main::getInstance()->getScheduler()->scheduleRepeatingTask(new TickTask($player, $itemEntity, Main::getInstance()->getAnimationTickRate()), 5);
        return true;
    }
}
