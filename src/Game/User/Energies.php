<?php

namespace App\Game\User;

use App\Entity\Energy;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class Energies
{
    const RESTORE = [
        'work' => [
            'time' => 90,
            'max' => 80
        ],
        'pvp' => [
            'time' => 300,
            'max' => 5
        ],
        'pve' => [
            'time' => 300,
            'max' => 5
        ],
    ];

    private EntityManagerInterface $manager;

    public function __construct(
        EntityManagerInterface $manager
    )
    {
        $this->manager = $manager;
    }

    public function setEnergy(Users $user, string $type = null, int $current = 0)
    {
        foreach ($user->getEnergies() as $key => $value) {
            if ($type === $value->getCategory()) {
                $value->setCurrent($value->getCurrent() - $current)
                    ->setStamp(microtime(true))
                    ->setUsed($value->getUsed() + $current);
            }
        }
    }

    public function getEnergy(Users $user, string $type = null): int|array
    {
        $energies = [];

        foreach ($user->getEnergies() as $key => $value) {
            $min = $value->getCurrent();
            $max = self::RESTORE[$value->getCategory()]['max'];
            $timeLimit = self::RESTORE[$value->getCategory()]['time'];
            $timeStatic = $value->getStamp();
            $timeDynamic = microtime(true);
            $now = round(($timeDynamic - $timeStatic) / $timeLimit, 2) + $min;
            $now = ($now > $max) ? $max : $now;

            if (round($now) > $value->getCurrent()) {
                $value->setCurrent(round($now))
                    ->setStamp($timeDynamic);
            }
            $energies[$value->getCategory()] = [
                'current' => $value->getCurrent(),
                'max' => [
                    'base' => self::RESTORE[$value->getCategory()]['max'],
                    'temp' => 0
                ],
                'stamp' => $value->getStamp(),
                'used' => $value->getUsed()
            ];

            if ($type == $value->getCategory()) {
                return $value->getCurrent();
            }
        }
        return $energies;
    }
}
