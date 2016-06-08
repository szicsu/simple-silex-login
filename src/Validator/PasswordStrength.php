<?php

namespace Login\Validator;

use Symfony\Component\Validator\Constraint;

class PasswordStrength extends Constraint
{
    const SHORT_ERROR = 'd53a91b0-def3-426a-83d7-269da7ab4200';
    const CAPITAL_ERROR = 'd53a91b0-def3-426a-83d7-269da7ab4201';
    const SMALL_ERROR = 'd53a91b0-def3-426a-83d7-269da7ab4202';
    const NUMBER_ERROR = 'd53a91b0-def3-426a-83d7-269da7ab4203';
    const SPEC_CHAR_ERROR = 'd53a91b0-def3-426a-83d7-269da7ab4204';

    protected static $errorNames = array(
        self::SHORT_ERROR => 'SHORT_ERROR',
        self::CAPITAL_ERROR => 'CAPITAL_ERROR',
        self::SMALL_ERROR => 'SMALL_ERROR',
        self::NUMBER_ERROR => 'NUMBER_ERROR',
        self::SPEC_CHAR_ERROR => 'SPEC_CHAR_ERROR',
    );

    public $min = 10;
    public $shortMessage = 'The password is too short! ({{ min }} char required)';
    public $capitalMessage = 'The password must contains at least one capital letter!';
    public $smallMessage = 'The password must contains at least one small letter!';
    public $numberMessage = 'The password must contains at least one number!';
    public $specCharMessage = 'The password must contains at least one special character!';
}
