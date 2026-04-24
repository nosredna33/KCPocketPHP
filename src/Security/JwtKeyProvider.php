<?php

namespace KCPocket\Security;

class JwtKeyProvider
{
    private string $privateKeyPath;
    private string $publicKeyPath;

    public function __construct()
    {
        $keyDir = __DIR__ . 
            '/../../data/';
        if (!is_dir($keyDir)) {
            mkdir($keyDir, 0777, true);
        }
        $this->privateKeyPath = $keyDir . 'private_key.pem';
        $this->publicKeyPath = $keyDir . 'public_key.pem';

        if (!file_exists($this->privateKeyPath) || !file_exists($this->publicKeyPath)) {
            $this->generateNewKeys();
        }
    }

    private function generateNewKeys(): void
    {
        $res = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        if (!$res) {
            throw new \Exception('Failed to generate RSA key pair: ' . openssl_error_string());
        }

        openssl_pkey_export($res, $privateKey);
        $publicKey = openssl_pkey_get_details($res)['key'];

        file_put_contents($this->privateKeyPath, $privateKey);
        file_put_contents($this->publicKeyPath, $publicKey);
    }

    public function getPrivateKey(): string
    {
        return file_get_contents($this->privateKeyPath);
    }

    public function getPublicKey(): string
    {
        return file_get_contents($this->publicKeyPath);
    }

    public function getPublicKeyDetails(): array
    {
        $publicKey = $this->getPublicKey();
        $keyDetails = openssl_pkey_get_public($publicKey);
        if (!$keyDetails) {
            throw new \Exception("Failed to get public key details.");
        }
        $details = openssl_pkey_get_details($keyDetails);

        return [
            "kty" => "RSA",
            "use" => "sig",
            "alg" => "RS256",
            "kid" => "kcpocket-php-key",
            "n" => rtrim(strtr(base64_encode($details["rsa"]["n"]), '+/', '-_'), '='),
            "e" => rtrim(strtr(base64_encode($details["rsa"]["e"]), '+/', '-_'), '=')
        ];
    }
}
