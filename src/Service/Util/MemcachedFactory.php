<?php

namespace Login\Service\Util;

class MemcachedFactory
{
    /**
     * @var array
     */
    private $defaultOptions;

    /**
     * @param array $defaultOptions
     */
    public function __construct(array $defaultOptions = array())
    {
        $this->defaultOptions = $defaultOptions;
    }

    /**
     * @param array       $serverList
     * @param string|null $persistentId
     * @param array       $options
     *
     * @see http://php.net/manual/en/memcached.addservers.php
     *
     * @throws \InvalidArgumentException
     *
     * @return \Memcached
     */
    public function create(array $serverList, $persistentId = null, array $options = array())
    {
        $instanceId = $this->createInstanceId($serverList, $persistentId, $options);

        $mc = new \Memcached($instanceId);

        if (true === $mc->isPristine()) {
            $this->applyOptions($mc, $options);
        }

        if (0 === count($mc->getServerList())) {
            $this->applyServers($mc, $serverList);
        }

        return $mc;
    }

    /**
     * @param \Memcached $mc
     * @param array      $options
     *
     * @throws \InvalidArgumentException
     */
    private function applyOptions(\Memcached $mc, array $options)
    {
        $fullOptions = array_replace($this->defaultOptions, $options);
        foreach ($fullOptions as $key => $value) {
            if (true !== $mc->setOption($key, $value)) {
                throw new \InvalidArgumentException(
                    sprintf('Memcached options error: %s', $mc->getResultMessage()),
                    $mc->getResultCode()
                );
            };
        }
    }

    /**
     * @param \Memcached $mc
     * @param array      $serverList
     *
     * @throws \InvalidArgumentException
     */
    private function applyServers($mc, array $serverList)
    {
        if (true !== $mc->addServers($serverList)) {
            throw new \InvalidArgumentException(
                sprintf('Memcached server error: %s', $mc->getResultMessage()),
                $mc->getResultCode()
            );
        }
    }

    /**
     * @param array       $serverList
     * @param string|null $persistentId
     * @param array       $options
     *
     * @return null|string
     */
    private function createInstanceId(array $serverList, $persistentId, array $options)
    {
        if ($persistentId !== null) {
            $hash = substr(md5(serialize([$serverList, $options, $this->defaultOptions])), 0, 8);
            $instanceId = $persistentId.'.'.$hash;

            return $instanceId;
        } else {
            $instanceId = null;

            return $instanceId;
        }
    }
}
