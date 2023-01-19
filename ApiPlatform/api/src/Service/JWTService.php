<?php

namespace App\Service;

class JWTService {
    public function createToken(array $header, array $payload, string $secret, int $validityTime = 3600): string
    {
        if ($validityTime <= 0){
            return "";
        }
        $now = new \DateTimeImmutable();
        $exp = $now -> getTimestamp() + $validityTime;
        $payload['iat'] = $now-> getTimestamp();
        $payload['exp'] = $exp;

        $base64Header = base64_encode(json_encode($header, JSON_THROW_ON_ERROR));
        $base64Payload = base64_encode(json_encode($payload, JSON_THROW_ON_ERROR));

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], $base64Header);
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], $base64Payload);

        $secret = base64_encode($secret);
        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, $secret, true);
        $base64Signature = base64_encode($signature);

        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], $base64Signature);

        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }
}