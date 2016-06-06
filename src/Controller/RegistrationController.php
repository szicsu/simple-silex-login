<?php

namespace Login\Controller;

use Login\Form\Factory\RegistrationFormFactory;
use Login\Service\Util\RendererServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

class RegistrationController extends AbstractController
{
    /**
     * @var RegistrationFormFactory
     */
    private $registrationFormFactory;

    public function __construct(RendererServiceInterface $renderer, UrlGenerator $router, RegistrationFormFactory $registrationFormFactory)
    {
        parent::__construct($renderer, $router);

        $this->registrationFormFactory = $registrationFormFactory;
    }

    public function indexAction() : Response
    {
        return $this->renderActionTemplate(array(
            'form' => $this->registrationFormFactory->create()->createView(),
        ));
    }
}
