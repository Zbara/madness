<?php

namespace App\Game\User;

use App\Entity\Users;

class SkillsOptions
{

    public function getSkills(Users $getSkills): array
    {
        $skills = [];

        foreach ($getSkills->getSkills() as $skill) {
            if ($getSkills->getSex() == $skill->getSex()) {
                foreach ($skill->getSkills() as $i => $item) {
                    $skills[] =
                        [
                            'name' => $i,
                            'base' => $item,
                            'store' => $skill->getStore()[$i],
                            'temp' => 0
                        ];
                }
            }
        }
        return $skills;
    }
}
