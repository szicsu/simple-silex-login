<?php

declare (strict_types = 1);

namespace Login\Form\Factory;

use Login\Form\Type\RegistrationType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class RegistrationFormFactory
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return Form
     */
    public function create(array $data = array(), array $options = array()): Form
    {
        return $this->formFactory
            ->createBuilder(RegistrationType::class, $data, $options)
            ->setAction(Request::METHOD_POST)
            ->getForm()
        ;
    }
}
