<?php

namespace App\Json\Model;

class Collection
{
    private int $id = 0;
    private array $collect;
    private array $drops;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function setId(int $id): Collection
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function getCollect(): array
    {
        return $this->collect;
    }

    /**
     * @param string $collect
     * @return Collection
     */
    public function setCollect(string $collect): Collection
    {
        $this->collect = explode(',', $collect);
        return $this;
    }

    /**
     * @return array
     */
    public function getDrops(): array
    {
        return $this->drops;
    }

    /**
     * @param array $drops
     * @return Collection
     */
    public function setDrops(array $drops): Collection
    {
        $this->drops = $drops;
        return $this;
    }
}
