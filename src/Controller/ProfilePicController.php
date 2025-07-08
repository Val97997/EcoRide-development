<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilePicController extends AbstractController{
    #[Route('user/image/{id}', name: 'app_profile_pic')]
    public function displayImage(EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user || !$user->getPicture()) {
            throw $this->createNotFoundException('Image non trouvée');
        }

        $photo = $user->getPicture();

        // Déterminez le type MIME de l'image (exemple pour JPEG)
        $response = new StreamedResponse(function() use ($photo) {
            echo stream_get_contents($photo);
        });
        $response->headers->set('Content-Type', ['image/jpeg', 'image/jpg', 'image/png']); // Ajustez selon le type réel
        
        return $response;
    }
}
