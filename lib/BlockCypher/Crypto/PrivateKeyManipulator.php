<?php

namespace BlockCypher\Crypto;

use BitWasp\Bitcoin\Key\PrivateKeyFactory;
use BitWasp\Bitcoin\Key\PrivateKeyInterface;

/**
 * Class PrivateKeyManipulator
 * @package BlockCypher\Crypto
 */
class PrivateKeyManipulator
{
    /**
     * @param string $plainPrivateKey
     * @return PrivateKeyInterface
     */
    public static function importPrivateKey($plainPrivateKey)
    {
        $privateKey = null;
        $extraMsg = '';

        // TODO: Code Review. Method to detect private key format.

        if ($privateKey === null) {
            try {
                $privateKey = PrivateKeyFactory::fromWif($plainPrivateKey);
            } catch (\Exception $e) {
                $extraMsg .= " Error trying to import from Wif: " . $e->getMessage();
            }
        }

        if ($privateKey === null) {
            try {
                $privateKey = PrivateKeyFactory::fromHex($plainPrivateKey);
            } catch (\Exception $e) {
                $extraMsg .= " Error trying to import from Hex: " . $e->getMessage();
            }
        }

        if ($privateKey === null) {
            throw new \InvalidArgumentException("Invalid private key format. " . $extraMsg);
        }

        return $privateKey;
    }

    /**
     * @param string $wifPrivateKey
     * @return PrivateKeyInterface
     * @throws \Exception
     */
    public static function importPrivateKeyFromWif($wifPrivateKey)
    {
        $privateKey = PrivateKeyFactory::fromWif($wifPrivateKey);
        return $privateKey;
    }

    /**
     * @param string $hexPrivateKey
     * @param bool $compressed
     * @return string
     */
    public static function generateHexPubKeyFromHexPrivKey($hexPrivateKey, $compressed = true)
    {
        $privateKey = self::importPrivateKeyFromHex($hexPrivateKey, $compressed);
        $hexPublicKey = $privateKey->getPublicKey()->getHex();
        return $hexPublicKey;
    }

    /**
     * @param string $hexPrivateKey
     * @param bool $compressed
     * @return \BitWasp\Bitcoin\Key\PrivateKeyInterface
     */
    public static function importPrivateKeyFromHex($hexPrivateKey, $compressed = true)
    {
        $privateKey = PrivateKeyFactory::fromHex($hexPrivateKey, $compressed);
        return $privateKey;
    }
}