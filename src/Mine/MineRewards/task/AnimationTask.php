<?php

namespace Mine\MineRewards\task;

use Mine\MineRewards\MineR;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\entity\object\ItemEntity;
use pocketmine\item\Item;
use pocketmine\level\particle\HugeExplodeSeedParticle;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

class AnimationTask extends Task {

    /** @var Player */
    private $player;

    /** @var ItemEntity */
    private $item;

    /**
     * AnimationTask constructor.
     *
     * @param Player $player
     * @param ItemEntity $item
     */
    public function __construct(Player $player, ItemEntity $item) {
        $this->player= $player;
        $this->item = $item;
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick) {
        $amount = mt_rand(MineR::getInstance()->getCountMin(), MineR::getInstance()->getCountMax());
        $rewards = MineR::getInstance()->getRewards();
        for($i = 0; $i < $amount; $i++) {
            $reward = $rewards[array_rand($rewards)];
            if($reward instanceof Item) {
                $this->item->getLevel()->dropItem($this->item, $reward);
                continue;
            }
            $reward = explode(":", $reward);
            MineR::getInstance()->getServer()->dispatchCommand(new ConsoleCommandSender(),
                str_replace("{player}", $this->player->getName(), $reward[0]));
            if(isset($reward[1])) {
                $this->player->sendMessage(str_replace("&", TextFormat::ESCAPE, $reward[1]));
            }
        }
        $this->item->getLevel()->addParticle(new HugeExplodeSeedParticle($this->item));
        $this->item->getLevel()->broadcastLevelSoundEvent($this->item, LevelSoundEventPacket::SOUND_EXPLODE);
        $this->item->flagForDespawn();
    }
}