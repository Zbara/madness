<?php

namespace App\Game\Collection;

use App\Entity\Collection as EntityCollection;
use App\Exception\ServerException;
use App\Model\Collections\CollectionElement;
use App\Model\Collections\CollectionModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class Collections
{
    const COLLECTION = [
        [
            'id' => 1,
            'stamp' => 0,
            'count' => 0,
            'collect' => [
                [
                    'id' => 1,
                    'count' => 0
                ],
                [
                    'id' => 1,
                    'count' => 0
                ],
                [
                    'id' => 1,
                    'count' => 0
                ],
                [
                    'id' => 1,
                    'count' => 0
                ],
                [
                    'id' => 1,
                    'count' => 0
                ]
            ]
        ]
    ];

    private ArrayCollection $collection;
    private Serializer $serializer;

    #[Pure]
    public function __construct(SerializerInterface $serializer)
    {
        $this->collection = new ArrayCollection();
        $this->serializer = $serializer;
    }

    /**
     * @param EntityCollection $collection
     * @return Collection<int, CollectionModel>
     */
    public function getCollection(EntityCollection $collection): Collection
    {
        foreach ($collection->getCollection() as $item) {
            $collect = new CollectionModel();
            $collect->setId($item['id'])
                ->setStamp($item['stamp'])
                ->setCount($item['count']);

            foreach ($item['collect'] as $value) {
                $collect->addCollect((new CollectionElement())->setCount($value['count'])->setId($value['id']));
            }
            $this->collection->add($collect);
        }
        return $this->collection;
    }

    /**
     * @param EntityCollection $collection
     * @param int $id
     * @return CollectionModel
     */
    public function getCollectionId( EntityCollection $collection, int $id): CollectionModel
    {
        $this->getCollection($collection);

        return $this->collection->filter(
            function ($job) use ($id) {
                return ($id === $job->getId());
            }
        )->current();
    }


    /**
     * @return array
     */
    public function update(): array
    {
        try {
            return $this->serializer->normalize($this->collection);
        } catch (ExceptionInterface $e) {
            throw new ServerException($e->getMessage());
        }
    }
}
