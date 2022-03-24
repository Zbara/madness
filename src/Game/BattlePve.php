<?php

namespace App\Game;

use App\Entity\Pve;
use App\Entity\PveUsers;
use App\Game\Pve\PveData;
use App\Game\Pve\UseAbility;
use App\Game\User\Currency;
use App\Game\User\Energies;
use App\Game\User\SessionOption;
use App\Game\User\Setting;
use App\Game\User\SkillsOptions;
use App\Libs\GameLibs;
use App\Model\UserParams;
use App\Repository\PveUsersRepository;
use App\Repository\UsersRepository;
use App\Response\DataResponse;
use Doctrine\ORM\EntityManagerInterface;

class BattlePve
{
    private UserParams $params;
    private EntityManagerInterface $manager;
    private UsersRepository $usersRepository;
    private DataResponse $dataResponse;
    private SkillsOptions $skillsOptions;
    private PveUsersRepository $pveUsersRepository;
    private UseAbility $useAbility;
    private PveData $pveData;

    public function __construct(
        UserParams             $params,
        EntityManagerInterface $manager,
        UsersRepository        $usersRepository,
        DataResponse           $dataResponse,
        SkillsOptions          $skillsOptions,
        PveUsersRepository     $pveUsersRepository,
        UseAbility             $useAbility,
        PveData                $pveData
    )
    {
        $this->params = $params;
        $this->manager = $manager;
        $this->usersRepository = $usersRepository;
        $this->dataResponse = $dataResponse;
        $this->pveUsersRepository = $pveUsersRepository;
        $this->useAbility = $useAbility;
        $this->pveData = $pveData;
    }

    public function hepler()
    {
        $user = $this->usersRepository->findOneBy(['id' => $this->params->getUser()]);

        $health = 1333;
        $level = 7;

        if (GameLibs::getLevel($user->getExperience()) >= $level) {

            if ($user->getBattle() === null) {
                $pve = new Pve();
                $pve->setUser($user)
                    ->setBattleStart(time())
                    ->setBattleFinish(time() + 7200)
                    ->setBossId(1)
                    ->setHealth($health);

                $users = new PveUsers();
                $users->setUser($user)
                    ->setHealth(2 * 3 + GameLibs::HEALTH)
                    ->setCreated(time())
                    ->setVisit(time())
                    ->setDamage(0)
                    ->setBattle($pve);

                $pve->addPveUser($users);
                $user->setBattle($pve);

                $this->manager->persist($pve);
                $this->manager->flush();

            } else return ['pve yes'];
        }
    }

    public function use_ability(): array
    {
        $user = $this->usersRepository->findOneBy(['id' => $this->params->getUser()]);

        if ($battle = $user->getBattle()) {

            if ($battle->getBattleFinish() >= time()) {

                if ($battle->getHealth() > 0) {

                    if ($user->getPveUsers()->current()->getHealth() > 0) {

                        $damage_boss = $user->getPveUsers()->current()->getHealth() - rand(5, 10);

                        if ($damage_boss < 0) {
                            $damage_boss = 0;
                        }

                        $battle->setHealth($battle->getHealth() - rand(30, 50));
                        $user->getPveUsers()->current()->setHealth($damage_boss)
                            ->setDamage($user->getPveUsers()->current()->getDamage() + rand(30, 50))
                            ->setVisit(time());

                        $this->manager->flush();
                        if ($battle->getHealth() <= 0) {
                            return $this->useAbility->examination($battle, $user);
                        }
                        return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, $this->pveData->getData($battle, $user));

                    } else return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, $this->pveData->getData($battle, $user, PveData::DAMAGE_NO));
                }
            }
            return $this->dataResponse->success(DataResponse::STATUS_PVE_NO, $this->useAbility->examination($battle, $user));
        }
        return $this->dataResponse->success(DataResponse::STATUS_PVE_NO, 'pve_not_exist');
    }
}
