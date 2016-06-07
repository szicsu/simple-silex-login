<?php

declare (strict_types = 1);

namespace Login\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for home page.
 */
class HomeController extends AbstractController
{
    /**
     * Display HomePage.
     */
    public function indexAction() : Response
    {
        return $this->renderActionTemplate(array(
            'loginAction' => $this->generateUrl('login'),
            'registrationAction' => $this->generateUrl('registration'),
        ));
    }
}
