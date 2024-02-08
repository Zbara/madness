<?php

namespace App\Model\Collections;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;

class CollectionModel
{

    private int $id = 0;
    private int $stamp = 0;
    private int $count = 0;
    private $collect;

    #[Pure]
    public function __construct()
    {
        $this->collect = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getStamp(): int
    {
        return $this->stamp;
    }

    /**
     * @param int $stamp
     * @return CollectionModel
     */
    public function setStamp(int $stamp): CollectionModel
    {
        $this->stamp = $stamp;
        return $this;
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return CollectionModel
     */
    public function setId(int $id): CollectionModel
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
     * @return CollectionModel
     */
    public function setCount(int $count): CollectionModel
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return Collection<int, CollectionElement>
     */
    public function getCollect(): Collection
    {
        return $this->collect;
    }


    public function addCollect(CollectionElement $element): self
    {
        if (!$this->collect->contains($element)) {
            $this->collect[] = $element;

        }
        return $this;
    }
}
