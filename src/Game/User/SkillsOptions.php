<?php

namespace App\Game\User;

use App\Entity\Users;

class SkillsOptions
{

    public function getSkills(Users $getSkills): array
    {
        $skills = [];

        foreach ($getSkills->getSkills()->getSkills() as $i => $skill) {
            $skills[] =
                [
                    'name' => $i,
                    'base' => $skill,
                    'store_male' => $getSkills->getSkills()->getStore($i, 'male'),
                    'store_female' => $getSkills->getSkills()->getStore($i, 'female'),
                    'temp' => 0
                ];
        }
        return $skills;
    }
}
