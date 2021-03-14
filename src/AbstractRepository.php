<?php

namespace FuzzingBits\Stencil;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;

class AbstractRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param array<string,mixed> $bind
     */
    protected function query(string $sql, array $bind = []): Result
    {
        return $this->connection->executeQuery($sql, $bind);
    }
}
