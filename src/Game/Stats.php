<?php

namespace App\Game;

use App\Game\User\Currency;
use App\Game\User\SkillsOptions;
use App\Json\ParserLevel;
use App\Json\ParserLibsFortune;
use App\Libs\Random;
use App\Model\UserParams;
use App\Repository\FortuneRepository;
use App\Repository\UsersRepository;
use App\Response\DataResponse;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;

class Stats
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

    public function __construct(
        UserParams             $params,
        EntityManagerInterface $manager,
        UsersRepository        $usersRepository,
        DataResponse           $dataResponse,
        Currency               $currency,
        ParserLevel            $parserLevel,
        SkillsOptions          $skillsOptions,
    )
    {
        $this->params = $params;
        $this->manager = $manager;
        $this->usersRepository = $usersRepository;
        $this->dataResponse = $dataResponse;
        $this->currency = $currency;
        $this->parserLevel = $parserLevel;
        $this->skillsOptions = $skillsOptions;
    }

    #[ArrayShape(['response' => "array"])]
    public function raise(): array
    {
        $user = $this->usersRepository->findOneBy(['id' => $this->params->getUser()]);

        if (array_key_exists($this->params->getName(), $user->getSkills()->getSkills())) {
            if ($user->getCurrency('pills') >= self::UPDATE_COUNT) {
                $max_skills = $this->parserLevel->getLevel($user->getExperience())->getMaxSkill($this->params->getName());

                if ($user->getSkills()->getSkills($this->params->getName()) <= $max_skills) {
                    $user->getSkills()->setSkills($user->getSkills()->getSkills(), 1, $this->params->getName());
                    $user->setCurrency($this->currency->calculator($user->getCurrency(), Currency::MINUS, self::UPDATE_COUNT, self::MONEY_TYPE));

                    $this->manager->flush();

                    return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
                        'skills' => $this->skillsOptions->getSkills($user)
                    ]);
                }
                return $this->dataResponse->error(DataResponse::STATUS_ERROR, 'level max ' . $this->params->getName());
            }
            return $this->dataResponse->error(DataResponse::STATUS_ERROR, 'no money');
        }
        return $this->dataResponse->error(DataResponse::STATUS_ERROR, 'no params ' . $this->params->getName());
    }
}
