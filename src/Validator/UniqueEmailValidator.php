<?php

namespace Login\Validator;

use Login\Service\Reader\UserCounterByEmailInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailValidator extends ConstraintValidator
{
    /**
     * @var UserCounterByEmailInterface
     */
    private $userEmailCounter;

    /**
     * @param UserCounterByEmailInterface $userEmailCounter
     */
    public function __construct(UserCounterByEmailInterface $userEmailCounter)
    {
        $this->userEmailCounter = $userEmailCounter;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }

        if (null === $value) {
            return;
        }

        if (0 !== $this->userEmailCounter->countByEmail($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(UniqueEmail::NOT_UNIQUE_ERROR)
                ->addViolation();
        }
    }
}
