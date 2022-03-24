<?php

namespace App\Game\Pve;

use App\Entity\Pve;
use App\Entity\PveUsers;
use App\Entity\Users;
use JetBrains\PhpStorm\ArrayShape;

class PveData
{
    const DAMAGE_NO = 1;
    const WIN_NO = 2;
    const WIN_YES = 3;

    private array $drops_request;

    #[ArrayShape(['pve' => "array|array[]|int[]"])]
    public function getData(Pve $pve, Users $users, int $param = 0): array
    {
        $data = [
            'boss' => [
                'init_health' => 1333,
                'pve_id' => $pve->getId(),
                'id' => $pve->getBossId(),
                'health' => $pve->getHealth(),
                'start' => $pve->getBattleStart(),
                'finish' => $pve->getBattleFinish(),
                'dev' => $pve->getBattleFinish() - time()
            ],
            'user' => [
                'init_health' => 0,
                'health' => $users->getPveUsers()->current()->getHealth()
            ]
        ];
        if ($param === self::DAMAGE_NO) {
            $data = array_merge($data, ['hit' => 0]);
        } elseif ($param === self::WIN_NO) {
            $data = array_merge($data, ['win' => 0]);
        }

        foreach ($pve->getPveUsers() as $item) {
            $data['users'][] = [
                'id' => $item->getUser()->getId(),
                'name' => $item->getUser()->getName(),
                'health' => $item->getHealth(),
                'damage' => $item->getDamage(),
                'stamp' => $item->getVisit()
            ];
        }
        $result = [
            'pve' => $data
        ];
        if ($param === self::WIN_YES) {
            $result = array_merge($result, ['drops_request' => $this->drops_request]);
        }
        return $result;
    }


    public function getDrops(): array
    {
        return $this->drops_request;
    }

    public function setDrops(array $drops)
    {
        $this->drops_request = $drops;
    }
}
