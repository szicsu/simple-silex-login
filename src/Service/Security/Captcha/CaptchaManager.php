<?php

declare (strict_types = 1);

namespace Login\Service\Security\Captcha;

use Login\Request\LoginRequest;
use Login\Service\Security\BlackList\BlacklistManagerInterface;
use Login\Service\Security\Captcha\Generator\CaptchaGeneratorInterface;
use Login\Service\Security\Captcha\Storage\CaptchaStorageInterface;

class CaptchaManager implements CaptchaManagerInterface
{
    /**
     * @var BlacklistManagerInterface
     */
    private $blackListManager;

    /**
     * @var CaptchaGeneratorInterface
     */
    private $generator;

    /**
     * @var CaptchaStorageInterface
     */
    private $storage;

    /**
     * @param BlacklistManagerInterface $blackListManager
     * @param CaptchaGeneratorInterface $generator
     * @param CaptchaStorageInterface   $storage
     */
    public function __construct(
        BlacklistManagerInterface $blackListManager,
        CaptchaGeneratorInterface $generator,
        CaptchaStorageInterface $storage
    ) {
        $this->blackListManager = $blackListManager;
        $this->generator = $generator;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function isNeed(LoginRequest $loginRequest) : bool
    {
        return $this->blackListManager->isInBlackList($loginRequest);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageForDisplay(LoginRequest $loginRequest): string
    {
        $desc = $this->generator->generate();
        $this->storage->set($desc->getAnswer());

        return $desc->getQuestion();
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(LoginRequest $loginRequest) : bool
    {
        return $this->storage->get() === $loginRequest->getCaptchaValue();
    }
}
