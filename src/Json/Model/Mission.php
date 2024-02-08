<?php

namespace App\Json\Model;

class Mission
{
    private int $id = 0;
    private int $category_id = 0;
    private int $energy = 0;
    private int $max_count = 0;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Mission
     */
    public function setId(int $id): Mission
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     * @return Mission
     */
    public function setCategoryId(int $category_id): Mission
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getEnergy(): int
    {
        return $this->energy;
    }

    /**
     * @param int $energy
     * @return Mission
     */
    public function setEnergy(int $energy): Mission
    {
        $this->energy = $energy;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxCount(): int
    {
        return $this->max_count;
    }

    /**
     * @param int $max_count
     * @return Mission
     */
    public function setMaxCount(int $max_count): Mission
    {
        $this->max_count = $max_count;
        return $this;
    }
}
