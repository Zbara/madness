<?php

namespace App\Model\Job;

class Job
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
     * @return Job
     */
    public function setId(int $id): Job
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
     * @return Job
     */
    public function setCount(int $count, bool $update = false): Job
    {
        if($update){
            $this->count = $this->count + 1;
        } else $this->count = $count;

        return $this;
    }

}
