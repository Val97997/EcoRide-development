<?php
namespace App\Service;

use App\Entity\Carshare;
use App\Entity\User;
use Dompdf\Dompdf;
use Dompsf\Options;

use function PHPUnit\Framework\fileExists;

class TicketGeneratorService
{
    private string $projectDir ;

    private Carshare $carshare;
    private User $user;
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }
    public function getCarshare(): Carshare
    {
        return $this->carshare;
    }
    public function setCarshare(Carshare $carshare): void
    {
        $this->carshare = $carshare;
    }

    public function getUser(): User
    {
        return $this->user;
    }
    public function setUser(User $user): void
    {
        $this->user = $user;
    }


    // public function saveTicketToJson(array $ticketData, string $fileName): void
    // {
    //     $jsonData = json_encode($ticketData, JSON_PRETTY_PRINT);
    //     $jsonfilePath = $this->projectDir . '/public/tickets/' . $fileName;

    //     if(!file_exists($this->projectDir . '/public/tickets/')) {
    //         mkdir($this->projectDir . '/public/tickets/', 0777, true);
    //     }

    //     file_put_contents($jsonfilePath, $jsonData);
    // }

    public function generateTicket(string $fileName): void
    {
        $dompdf = new Dompdf();
        $userDetails = $this->user->getFirstName() . ' ' . $this->user->getLastName();
        // Prepare HTML content with Carshare properties
        $html = '
        <body style="text-align: center; font-family: Motserrat, sans-serif; background-color: lightgreen; border: 1px dashed darkgreen;
         border-radius: 10px; box-shadow: 5px 10px 10px rgba(0, 128, 0, 0.5);">
        <h1 style="color: green; ">Your reservation
        <span style="font-size: 16px; color: darkgreen; margin-left:50px; "> ' .htmlspecialchars($userDetails). '  </span>
        </h1> <hr> <i class="bi bi-qr-code"></i>';
        $departureLoc = $this->carshare->getDepartureLocation();
        $destination = $this->carshare->getArrivalLocation();
        $departureDate = $this->carshare->getDepartureDate()->format('Y-m-d');
        $arrivalDate = $this->carshare->getArrivalDate()->format('Y-m-d');
        $html .= '<h3>Route: ' . htmlspecialchars($departureLoc) . '  '.
        htmlspecialchars($destination) . '</h3>';
        
        $html .= '<h3>Dates: ' . htmlspecialchars($departureDate) . '  to  '.
        htmlspecialchars($arrivalDate) . '</h3>
        <footer>
        <p style="font-size: 12px; color: darkgreen;">EcoRide, all rights reserved / Contact: ecoRide@mail.com</p>
        </footer>
        </body>';
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'landscape');
        $dompdf->render();
        
        $pdfOutput = $dompdf->output();

        $pdfDir = $this->projectDir . '/public/tickets/';
        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0777, true);
        }

            file_put_contents($pdfDir . $fileName, $pdfOutput);

        
    }
}