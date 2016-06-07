<?php

declare (strict_types = 1);

namespace Login\Form\Factory;

use Login\Form\Type\RegistrationType;
use Login\Request\RegistrationRequest;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
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
        $builder = $this->formFactory->createBuilder(
            RegistrationType::class,
            $data,
            array_replace($options, array('data_class' => RegistrationRequest::class))
        );
        $builder->setAction(Request::METHOD_POST);
        $builder->addViewTransformer($this->createViewTransformer());

        return $builder->getForm();
    }

    private function createViewTransformer(): DataTransformerInterface
    {
        $transform = function () {
            return new RegistrationRequest();
        };

        $reverseTransform = function ($value) {
            return $value;
        };

        return new CallbackTransformer($transform, $reverseTransform);
    }
}
