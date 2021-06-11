<?php

namespace FuzzingBits\Stencil\Security;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class HmacUserProvider implements UserProviderInterface
{
    /** @var mixed[] */
    private array $hmacUsers = [];

    /**
     * @param mixed[] $hmacUsers
     */
    public function __construct(array $hmacUsers)
    {
        $this->hmacUsers = $hmacUsers;
    }

    public function loadUserByUsername(string $username)
    {
        if (!array_key_exists($username, $this->hmacUsers)) {
            throw new UsernameNotFoundException();
        }
        $user = $this->hmacUsers[$username];
        $password = $user['password'];
        $roles = $user['roles'];

        return new HmacUser($username, $password, $roles);
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass(string $class)
    {
        return $class === HmacUser::class;
    }
}
