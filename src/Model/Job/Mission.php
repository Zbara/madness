<?php

namespace App\Model\Job;


use App\Model\Job\Job as JobModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;

class Mission
{
    private int $id = 0;
    private int $count = 0;
    private int $stamp = 0;
    private int $created = 0;
    private  $job;

    #[Pure]
    public function __construct()
    {
        $this->job = new ArrayCollection();
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
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @param bool $update
     * @return $this
     */
    public function setCount(int $count, bool $update = false): Mission
    {
        if($update){
            $this->count = $this->count + $count;
        } else $this->count = $count;

        return $this;
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
     * @return Mission
     */
    public function setStamp(int $stamp): Mission
    {
        $this->stamp = $stamp;
        return $this;
    }

    /**
     * @return int
     */
    public function getCreated(): int
    {
        return $this->created;
    }

    /**
     * @param int $created
     * @return Mission
     */
    public function setCreated(int $created): Mission
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return Collection<int, JobModel>
     */
    public function getJob(): Collection
    {
        return $this->job;
    }

    public function addJob(Job $job): self
    {
        if (!$this->job->contains($job)) {
            $this->job[] = $job;

        }
        return $this;
    }
}
