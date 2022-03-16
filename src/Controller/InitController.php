<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InitController extends AbstractController
{
    #[Route('/init', name: 'app_init')]
    public function index(): Response
    {
        return $this->json([]);
    }
}
