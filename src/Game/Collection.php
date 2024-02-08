<?php

namespace App\Game;

use App\Exception\ServerException;
use App\Game\Collection\Collections;
use App\Game\Job\Missions;
use App\Game\User\Currency;
use App\Game\User\Energies;
use App\Game\User\SkillsOptions;
use App\Json\ParserCollection;
use App\Json\ParserLevel;
use App\Json\ParserMission;
use App\Model\UserParams;
use App\Repository\UsersRepository;
use App\Response\DataResponse;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;

class Collection
{
    private UserParams $params;
    private UsersRepository $usersRepository;
    private ParserCollection $parserCollection;
    private Collections $collections;
    private DataResponse $dataResponse;
    private EntityManagerInterface $manager;
    private Currency $currency;

    public function __construct(
        UserParams             $params,
        EntityManagerInterface $manager,
        UsersRepository        $usersRepository,
        DataResponse           $dataResponse,
        Currency               $currency,
        ParserCollection       $parserCollection,
        Collections            $collections,
    )
    {
        $this->params = $params;
        $this->manager = $manager;
        $this->usersRepository = $usersRepository;
        $this->dataResponse = $dataResponse;
        $this->currency = $currency;
        $this->parserCollection = $parserCollection;
        $this->collections = $collections;
    }

    #[ArrayShape(['response' => "array"])]
    public function complete(): array
    {
        $library = $this->parserCollection->getCollection($this->params->getCollectionId());

        if ($user = $this->usersRepository->findOneBy(['id' => $this->params->getUser()])) {

            $collection = $this->collections->getCollectionId($user->getCollection(), $library->getId());

            $number = array_filter(array_map(function ($el) {
                return $el->getCount() > 0;
            }, $collection->getCollect()->getValues()), function ($a) {
                return $a === true;
            });
            if (count($number) === 5) {

                array_map(function ($el) {
                    return $el->setCount($el->getCount() - 1);
                }, $collection->getCollect()->getValues());

                $collection->setCount($collection->getCount() + 1)
                    ->setStamp(time());

                $user->getCollection()->setCollection($this->collections->update());

                $this->manager->flush();

                return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, ['drops_request' => $library->getDrops()]);

            } else throw new ServerException('Missing items.');
        } else throw new ServerException('Error select.');
    }
}
