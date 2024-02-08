<?php

namespace App\Model\Collections;

class CollectionElement
{

    private int $id = 0;
    private int $count = 0;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CollectionElement
     */
    public function setId(int $id): CollectionElement
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return CollectionElement
     */
    public function setCount(int $count): CollectionElement
    {

        $this->count = $count;

        return $this;
    }
}
