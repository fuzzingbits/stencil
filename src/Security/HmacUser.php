<?php

namespace FuzzingBits\Stencil\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class HmacUser implements UserInterface
{
    private string $password;

    /** @var string[] */
    private array $roles;

    private string $username;

    /**
     * @param string[] $roles
     */
    public function __construct(string $username, string $password, array $roles)
    {
        $this->username = $username;
        $this->password = $password;
        $this->roles = $roles;
    }

    /**
     * @return void
     */
    public function eraseCredentials()
    {
        return;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        return '';
    }

    public function getUsername()
    {
        return $this->username;
    }
}
