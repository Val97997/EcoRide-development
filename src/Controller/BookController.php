<?php

namespace App\Controller;

use App\Entity\Carshare;
use App\Entity\User;
use App\Form\SearchFormType;
use App\Service\BookMailService;
use App\Service\TicketGeneratorService;
use Doctrine\DBAL\Driver\PgSQL\ConvertParameters;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

#[Route('/booking', name: 'app_')]
class BookController extends AbstractController{
    #[Route('/book/{id}/{uid}', name: 'book')]

    // IMPORTANT: The 'id' parameter should match the Carshare entity's ID and 'uid' should match the User entity's ID.
    // The 'uid' parameter is used to identify the user who is booking the carshare.
    // The 'id' parameter is used to identify the carshare that the user wants to book.
    //Which is why we use #[MapEntity(expr: 'repository.find(uid)')] User $user in order to map the user entity based on the 'uid' parameter.

    public function book(Carshare $carshare,#[MapEntity(expr: 'repository.find(uid)')] User $user,
     EntityManagerInterface $entityManager, TicketGeneratorService $ticketGeneratorService, BookMailService $bookMailService, Request $request
    ): Response
    {
        // update number of available seats and user credit balance
        $entityManager->persist($carshare);
        $entityManager->persist($user);
        $quantity = $carshare->getAvailableSeats();
        $balance = $user->getCreditBalance();
        
        // fix the credit price to be 10% of the carshare price in currency units
        $creditPrice = (int)($carshare->getPrice() / 10);
        $user->setCreditBalance($balance-$creditPrice);
        $user->addBookHistory($carshare);
        $carshare->addPassenger($user);
        $carshare->setAvailableSeats($quantity - 1);
        
        // Optionally, you can add logic to handle the case when no seats are available

        // Generate the PDF ticket using the service
        // generate ticket
        $ticketGeneratorService->setCarshare($carshare);
        $ticketGeneratorService->setUser($user);
        $ticketGeneratorService->generateTicket('ticket.pdf');
        // send email to user
        $bookMailService->setUser($user);
        $bookMailService->generateBookingEmail();

        // flush to the database the necessary queries to update it :
        $entityManager->flush();

        $this->addFlash('success', 'Your booking is confirmed, email sent!');

        // Redirect to the default route after booking
        // This will redirect to the index action of DefaultController with a flash message confirming the booking !
        $request->getSession()->set('redirect_from', '/booking/book/');
        return new RedirectResponse($this->generateUrl('app_default'));
    }

    #[Route('/cancelDialog/{id}', name: 'cancel_confirm')]
    public function cancelConfirm(Carshare $carshare): Response{
        $user = $this->getUser();
        $pass = $carshare->getPassengers();
        $isPassenger = false;
        foreach ($pass as $p) {
            if ($p === $user) {
                $isPassenger = true;
                break;
            }
        }
        if(!$isPassenger){
            return $this->render('403.html.twig');
        }

        return $this->render('carshare/cancel_book.html.twig', [
            'controller_name' => 'BookController',
            'carshare' => $carshare,
        ]);
    }

    #[Route('/cancel/{id}/{uid}', name: 'cancel')]
    public function cancel(Carshare $carshare,#[MapEntity(expr: 'repository.find(uid)')] User $user, EntityManagerInterface $em): RedirectResponse|Response{
        $em->persist($carshare);
        $em->persist($user);
        $pass = $carshare->getPassengers();
        $isPassenger = false;
        foreach ($pass as $p) {
            if ($p === $user) {
                $isPassenger = true;
                break;
            }
        }
        if(!$isPassenger){
            return $this->render('403.html.twig');
        }
        else{
            $user->removeBookHistory($carshare);
            $seats = $carshare->getAvailableSeats();
            $carshare->setAvailableSeats($seats + 1);
            $balance = $user->getCreditBalance();
            $user->setCreditBalance($balance + round(($carshare->getPrice())/10));
            $em->flush();
            return $this->redirectToRoute('app_search');
        }
    }
}
