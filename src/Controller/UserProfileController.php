<?php

namespace Login\Controller;

use Login\Service\Util\RendererServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserProfileController extends AbstractController
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        RendererServiceInterface $renderer,
        UrlGenerator $router,
        TokenStorageInterface $tokenStorage
    ) {
        parent::__construct($renderer, $router);
        $this->tokenStorage = $tokenStorage;
    }

    public function indexAction() : Response
    {
        return $this->renderActionTemplate(array(
            'logoutAction' => $this->generateUrl('logout'),
            'username' => $this->tokenStorage->getToken()->getUsername(),
        ));
    }
}
