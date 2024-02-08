<?php

namespace App\Json;

use App\Exception\ServerException;
use App\Json\Exception\LevelException;
use App\Json\Model\Level;
use App\Json\Model\Mission;

class ParserMission extends LoaderJSON
{
    public function __construct()
    {
        $this->setFile('Missions')
        ->library();
    }

    public function getMission(int $mission = 0): Mission
    {
        foreach ($this->getJson(['mission', 'item']) as  $item) {
            if($item['id'] == $mission){
                return (new Mission())
                    ->setId($item['id'])
                    ->setCategoryId($item['category_id'])
                    ->setEnergy($item['energy'])
                    ->setMaxCount($item['max_count']);
            }
        }
        throw new ServerException('Library item id [' . $mission . '] of class [item] not found');
    }
}
