<?php


namespace Login\Service\Security;


use Login\Request\LoginRequestFactory;
use Login\Service\Security\BlackList\BlacklistManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @var BlacklistManagerInterface
     */
    private $blacklistManager;

    /**
     * @var LoginRequestFactory
     */
    private $loginRequestFactory;

    /**
     * @var AuthenticationFailureHandlerInterface
     */
    private $innerHandler;

    /**
     * @param BlacklistManagerInterface $blacklistManager
     * @param AuthenticationFailureHandlerInterface $innerHandler
     * @param LoginRequestFactory $loginRequestFactory
     */
    public function __construct(
        BlacklistManagerInterface $blacklistManager,
        AuthenticationFailureHandlerInterface $innerHandler,
        LoginRequestFactory $loginRequestFactory
    )
    {
        $this->blacklistManager = $blacklistManager;
        $this->innerHandler = $innerHandler;
        $this->loginRequestFactory = $loginRequestFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $this->blacklistManager->handleBadLogin(
            $this->loginRequestFactory->createByEmailAndRequest(
                $exception->getToken()->getUsername(),
                $request
            )
        );

        return $this->innerHandler->onAuthenticationFailure($request, $exception);
    }

}