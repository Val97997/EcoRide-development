<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ErrorController extends AbstractController{
    #[Route('/403', name: 'app_403')]
    public function index(): Response
    {
        return $this->render('pages/403.html.twig', [
            'controller_name' => '403Controller',
        ]);
    }
}
