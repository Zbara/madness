<?php

namespace App\Game\Job;

use App\Entity\Job;
use App\Exception\ServerException;
use App\Model\Job\Mission;
use App\Model\Job\Job as JobModel;
use ArrayObject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


class Missions
{
    private ArrayCollection $missions;
    private Serializer $serializer;

    #[Pure]
    public function __construct(SerializerInterface $serializer)
    {
        $this->missions = new ArrayCollection();
        $this->serializer = $serializer;
    }

    /**
     * @param Job $job
     * @return Collection<int, Mission>
     */
    public function getMissions(Job $job): Collection
    {
        foreach ($job->getMissions() as $mission) {
            $missions = new Mission();
            $missions->setId($mission['id'])
                ->setStamp($mission['stamp'])
                ->setCount($mission['count'])
                ->setCreated($mission['created']);

            foreach ($mission['job'] as $item) {
                $job = new JobModel();
                $job->setId($item['id'])
                    ->setCount($item['count']);
                $missions->addJob($job);
            }
            $this->missions[] = $missions;
        }
        return $this->missions;
    }

    /**
     * @param int $id
     * @return Mission
     */
    public function getMissionId(int $id = 0): Mission
    {
        return $this->missions->filter(
            function ($job) use ($id) {
                return ($id === $job->getId());
            }
        )->current();
    }

    /**
     * @param ArrayCollection $collection
     * @param int $jobId
     * @return JobModel
     */
    public function getJobId(ArrayCollection $collection, int $jobId = 0): JobModel
    {
        return $collection->filter(
            function ($job) use ($jobId) {
                return ($jobId === $job->getId());
            }
        )->current();
    }

    /**
     * @param int $id
     * @param int $jobId
     * @param int $status
     * @return array
     */
    public function updateMission(int $id = 0, int $jobId = 0, int $status = 0): array
    {
        try {
            return $this->serializer->normalize($this->missions);
        } catch (ExceptionInterface $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
