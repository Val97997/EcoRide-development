<?php

namespace App\Service;

use App\Entity\Carshare;
use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

use function Symfony\Component\Clock\now;

class BookMailService
{
     private string $projectDir;
    private MailerInterface $mailer;
    private LoggerInterface $logger;
    private User $user;
    public function __construct(
        MailerInterface $mailer,
        string $projectDir,
        LoggerInterface $logger
    ) {
        $this->mailer = $mailer;
        $this->projectDir = $projectDir;
        $this->logger = $logger;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function generateBookingEmail(): void
    {
        try {
            // Verify the ticket file exists
            $ticketPath = $this->projectDir . '/public/tickets/ticket.pdf';

            if (!file_exists($ticketPath)) {
                $this->logger->error('Ticket file not found: ' . $ticketPath);
                throw new \RuntimeException('Ticket file not found');
            }

            $email = (new TemplatedEmail())
                ->from(new Address('noreply@ecoride.com', 'EcoRide'))
                ->to(new Address($this->user->getEmail(), $this->user->getFirstName()))
                ->subject('Booking Confirmation')
                ->addPart(new DataPart(new File($ticketPath,'Booking ticket')))
                // define variables to be used and rendered in mail template :
                ->context([
                    'user' => $this->user,
                ])
                ->htmlTemplate('email/bookSuccess.html.twig');

            $this->mailer->send($email);
            $this->logger->info('Booking confirmation email sent to ' . $this->user->getEmail());

        } catch (\Exception $e) {
            $this->logger->error('Failed to send booking email: ' . $e->getMessage());
            // Re-throw the exception or handle it as needed
            throw $e;
        }
    }
    //     private function getBookEmailContent(): string
    // {
    //     return '<h3>Dear ' . htmlspecialchars($this->user->getFirstName()) . ',</h3>
    //             <p>Your booking has been confirmed.</p>
    //             <p>Thank you for choosing EcoRide!</p>
    //             <p>Your ticket is attached to this email.</p>';
    // }

    public function generateCancelEmail(Carshare $carshare):void
    {
        try{
            $arrayPassengers = $carshare->getPassengers();
            foreach ($arrayPassengers as $passenger) {
                $cancelEmail = (new TemplatedEmail())
                    ->from(new Address('noreply@ecoride.com', 'EcoRide'))
                    ->to(new Address($passenger->getEmail(), $passenger->getFirstName()))
                    ->subject('Carshare cancellation')
                    ->date(now())
                    ->context([
                        'carshare' => $carshare,
                        'passenger' => $passenger,
                    ])
                    ->htmlTemplate('email/cancelCarshare.html.twig');

                    $this->mailer->send($cancelEmail);

            }
        } catch (\Exception $e) {
            $this->logger->error('Failed to send booking email: ' . $e->getMessage());
            // Re-throw the exception or handle it as needed
            throw $e;
        }

    }

    public function generateFinishEmail(Carshare $carshare): void{
        try{
            $arrayPassengers = $carshare->getPassengers();
            foreach ($arrayPassengers as $passenger) {
                $cancelEmail = (new TemplatedEmail())
                    ->from(new Address('noreply@ecoride.com', 'EcoRide'))
                    ->to(new Address($passenger->getEmail(), $passenger->getFirstName()))
                    ->subject('Carshare ended')
                    ->date(now())
                    ->context([
                        'carshare' => $carshare,
                        'passenger' => $passenger,
                    ])
                    ->htmlTemplate('email/endCarshare.html.twig');

                    $this->mailer->send($cancelEmail);

            }
        } catch (\Exception $e) {
            $this->logger->error('Failed to send voyage complete email: ' . $e->getMessage());
            // Re-throw the exception or handle it as needed
            throw $e;
        }
    }
}