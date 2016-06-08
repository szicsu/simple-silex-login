<?php


namespace Login\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordStrengthValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PasswordStrength) {
            throw new UnexpectedTypeException($constraint, PasswordStrength::class);
        }

        if (null === $value) {
            return;
        }


        /** @var PasswordStrength $constraint */
        if (strlen($value) < $constraint->min) {
            $this->context->buildViolation($constraint->shortMessage)
                ->setParameter('{{ min }}', $constraint->min)
                ->setCode(PasswordStrength::SHORT_ERROR)
                ->addViolation();
        }

        if ( ! preg_match( '/[A-Z]/', $value ) ) {
            $this->context->buildViolation($constraint->capitalMessage)
                ->setCode(PasswordStrength::CAPITAL_ERROR)
                ->addViolation();
        }

        if ( ! preg_match( '/[a-z]/', $value ) ) {
            $this->context->buildViolation($constraint->smallMessage)
                ->setCode(PasswordStrength::SMALL_ERROR)
                ->addViolation();
        }

        if ( ! preg_match( '/[0-9]/', $value ) ) {
            $this->context->buildViolation($constraint->numberMessage)
                ->setCode(PasswordStrength::NUMBER_ERROR)
                ->addViolation();
        }

        if ( ! preg_match( '/[^0-9a-zA-Z]/', $value ) ) {
            $this->context->buildViolation($constraint->specCharMessage)
                ->setCode(PasswordStrength::SPEC_CHAR_ERROR)
                ->addViolation();
        }
    }
}