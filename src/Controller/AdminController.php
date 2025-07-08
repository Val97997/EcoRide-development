<?php

namespace App\Controller;

use App\Data\FindUser;
use App\Data\FindUserData;
use App\Document\Review;
use App\Entity\Carshare;
use App\Entity\User;
use App\Form\EmployeeFormType;
use App\Form\FindUserType;
use App\Form\RegistrationFormType;
use App\Repository\CarshareRepository;
use App\Repository\UserRepository;
use App\Service\RoleManagerService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// routes protected by security.yaml settings admin restriction
#[Route('/admin', name: 'app_admin_')]
final class AdminController extends AbstractController{

    #[Route('/workspace', name: 'workspace')]
    public function index(EntityManagerInterface $em, CarshareRepository $carshareRepository, Request $request, UserRepository $userRepository): Response
    {
        // get info for the admin charts and total earned credits
        $routesPerD = $carshareRepository->getDailyRoutes();
        $earnings = $carshareRepository->getEarnedCredits();
        $totalBenef = $carshareRepository->getTotalBenef();
        // dd($totalBenef);
        // dd($earnings);

        // initiate the form for account browse and suspension
        $udata = new FindUserData();
        $form = $this->createForm(FindUserType::class, $udata);
        $form->handleRequest($request);
        $user = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findUserByPseudoId($udata);
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'routesPD' => json_encode($routesPerD),
            'earnings' => json_encode($earnings),
            'benef' => $totalBenef[0],
            'searchUserForm' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/subordonate/create', name: 'addemployee')]
    public function createEmployee(Request $request, RoleManagerService $roleManagerService, EntityManagerInterface $em){

        $employee = new User();
        $form = $this->createForm(EmployeeFormType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $roleManagerService->addRole($employee, 'ROLE_EMPLOYEE');
            $roleManagerService->removeRole($employee, 'ROLE_USER');
            $employee->setCreditBalance(0);// no credits for employee User
            return $this->redirectToRoute('app_admin_workspace');
        }

        return $this->render('employee/add.html.twig', [
            'employeeForm' => $form,
        ]);
    }

    #[Route('/suspend/user/{id}', name: 'suspend-account')]
    public function suspendAccount(User $user, EntityManagerInterface $em, Request $request){
        // the method will not only remove the User , but also all entities associated, cars and routes
        $em->persist($user);
        $cars = $user->getCars();
        $routes = $user->getCarshares();


        foreach($routes as $route){
            $em->remove($route);
        }
        foreach($cars as $car){
            $em->remove($car);
        }
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_admin_workspace');
    }
}
