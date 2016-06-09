<?php

namespace Login\Service\Security\BlackList\Driver;

use Psr\Log\LoggerInterface;

/**
 * Memcached implementation for DriverInterface.
 */
class MemcachedDriver implements DriverInterface
{
    /**
     * @var \Memcached
     */
    private $memcached;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param \Memcached      $memcached
     * @param string          $namespace
     * @param LoggerInterface $logger
     */
    public function __construct(\Memcached $memcached, string $namespace, LoggerInterface $logger)
    {
        $this->memcached = $memcached;
        $this->namespace = $namespace;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function increment(string $key, int $ttl)
    {
        $normKey = $this->normalizeKey($key);
        $result = $this->memcached->increment($normKey, 1, 0, $ttl);

        if ($result === false) {
            if ($this->memcached->getResultCode() === \Memcached::RES_NOTFOUND) {
                $this->doAdd($normKey, $ttl);
            } else {
                $this->logger->error(sprintf(
                    'The memcached increment error: %s',
                    $this->memcached->getResultMessage()
                ), array(
                    'normKey' => $normKey,
                    'resultCode' => $this->memcached->getResultCode(),
                ));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getByKey(string $key) : int
    {
        $normKey = $this->normalizeKey($key);
        $result = $this->memcached->get($normKey);

        if ($result === false || $result === null) {
            if ($this->memcached->getResultCode() !== \Memcached::RES_NOTFOUND) {
                $this->logger->error(sprintf(
                    'The memcached read error: %s',
                    $this->memcached->getResultMessage()
                ), array(
                    'normKey' => $normKey,
                    'resultCode' => $this->memcached->getResultCode(),
                ));
            }
        }

        return (int) $result;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function normalizeKey(string $key)
    {
        $key = urlencode($this->namespace.'-'.$key);

        if (250 < strlen($key)) {
            $key = $this->namespace.'-'.md5($key);
        }

        return $key;
    }

    /**
     * @param string $normKey
     * @param int    $ttl
     */
    private function doAdd(string $normKey, int $ttl)
    {
        $result = $this->memcached->add($normKey, 1, $ttl);

        if ($result !== true) {
            $this->logger->error(sprintf(
                'The memcached add error: %s',
                $this->memcached->getResultMessage()
            ), array(
                'normKey' => $normKey,
                'resultCode' => $this->memcached->getResultCode(),
            ));
        }
    }
}
