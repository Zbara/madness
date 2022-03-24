<?php

namespace App\Json;

use App\Json\Model\Level;

class ParserLevel extends LoaderJSON
{
    public function __construct()
    {
        $this->setFile('Level')
        ->library();
    }

    public function getLevel(int $xp = 0, bool $information = false): Level|int
    {
        $sum = 0;
        foreach ($this->getJson('item') as  $exp) {
            $sum += (int) $exp['xp'];

            if ($sum >= $xp) {
                $level = new Level();

                $level->setId($exp['id'])
                    ->setMaxEnergy($exp['max_energy'])
                    ->setXp($exp['xp'])
                    ->setMaxSkill(explode(',', $exp['max_skill']));

                return $level;
            }
        }
        return -1;
    }
}
