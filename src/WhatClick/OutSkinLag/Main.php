<?php

declare(strict_types=1);

namespace WhatClick\OutSkinLag;

use pocketmine\plugin\PluginBase;
use WhatClick\OutSkinLag\listener\SkinCheckListener;

final class Main extends PluginBase
{
    private const DEFAULT_MAX_CUBES = 1000;
    private const DEFAULT_KICK_MESSAGE = "§cTu skin tiene demasiados cubos.";
    
    private int $maxCubes;
    private string $kickMessageTemplate;

    protected function onEnable(): void
    {
        $this->initializeConfig();
        $this->loadConfiguration();
        $this->registerEventListeners();
    }

    private function initializeConfig(): void
    {
        if (!$this->getResource("config.yml")) {
            $this->getLogger()->warning("config.yml resource not found, using default values");
            return;
        }
        
        $this->saveDefaultConfig();
    }

    private function loadConfiguration(): void
    {
        $this->reloadConfig();
        
        $this->maxCubes = (int) $this->getConfig()->get("max-cubes", self::DEFAULT_MAX_CUBES);
        $this->kickMessageTemplate = (string) $this->getConfig()->get("kick-message", self::DEFAULT_KICK_MESSAGE);
        
        if ($this->maxCubes <= 0) {
            $this->getLogger()->warning("max-cubes debe ser mayor que 0, usando valor por defecto: " . self::DEFAULT_MAX_CUBES);
            $this->maxCubes = self::DEFAULT_MAX_CUBES;
        }
    }

    private function registerEventListeners(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(
            new SkinCheckListener($this), 
            $this
        );
    }

    public function getMaxCubes(): int
    {
        return $this->maxCubes;
    }

    /**
     * @param int 
     * @return string 
     */
    public function getKickMessage(int $cubes): string
    {
        return str_replace(
            ["{cubes}", "{max}"],
            [(string) $cubes, (string) $this->maxCubes],
            $this->kickMessageTemplate
        );
    }

    public function reloadPluginConfig(): void
    {
        $this->loadConfiguration();
        $this->getLogger()->info("Configuración recargada exitosamente");
    }
}