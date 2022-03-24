<?php

namespace App\Game;

use App\Entity\Energy;
use App\Entity\Session;
use App\Entity\Skills;
use App\Game\User\Currency;
use App\Game\User\Energies;
use App\Entity\Settings;
use App\Entity\Users;
use App\Game\User\SessionOption;
use App\Game\User\Setting;
use App\Game\User\SkillsOptions;
use App\Json\ParserLevel;
use App\Libs\GameLibs;
use App\Model\UserParams;
use App\Repository\UsersRepository;
use App\Response\DataResponse;
use Doctrine\ORM\EntityManagerInterface;

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
        ParserLevel          $parserLevel,
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
            'session' => $this->session->setSession($user)
        ];
        $this->manager->flush();

        return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, $data);
    }

    private function user(): ?\App\Entity\Users
    {
        $user = $this->usersRepository->findOneBy(['platformId' => $this->params->getPlatformId()]);

        if (null === $user) {
            $sex = $this->params->getSex() == 'unknown' ? 'unknown' : ($this->params->getSex() == 'female' ? 'female' : 'male');

            $settings = new Settings();
            $settings->setMusic(true)
                ->setSound(true)
                ->setMusicVolume(50)
                ->setSoundVolume(50);

            $user = new Users();
            $user->setPlatformId($this->params->getPlatformId())
                ->setCreatedAt(time())
                ->setSex($sex)
                ->setRoom(self::ROOM)
                ->setExperience(0)
                ->setSettings($settings)
                ->setBattleRank(self::START_BATTLE_RANK)
                ->setCurrency($this->currency->startCurrency());

            $skills = new Skills();
            $skills->setSkills((self::SKILLS))
                ->setStore(self::SKILLS_STORE);

            $user->setSkills($skills);

            foreach (self::ENERGY as $i => $value) {
                $energy = new Energy();
                $energy->setCategory($i)
                    ->setCurrent($value);
                $user->addEnergy($energy);
            }
            $session = new Session();
            $user->setSession($session);
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
