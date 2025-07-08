<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use App\Service\DbScriptsExec;
use Doctrine\DBAL\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdmincreateController extends AbstractController{
    #[Route('/createAdmin', name: 'app_admincreate')]
    public function sql(DbScriptsExec $dbScriptsExec): Response
    {
        try{
            $dbScriptsExec->executeScript('admin.sql');
            return new Response('SQL executed');
        }
        catch(\Exception $e){
            return new Response('Error executing the script: ' . $e->getMessage());
        }
    }
}
