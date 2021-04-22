<?php

namespace FuzzingBits\Stencil\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class HmacAuthenticator extends AbstractGuardAuthenticator
{
    private Request $request;

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        $providedSignature = $credentials['signature'];
        $correctSignature = HMAC::generateFromRequest(
            $this->request,
            (string) $user->getPassword(),
        );
        return $providedSignature === $correctSignature;
    }

    public function getCredentials(Request $request)
    {
        return [
            'username' => (string) $request->headers->get('PHP_AUTH_USER'),
            'signature' => (string) $request->headers->get('PHP_AUTH_PW'),
            'httpDate' => (string) $request->headers->get('HTTP_DATE'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (null === $credentials) {
            return null;
        }

        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }


    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
    public function supports(Request $request): bool
    {
        $requiredHeaders = [
            'PHP_AUTH_USER',
            'PHP_AUTH_PW',
            'Date',
        ];

        foreach ($requiredHeaders as $requiredHeader) {
            if (!$request->headers->has($requiredHeader)) {
                return false;
            }
        }

        $this->request = $request;

        return true;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
