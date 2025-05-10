<?php

namespace WhatClick\OutSkinLag;

use pocketmine\plugin\PluginBase;

use WhatClick\OutSkinLag\listener\SkinCheckListener;

class Main extends PluginBase {

    protected function onEnable(): void {
      
        $this->saveResource("config.yml");
        $this->getServer()->getPluginManager()->registerEvents(new SkinCheckListener($this), $this);
    }

    public function getMaxCubes(): int {
      
        return $this->getConfig()->get("max-cubes", 1000);
    }

    public function getKickMessage(int $cubes): string {
      
        $template = $this->getConfig()->get("kick-message", "§cTu skin tiene demasiados cubos.");
        
      return str_replace(
            ["{cubes}", "{max}"],
            [$cubes, $this->getMaxCubes()],
            $template
        );
    }
}
