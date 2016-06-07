<?php

namespace Login\Controller;

use Login\Form\Factory\RegistrationFormFactory;
use Login\Service\RegistrationService;
use Login\Service\Util\RendererServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

class RegistrationController extends AbstractController
{
    /**
     * @var RegistrationFormFactory
     */
    private $registrationFormFactory;

    /**
     * @var RegistrationService
     */
    private $registrationService;

    public function __construct(
        RendererServiceInterface $renderer,
        UrlGenerator $router,
        RegistrationFormFactory $registrationFormFactory,
        RegistrationService $registrationService
    ) {
        parent::__construct($renderer, $router);

        $this->registrationFormFactory = $registrationFormFactory;
        $this->registrationService = $registrationService;
    }

    public function indexAction() : Response
    {
        return $this->renderActionTemplate(array(
            'form' => $this->registrationFormFactory->create()->createView(),
            'action' => $this->generateUrl('registration_save'), //TODO duplicte params
        ));
    }

    public function saveAction(Request $request): Response
    {
        $form = $this->registrationFormFactory->create();
        $form->handleRequest($request);

        if (true !== $form->isSubmitted()) {
            throw new MethodNotAllowedHttpException('POST');
        }

        if ($form->isValid()) {
            $this->registrationService->register($form->getData());

            return $this->redirect($this->generateUrl('registration_success'));
        }

        return $this->render('Registration/index', array(
            'form' => $form->createView(),
            'action' => $this->generateUrl('registration_save'),
        ));
    }

    public function successAction(): Response
    {
        return $this->renderActionTemplate(array(
            'loginAction' => $this->generateUrl('login'),
        ));
    }
}
