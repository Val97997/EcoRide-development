<?php
// declare(strict_types=1);
namespace App\Controller;
use App\Document\Restaurant;
use App\Document\Review;
use App\Entity\Carshare;
use App\Entity\User;
use App\Enum\ReviewState;
use App\Form\ReviewType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\BSON\Regex;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/review', 'app_review_')]
class ReviewController extends AbstractController{
    private DocumentManager $dm;
    private LoggerInterface $logger;
    public function __construct(DocumentManager $dm, LoggerInterface $logger)
    {
        $this->dm = $dm;
        $this->logger = $logger;
    }

    #[Route('/create/{id}/{uid}', 'create')]
    public function new(Request $request, Carshare $carshare, #[MapEntity(expr: 'repository.find(uid)')] User $user, DocumentManager $dm, EntityManagerInterface $em) {
        $review = new Review();
        $appUser = $this->getUser();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        // check if review already exists, then if it is the case, forbid iteration of review creation
        $routeCheckId = $carshare->getId();
        $reviewerCheck = $user->getId();
        if($dm->getRepository(Review::class)->findOneBy(['userId' => (int)$reviewerCheck, 'carshareId' => (int)$routeCheckId]) != null){
            return $this->redirectToRoute('app_user_profile');
        }

        // check route safety by forbidding invasive access
        if($appUser !== $user){
            return $this->redirectToRoute('app_403',status: 302);
        }
        
        if($form->isSubmitted() && $form->isValid()){
            $this->dm->persist($review);
            $review->setUserId($user->getId());
            $review->setCarshareId($carshare->getId());
            
            $this->dm->flush();
            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('review/new.html.twig', [
            'carshare' => $carshare,
            'reviewForm' => $form,
        ]);
    }
}