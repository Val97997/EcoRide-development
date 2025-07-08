<?php

namespace App\Service;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpKernel\KernelInterface;

class DbScriptsExec{
    private $connection;
    private $kernel;

    public function __construct(Connection $connection, KernelInterface $kernel)
    {
        $this->connection = $connection;
        $this->kernel = $kernel;
    }

    public function executeScript(string $scriptPath)
    {
        $scriptPath = $this->kernel->getProjectDir() . '/scripts/' . $scriptPath;
        $sql = file_get_contents($scriptPath);

        if ($sql === false) {
            throw new \RuntimeException("Unable to read SQL script: $scriptPath");
        }

        // Split the script into individual queries
        $queries = array_filter(explode(';', $sql));

        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                $this->connection->executeStatement($query);
            }
        }
    }
}