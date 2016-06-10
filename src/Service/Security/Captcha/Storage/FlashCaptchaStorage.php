<?php

declare (strict_types = 1);

namespace Login\Service\Security\Captcha\Storage;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Symfony Session base Captcha storage.
 */
class FlashCaptchaStorage implements CaptchaStorageInterface
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var string
     */
    private $storeKey;

    /**
     * @param FlashBagInterface $flashBag
     * @param string            $storeKey
     */
    public function __construct(FlashBagInterface $flashBag, string $storeKey = 'captcha')
    {
        $this->flashBag = $flashBag;
        $this->storeKey = $storeKey;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $value)
    {
        $this->flashBag->set($this->storeKey, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function get() : string
    {
        return (string) current($this->flashBag->get($this->storeKey));
    }
}
