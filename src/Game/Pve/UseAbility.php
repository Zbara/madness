<?php

namespace App\Game\Pve;

use App\Entity\Pve;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class UseAbility
{
    private EntityManagerInterface $manager;
    private PveData $pveData;

    public function __construct(EntityManagerInterface $manager, PveData $pveData)
    {
        $this->manager = $manager;
        $this->pveData = $pveData;
    }

    public function examination(Pve $pve, Users $user): array
    {
        if ($pve->getHealth() <= 0) {
       //     $pve->getUser()->setExperience($pve->getUser()->getExperience() + 20)
     //           ->setBattle(null);


            $this->pveData->setDrops([
                [
                    'type' => 'xp',
                    'count' => 28
                ],
                [
                    'type' => 'currency1',
                    'count' => 300
                ],
                [
                    'type' => 'inventory',
                    'count' => 1,
                    'id' => 1
                ],
                [
                    'type' => 'room_opened',
                    'count' => 1,
                    'id' => 1
                ]
            ]);


            $this->manager->flush();

            return $this->pveData->getData($pve, $user, PveData::WIN_YES);

        } elseif ($pve->getBattleFinish() <= time()) {
            $pve->getUser()->setBattle(null);
            $this->manager->flush();

            return $this->pveData->getData($pve, $user, PveData::WIN_NO);
        }
        return ['pve_not_exist'];
    }
}
