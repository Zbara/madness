<?php

namespace App\Json;

use App\Exception\ServerException;
use App\Json\Model\Collection;

class ParserCollection extends LoaderJSON
{
    public function __construct()
    {
        $this->setFile('Collection')
        ->library();
    }

    public function getCollection(int $collection = 0): Collection
    {
        foreach ($this->getJson(['collection', 'item']) as  $item) {
            if($item['id'] == $collection){
                return (new Collection())
                    ->setId($item['id'])
                    ->setDrops($item['drops'])
                    ->setCollect($item['collect']);
            }
        }
        throw new ServerException('Library item id [' . $collection . '] of class [item] not found');
    }
}
