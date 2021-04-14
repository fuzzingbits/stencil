<?php

namespace FuzzingBits\Stencil;

class AbstractRepository
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * @param array<mixed> $bind
     * @return array<mixed>
     */
    protected function select(string $sql, array $bind = []): array
    {
        return $this->database->select($sql, $bind);
    }
}
