<?php

namespace App\Controller;

use App\Data\FindUserData;
use App\Data\SearchData;
use App\Entity\Carshare;
use App\Form\FindUserType;
use App\Form\SearchFormType;
use App\Repository\CarshareRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController{
    #[Route('/search', name: 'app_search')]
    public function index(CarshareRepository $carshareRepository, Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchFormType::class, $data);
        $form->handleRequest($request);
        $carshares = $carshareRepository->findSearch($data);
        return $this->render('search/index.html.twig', [
            'carshares' => $carshares,
            'searchForm' => $form,
        ]);
    }
}
