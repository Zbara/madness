<?php

namespace App\Game;

use App\Entity\Energy;
use App\Entity\Job;
use App\Entity\Session;
use App\Entity\Skills;
use App\Game\Collection\Collections;
use App\Game\Job\Missions;
use App\Game\User\Currency;
use App\Game\User\Energies;
use App\Entity\Settings;
use App\Entity\Users;
use App\Game\User\SessionOption;
use App\Game\User\Setting;
use App\Game\User\SkillsOptions;
use App\Json\ParserLevel;
use App\Model\UserParams;
use App\Repository\UsersRepository;
use App\Response\DataResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Collection as EntityCollection;

class Authorize
{
    const SKILLS = [
        'skill1' => 2,
        'skill2' => 1,
        'skill3' => 0,
        'skill4' => 0
    ];
    const SKILLS_STORE = [
        'male' => [
            'skill1' => 0,
            'skill2' => 0,
            'skill3' => 0,
            'skill4' => 0
        ],
        'female' => [
            'skill1' => 0,
            'skill2' => 0,
            'skill3' => 0,
            'skill4' => 0
        ]
    ];

    const ROOM = 1;

    const ENERGY = [
        'work' => 80,
        'pvp' => 0,
        'pve' => 5
    ];
    const START_BATTLE_RANK = 1500;


    const START_MISSION = [
        [
            'id' => 1,
            'job' => [
                [
                    'id' => 1,
                    'count' => 0
                ],
                [
                    'id' => 2,
                    'count' => 5
                ],
                [
                    'id' => 3,
                    'count' => 0
                ],
                [
                    'id' => 4,
                    'count' => 
                ],
                [
                    'id' => 5,
                    'count' => 0
                ]
            ],
            'count' => 0,
            'created' => 0,
            'stamp' => 0
        ]
    ];

    private UserParams $params;
    private EntityManagerInterface $manager;
    private UsersRepository $usersRepository;
    private DataResponse $dataResponse;
    private Energies $energy;
    private Currency $currency;
    private Setting $setting;
    private SkillsOptions $skillsOptions;
    private SessionOption $session;
    private ParserLevel $parserLevel;
    private Missions $missions;
    private Collections $collections;

    public function __construct(
        UserParams             $params,
        EntityManagerInterface $manager,
        UsersRepository        $usersRepository,
        DataResponse           $dataResponse,
        Energies               $energy,
        Currency               $currency,
        Setting                $setting,
        SkillsOptions          $skillsOptions,
        SessionOption          $sessionOption,
        ParserLevel            $parserLevel,
        Missions               $missions,
        Collections $collections
    )
    {
        $this->params = $params;
        $this->manager = $manager;
        $this->usersRepository = $usersRepository;
        $this->dataResponse = $dataResponse;
        $this->energy = $energy;
        $this->currency = $currency;
        $this->setting = $setting;
        $this->skillsOptions = $skillsOptions;
        $this->session = $sessionOption;
        $this->parserLevel = $parserLevel;
        $this->missions = $missions;
        $this->collections = $collections;
    }

    public function hepler(): array
    {
        $user = $this->user();

        $data = [
            'uid' => $user->getId(),
            'platform_id' => $user->getPlatformId(),
            'currency' => $this->currency->getCurrency($user->getCurrency()),
            'level' => $this->parserLevel->getLevel($user->getExperience())->getId(),
            'xp' => $user->getExperience(),
            'settings' => $this->setting->getSettings($user->getSettings()),
            'platform_name' => $user->getRealName(),
            'platform_avatar' => $user->getAvatar(),
            'creation' => $user->getCreatedAt(),
            'skills' => $this->skillsOptions->getSkills($user),
            'battle_rank' => $user->getBattleRank(),
            'energy' => $this->energy->getEnergy($user),
            'name' => $user->getName(),
            'sex' => $user->getSex(),
            'friend_count' => count($this->params->getAppFriends()),
            'invite_count' => 0,
            'top' => [
                'xp' => [
                    'total' => (int)$user->getExperience(),
                ],
                'battle_rank' => [
                    'total' => (int)$user->getBattleRank()
                ]
            ],

            'missions' => $this->missions->getMissions($user->getJob()),
            'collection' => $this->collections->getCollection($user->getCollection()),
            'session' => $this->session->setSession($user)
        ];

        $this->manager->flush();

        return $this->dataResponse->success(
            DataResponse::STATUS_SUCCESS, $data
        );
    }

    private function user(): ?\App\Entity\Users
    {
        $user = $this->usersRepository->findOneBy(['platformId' => $this->params->getPlatformId()]);

        if (null === $user) {
            $user = new Users();
            $user->setPlatformId($this->params->getPlatformId())
                ->setCreatedAt(time())
                ->setSex($this->params->getSex() == 'unknown' ? 'unknown' : ($this->params->getSex() == 'female' ? 'female' : 'male'))
                ->setRoom(self::ROOM)
                ->setExperience(0)
                ->setBattleRank(self::START_BATTLE_RANK)
                ->setCurrency($this->currency->startCurrency())
                ->setCollection((new EntityCollection())
                    ->setCreatedAt(time())
                    ->setCollection(Collections::COLLECTION)
                )
                
                ->setJob((new Job())
                    ->setMissions(self::START_MISSION)
                )
                ->setSession((new Session()))
                ->setSkills((new Skills())
                    ->setSkills(self::SKILLS)
                    ->setStore(self::SKILLS_STORE)
                )->setSettings((new Settings())
                    ->setMusic(true)
                    ->setSound(true)
                    ->setMusicVolume(50)
                    ->setSoundVolume(50)
                );

            foreach (self::ENERGY as $i => $value) {
                $energy = new Energy();
                $energy->setCategory($i)
                    ->setCurrent($value);
                $user->addEnergy($energy);
            }

        }
        $user->setRealName($this->params->getFirstName() . ' ' . $this->params->getLastName())
            ->setAvatar($this->params->getAvatar())
            ->setName($this->params->getFirstName())
            ->setLastTime(time());

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}
