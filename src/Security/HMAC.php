<?php

namespace FuzzingBits\Stencil\Security;

use DateTime;
use Symfony\Component\HttpFoundation\Request;

class HMAC
{
    public static function generate(
        string $method,
        string $path,
        string $payload,
        string $date,
        string $sharedSecret,
    ): string {
        $method = strtolower($method);
        $epoch = (new DateTime($date))->getTimestamp();
        $token = sprintf("%d|%s|%s|%s", $epoch, $method, $path, $payload);
        $hash = hash_hmac('sha256', $token, $sharedSecret);

        return $hash;
    }

    public static function generateFromRequest(
        Request $request,
        string $sharedSecret,
    ): string {
        return self::generate(
            (string) $request->getMethod(),
            (string) $request->getRequestUri(),
            (string) $request->getContent(),
            (string) $request->headers->get('Date'),
            (string) $sharedSecret,
        );
    }
}
