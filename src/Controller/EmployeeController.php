<?php

namespace App\Controller;

use App\Document\Review;
use App\Entity\Carshare;
use App\Entity\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employee', name: 'app_employee_')]
final class EmployeeController extends AbstractController{

    #[Route('/workspace', name: 'workspace')]
    public function workPage(DocumentManager $dm): Response
    {
        $reviewsPending = $dm->getRepository(Review::class)->findBy(["status" => 'pending']);

        return $this->render('employee/index.html.twig', [
            'controller_name' => 'EmployeeController',
            'reviews' => $reviewsPending,
 
        ]);
    }

    #[Route('/review/{id}', name: 'review-card')]
    public function review(Review $review, DocumentManager $dm, EntityManagerInterface $em){

        //get the passenger
        $userid = $review->getUserId();
        $reviewer = $em->getRepository(User::class)->findOneBy(['id' => $userid]);

        // retrieve the driver
        $carshare = $em->getRepository(Carshare::class)->find($review->getCarshareId());
        $driver = null;
        if ($carshare !== null) {
            $driverid = $carshare->getUser()->getId();
            $driver = $em->getRepository(User::class)->findOneBy(['id' => $driverid]);
        }
        // dd($carshare, $driver, $reviewer);
        return $this->render('review/_card.html.twig', [
            'review' => $review,
            'reviewer' => $reviewer,
            'driver' => $driver,
            'carshare' => $carshare,
        ]);
    }
    #[Route('/review/approve/{id}', name: 'review-approve')]
    public function approveReview(Review $review, DocumentManager $dm, EntityManagerInterface $em): Response
    {
        // set status for review, and update the driver credit balance accordingly !
        $dm->persist($review);
        $review->setStatus('approved');
        
        $cid = $review->getCarshareId();
        $carshare = $em->getRepository(Carshare::class)->findOneBy(['id' => $cid]);
        $driver = $carshare->getUser();
        $dm->persist($driver);
        $currentBalance = $driver->getCreditBalance();
        $pricePerSeat = round(($carshare->getPrice())/10);
        $driver->setCreditBalance($currentBalance + $pricePerSeat - 2); // index the 2 platform tax credits

        $dm->flush();

        return $this->redirectToRoute('app_employee_workspace');
    }
    #[Route('/review/reject/{id}', name: 'review-reject')]
    public function rejectReview(Review $review, DocumentManager $dm): Response
    {
        $dm->remove($review);
        $dm->flush();

        return $this->redirectToRoute('app_employee_workspace');
    }
}
