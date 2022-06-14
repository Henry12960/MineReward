<?php

namespace Mine\MineRewards\item;

use Mine\MineRewards\MineR;
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
        parent::__construct(MineR::getInstance()->getConfig()->get("mining-reward-id"));
    }

    /**
     * @param Player $player
     * @param Vector3 $directionVector
     *
     * @return bool
     */
    public function onClickAir(Player $player, Vector3 $directionVector): bool {
        $itemEntity = $player->getLevel()->dropItem($player->add(0, 3, 0), $this, $directionVector->multiply(0.5), 1000);
        $player->sendMessage(MineR::getPrefix() . TextFormat::GREEN . "Opening reward...!");
        $player->getInventory()->setItemInHand($this->pop());
        MineR::getInstance()->getScheduler()->scheduleRepeatingTask(new TickTask($player, $itemEntity, MineR::getInstance()->getAnimationTickRate()), 5);
        return true;
    }
}