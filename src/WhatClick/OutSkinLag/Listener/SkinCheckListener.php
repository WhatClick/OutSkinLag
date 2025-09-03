<?php

declare(strict_types=1);

namespace WhatClick\OutSkinLag\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\utils\SingletonTrait;
use WhatClick\OutSkinLag\Main;

final class SkinCheckListener implements Listener
{
    use SingletonTrait;

    public function onPlayerCreation(PlayerCreationEvent $event): void
    {
        $geometryData = $event->getSkin()->getGeometryData();
        if (empty($geometryData)) return;

        $decoded = json_decode($geometryData, true);
        if (!isset($decoded["minecraft:geometry"]) || !is_array($decoded["minecraft:geometry"])) return;

        $totalCubes = array_sum(array_map(
            fn($model) => array_sum(array_map(
                fn($bone) => count($bone["cubes"] ?? []), 
                $model["bones"] ?? []
            )), 
            $decoded["minecraft:geometry"]
        ));

        $main = Main::getInstance();
        if ($totalCubes > $main->getMaxCubes()) {
            $event->setPlayerCreationCancelled(true, $main->getKickMessage($totalCubes));
            $main->getLogger()->warning("Jugador '{$event->getUsername()}' bloqueado: {$totalCubes} cubos (límite: {$main->getMaxCubes()})");
        }
    }
}