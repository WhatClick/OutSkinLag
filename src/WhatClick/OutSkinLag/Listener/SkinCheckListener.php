<?php

namespace WhatClick\OutSkinLag\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;

use WhatClick\OutSkinLag\Main;

class SkinCheckListener implements Listener {

    public function __construct(private Main $plugin) {}

    public function onPlayerCreation(PlayerCreationEvent $event): void {
      
        $skinData = $event->getSkin();
        $geometryData = $skinData->getGeometryData();

        if ($geometryData === "") {
            return;
        }

        $decoded = json_decode($geometryData, true);
        if (!isset($decoded["minecraft:geometry"])) {
            return;
        }

        $totalCubes = 0;
      
        foreach ($decoded["minecraft:geometry"] as $model) {
            foreach ($model["bones"] ?? [] as $bone) {
                foreach ($bone["cubes"] ?? [] as $cube) {
                    $totalCubes++;
                }
            }
        }

        $max = $this->plugin->getMaxCubes();
      
        if ($totalCubes > $max) {
          
            $playerInfo = $event->getUsername();
            $message = $this->plugin->getKickMessage($totalCubes);
            $event->setPlayerCreationCancelled(true, $message);

            $this->plugin->getLogger()->warning("El jugador '$playerInfo' fue bloqueado por usar una skin con $totalCubes cubos (límite: $max).");
        }
    }
}
