<?php

namespace App\Json\Model;

class Level
{
    private int $id = 0;
    private int $xp = 0;
    private int $max_energy = 0;
    private array $max_skill = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Level
     */
    public function setId(int $id): Level
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getXp(): int
    {
        return $this->xp;
    }

    /**
     * @param int $xp
     * @return Level
     */
    public function setXp(int $xp): Level
    {
        $this->xp = $xp;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxEnergy(): int
    {
        return $this->max_energy;
    }

    /**
     * @param int $max_energy
     * @return Level
     */
    public function setMaxEnergy(int $max_energy): Level
    {
        $this->max_energy = $max_energy;
        return $this;
    }

    /**
     * @return array
     */
    public function getMaxSkill(string $type = null): array|string
    {
        if($type) {
            return $this->max_skill[$type];
        } else  return $this->max_skill;
    }

    /**
     * @param array $max_skill
     * @return Level
     */
    public function setMaxSkill(array $max_skill): Level
    {
        foreach ($max_skill as $key => $item){
            $this->max_skill['skill' . $key + 1] = $item;
        }
        return $this;
    }
}
