<?php

namespace App\Game;

use App\Exception\ServerException;
use App\Exception\SkillsException;
use App\Game\User\Currency;
use App\Game\User\Energies;
use App\Game\User\SkillsOptions;
use App\Json\ParserLevel;
use App\Json\ParserLibsFortune;
use App\Json\ParserMission;
use App\Libs\Random;
use App\Game\Job\Missions;
use App\Model\UserParams;
use App\Repository\FortuneRepository;
use App\Repository\UsersRepository;
use App\Response\DataResponse;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;

class Job
{
    const MONEY_TYPE = 'pills';
    const UPDATE_COUNT = 66;

    private UserParams $params;
    private EntityManagerInterface $manager;
    private UsersRepository $usersRepository;
    private DataResponse $dataResponse;
    private Currency $currency;
    private ParserLevel $parserLevel;
    private SkillsOptions $skillsOptions;
    private Missions $mission;
    private ParserMission $parserMission;
    private Energies $energies;

    public function __construct(
        UserParams             $params,
        EntityManagerInterface $manager,
        UsersRepository        $usersRepository,
        DataResponse           $dataResponse,
        Currency               $currency,
        ParserLevel            $parserLevel,
        SkillsOptions          $skillsOptions,
        Missions               $mission,
        ParserMission          $parserMission,
        Energies               $energies
    )
    {
        $this->params = $params;
        $this->manager = $manager;
        $this->usersRepository = $usersRepository;
        $this->dataResponse = $dataResponse;
        $this->currency = $currency;
        $this->parserLevel = $parserLevel;
        $this->skillsOptions = $skillsOptions;
        $this->mission = $mission;
        $this->parserMission = $parserMission;
        $this->energies = $energies;
    }

    #[ArrayShape(['response' => "array"])]
    public function do(): array
    {
        $user = $this->usersRepository->findOneBy(['id' => $this->params->getUser()]);

        $library = $this->parserMission->getMission($this->params->getJobId());

        if ($this->energies->getEnergy($user, 'work') >= $library->getEnergy()) {
            $this->mission->getMissions($user->getJob());

            $job = $this->mission->getMissionId($library->getCategoryId());

            if ($this->mission->getJobId($job->getJob(), $library->getId())->getCount() < $library->getMaxCount()) {

                $this->mission
                    ->getJobId($job->getJob(), $library->getId())
                    ->setCount(0, 1);

                $job->setStamp(time());

                $this->energies
                    ->setEnergy($user, 'work', $library->getEnergy());


                if ($this->mission->getJobId($job->getJob(), $library->getId())->getCount() >= 5) {
                    $job->setCount(1, 1);

                    foreach ($job->getJob() as $item) {
                        $item->setCount(0);
                    }
                }

                $user->getJob()
                    ->setMissions($this->mission->updateMission());

                $this->manager->flush();

                return [$user->getJob()->getMissions(), $this->energies->getEnergy($user, 'work')];

            }
            throw new ServerException('Max job count reached');
        }
        throw new ServerException('Have no energy');
    }
}
