<?php

namespace Mine\MineRewards;

use Mine\MineRewards\item\Reward;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\level\particle\HugeExplodeSeedParticle;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\utils\TextFormat;

class EventListener implements Listener {

    /** @var Main */
    private $plugin;

    /**
     * EventListener constructor.
     *
     * @param Main $plugin
     */
    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
    }

    /**
     * @param BlockBreakEvent $event
     */
    public function onBlockBreak(BlockBreakEvent $event) {
        if($event->isCancelled()) {
            return;
        }
        if(mt_rand(1, $this->plugin->getChance()) === mt_rand(1, $this->plugin->getChance())) {
            $player = $event->getPlayer();
            $level = $player->getWorld()->getFolderName();
            $item = new Reward();
            $lore = [];
            $lore[] = TextFormat::RESET . TextFormat::YELLOW . "Reward Min: " . Main::getInstance()->getCountMin();
            $lore[] = TextFormat::RESET . TextFormat::YELLOW . "Reward Max: " . Main::getInstance()->getCountMax();
            $lore[] = "";
            $lore[] = TextFormat::RESET . TextFormat::GRAY . "Click to open reward.";
            $item->setLore($lore);
            $item->setCustomName(TextFormat::RESET . TextFormat::GOLD . TextFormat::BOLD . "Reward");
            $level->dropItem($player, $item);
            $level->addParticle(new HugeExplodeSeedParticle($player));
            $level->addSound(new BlazeShootSound($player));
            $titles = Main::getTitles();
            $player->setTitle(TextFormat::BOLD . TextFormat::AQUA . $titles[array_rand($titles)],
                TextFormat::GRAY . "You have found a reward from mining!");
        }
    }
}
