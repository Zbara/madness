<?php

namespace App\Game;

use App\Entity\Fortune;
use App\Exception\FortuneException;
use App\Game\User\Currency;
use App\Json\ParserLibsFortune;
use App\Libs\Random;
use App\Model\UserParams;
use App\Repository\FortuneRepository;
use App\Repository\UsersRepository;
use App\Response\DataResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

class Fortunes
{
    const MONEY_TYPE = 'bills';


    private UserParams $params;
    private EntityManagerInterface $manager;
    private UsersRepository $usersRepository;
    private DataResponse $dataResponse;
    private ParserLibsFortune $libsFortune;
    private Currency $currency;
    private Random $random;
    private FortuneRepository $fortuneRepository;

    public function __construct(
        UserParams             $params,
        EntityManagerInterface $manager,
        UsersRepository        $usersRepository,
        DataResponse           $dataResponse,
        ParserLibsFortune      $libsFortune,
        Currency               $currency,
        Random                 $random,
        FortuneRepository      $fortuneRepository
    )
    {
        $this->params = $params;
        $this->manager = $manager;
        $this->usersRepository = $usersRepository;
        $this->dataResponse = $dataResponse;
        $this->libsFortune = $libsFortune;
        $this->currency = $currency;
        $this->random = $random;
        $this->fortuneRepository = $fortuneRepository;
    }

    #[ArrayShape(['response' => "array"])]
    public function play(): array
    {
        $user = $this->usersRepository->findOneBy(['id' => $this->params->getUser()]);

        if (empty($user->getFortune())) {
            if ($user->getCurrency(self::MONEY_TYPE) <= 0) {
                throw new FortuneException('money error');
            }
            $cells = $this->random->fortune();

            $fortune = new Fortune();
            $fortune->setUser($user)
                ->setCells($cells);
            $user->setFortune($fortune)
                ->setFortuneExperince($user->getFortuneExperince())
                ->setCurrency($this->currency->calculator($user->getCurrency(), Currency::MINUS, 1, self::MONEY_TYPE));

            $this->manager->persist($fortune);
            $this->manager->flush();

            $drops = $this->libsFortune->getDrops($cells);

            return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
                'fortune' => [
                    'cell1' => $cells[1],
                    'cell4' => $cells[2],
                    'cell3' => $cells[3],
                    'cell_count' => 0,
                    'fortune_id' => (int)$drops['id'],
                    'level' => $this->libsFortune->getLevel($user->getFortuneExperince())
                ]
            ]);
        } else throw new FortuneException('play');
    }

    #[ArrayShape(['response' => "array"])]
    public function get_prize(): array
    {
        $user = $this->usersRepository->findOneBy(['id' => $this->params->getUser()]);

        if ($user->getFortune() !== null) {
            $drops = $this->libsFortune->getDrops($user->getFortune()->getCells());

            $user->getFortune()->setFinish(time());
            $user->setFortune(null);


            foreach ($drops['drops']['drop_item'] as $drop) {
                if ($drop['type'] == 'xp') {
                    $user->setExperience($user->getExperience() + $drop['count']);
                } elseif ($drop['type'] == 'currency1') {
                    $user->setCurrency($this->currency->calculator($user->getCurrency(), Currency::PLUS, $drop['count'], Currency::TYPE_1));
                } elseif ($drop['type'] == 'cloth_opened') {
                    //TODO
                }
            }
            $this->manager->flush();

            return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
                'drops' => $drops['drops']
            ]);
        } else throw new FortuneException('no game fortune user');
    }

    #[ArrayShape(['response' => "array"])]
    public function turn(): array
    {
        $user = $this->usersRepository->findOneBy(['id' => $this->params->getUser()]);

        if ($user->getFortune() !== null) {

            if (in_array($this->params->getCellId(), [1, 2, 3]) === false) {
                throw new FortuneException('cell id not found');
            }

            $fortune = $this->libsFortune->getLevel($user->getFortuneExperince(), true);

            if ($user->getFortune()->getNumber() >= $fortune['turn']['count']) {
                throw new FortuneException('error count');
            } elseif ($fortune['turn']['cell' . $this->params->getCellId()] == 'no') {
                throw new FortuneException('error cell id');
            }
            $cells = array_replace($user->getFortune()->getCells(), [
                $this->params->getCellId() => current($this->random->fortune(2))
            ]);

            $user->getFortune()->setCells($cells)
                ->setNumber($user->getFortune()->getNumber());
            $this->manager->flush();

            return $this->dataResponse->success(DataResponse::STATUS_SUCCESS, [
                'fortune' => [
                    'cell1' => $cells[1],
                    'cell4' => $cells[2],
                    'cell3' => $cells[3],
                    'cell_count' => $user->getFortune()->getNumber(),
                    'fortune_id' => (int)$this->libsFortune->getDrops($cells)['id']
                ]
            ]);
        } else throw new FortuneException('no game', 0);
    }
}
