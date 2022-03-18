<?php

namespace App\Controller;

use App\Game\Authorize;
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

    #[Route('/pve/start', name: 'app_pve_start')]
    public function pveStart(DataResponse $dataResponse, UserParams $params): Response
    {
        return $this->json($dataResponse->success(DataResponse::STATUS_SUCCESS, []));
    }

    #[Route('/contacts/get', name: 'app_contacts_start')]
    public function contacts(): Response
    {
        return $this->json([]);
    }

}
