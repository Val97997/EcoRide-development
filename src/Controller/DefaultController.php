<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController{
    #[Route('/', name: 'app_default')]
    public function index(Request $request): Response
    {
        // Check if the 'showMessage' parameter is present in the request, to display a message if user was redirected from booking
        // This is done by checking the session for 'redirect_from' key
        // If the user was redirected from 'carshare/book/', we will show a message
        $redirectFrom = $request->getSession()->get('redirect_from');
        $request->getSession()->remove('redirect_from');

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'showBookMessage' => ($redirectFrom === '/booking/book/'),
            'registerMessage' => ($redirectFrom === '/register'),
        ]);
    }
}
