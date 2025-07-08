<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Service\RoleManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/register', name: 'app_register_')]
class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/driver', name: 'user')]
    public function registerDriver(Request $request, UserPasswordHasherInterface $userPasswordHasher,
    RoleManagerService $roleManager, EntityManagerInterface $entityManager): RedirectResponse|Response
    {
        $user = new User();
        
        
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // define the Driver role for the user regisering from this form page by using the appropriate custom service:
            $roleManager->addRole($user, 'ROLE_DRIVER');
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            //deal with the profile pic uploading, so that it gets sent in a correct form to the DB :
            $uploadedPic = $form->get('picture')->getData();
            if($uploadedPic){
                // Generate a unique filename
                $newFilename = $form->get('pseudo')->getData() . '.' . $uploadedPic->guessExtension();

                // Move the file to a permanent location
                $uploadedPic->move(
                    'profile_pics/',
                    $newFilename
                );
            }

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_register_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('noreply@ecoride.com', 'EcoRide'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            $this->addFlash('register', 'Driver account registered, please check your email inbox for pending verification !');
            $request->getSession()->set('redirect_from', '/register');

            return new RedirectResponse($this->generateUrl('app_default'));
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'userRole' => 'driver',
        ]);
    }

    #[Route('/passenger', name: 'client')]
     public function registerPassenger(Request $request, UserPasswordHasherInterface $userPasswordHasher,
    RoleManagerService $roleManager, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        
        
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // define the passenger role for the user regisering from this form page by using the appropriate custom service:
            $roleManager->addRole($user, 'ROLE_PASSENGER');
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();
            
            
            //deal with the profile pic uploading, so that it gets sent in a correct form to the DB :
            $uploadedPic = $form->get('picture')->getData();
            if($uploadedPic){
                // Generate a unique filename
                $newFilename = $form->get('pseudo')->getData() . '.' . $uploadedPic->guessExtension();

                // Move the file to a permanent location
                $uploadedPic->move(
                    'profile_pics/',
                    $newFilename
                );
            }
            

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_register_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('mofculture@yahoo.com', 'Ecoride Mail Bot'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email
            //flash message for display on homepage redirect :
            $this->addFlash('register', 'Sign in successful, please check your email inbox for pending verification !');
            $request->getSession()->set('redirect_from', '/register');

            return $this->redirectToRoute('app_default');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'userRole' => 'passenger',
        ]);
    }

    #[Route('/verify/email', name: 'verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_default');
    }
}
