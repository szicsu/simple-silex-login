<?php


namespace Login\Validator;


use Symfony\Component\Validator\Constraint;

class UniqueEmail extends Constraint
{

    const NOT_UNIQUE_ERROR = 'd53a91b0-def3-426a-83d7-269da7ab420f';
    const SERVICE_NAME = 'UniqueEmailValidatorService';

    protected static $errorNames = array(
        self::NOT_UNIQUE_ERROR => 'NOT_FALSE_ERROR',
    );

    public $message = 'This email address already used!';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return self::SERVICE_NAME;
    }
}