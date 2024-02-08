<?php

namespace App\Controller;

use App\Game\Authorize;
use App\Game\BattlePve;
use App\Game\Collection;
use App\Game\Fortunes;
use App\Game\Job;
use App\Game\Stats;
use App\Model\UserParams;
use App\Response\DataResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{

    #[Route('/init', name: 'app_init')]
    public function index(DataResponse $dataResponse, UserParams $params): Response
    {
        return $this->json($dataResponse->success(DataResponse::STATUS_SUCCESS, []));
    }

    #[Route('/authorize', name: 'app_authorize', options: [
        'app_friends', 'sex', 'last_name', 'timezone', 'country', 'city', 'avatar', 'first_name', 'birthdate'
    ])]
    public function authorize(Authorize $authorize): Response
    {
        return $this->json($authorize->hepler());
    }

    #[Route('/pve/start', name: 'app_pve_start', options: ['boss_id'])]
    public function pveStart(BattlePve $pve): Response
    {
        return $this->json($pve->hepler());
    }
    #[Route('/pve/use_ability', name: 'app_pve_use_ability')]
    public function useAbility(BattlePve $pve): Response
    {
        return $this->json($pve->use_ability());
    }

    #[Route('/contacts/get', name: 'app_contacts_start')]
    public function contacts(): Response
    {
        return $this->json();
    }
    #[Route('/fortune/play', name: 'app_fortune_play')]
    public function fortunePlay(Fortunes $fortunes): Response
    {
        return $this->json($fortunes->play());
    }
    #[Route('/fortune/get_prize', name: 'app_fortune_get_prize')]
    public function get_prize(Fortunes $fortunes): Response
    {
        return $this->json($fortunes->get_prize());
    }
    #[Route('/fortune/turn', name: 'app_fortune_turn', options: ['cell_id'])]
    public function turn(Fortunes $fortunes): Response
    {
        return $this->json($fortunes->turn());
    }
    #[Route('/stat/raise', name: 'app_stat_raise', options: ['name'])]
    public function statsRaise(Stats $stats): Response
    {
        return $this->json($stats->raise());
    }
    #[Route('/job/do', name: 'app_job_do', options: ['job_id'])]
    public function jobDo(Job $job): Response
    {
        return $this->json($job->do());
    }

    #[Route('/collection/complete', name: 'app_collection_complete', options: ['collection_id'])]
    public function complete(Collection $collection): Response
    {
        return $this->json($collection->complete());
    }

}
