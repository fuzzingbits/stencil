<?php

namespace FuzzingBits\Stencil;

use Doctrine\DBAL\Result;
use Doctrine\DBAL\Connection;

class Database
{
    public Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param array<mixed> $bind
     * @return integer
     */
    public function execute(string $sql, array $bind = []): int
    {
        $statement = $this->run($sql, $bind);

        return $statement->rowCount();
    }

    /**
     * @param array<mixed> $bind
     * @return array<mixed>
     */
    public function select(string $sql, array $bind = []): array
    {
        $results = $this->run($sql, $bind);

        return $results->fetchAllAssociative();
    }

    /**
     * @param array<mixed> $bind
     */
    private function run(string $sql, array $bind = []): Result
    {
        $statement = $this->connection->prepare($sql);
        foreach ($bind as $key => $value) {
            $statement->bindValue($key, $value);
        }

        return $statement->execute();
    }
}
