<?php

namespace Login\Validator;

use Login\Service\Reader\UserEmailCounterInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEmailValidator extends ConstraintValidator
{
    /**
     * @var UserEmailCounterInterface
     */
    private $userEmailCounter;

    /**
     * @param UserEmailCounterInterface $userEmailCounter
     */
    public function __construct(UserEmailCounterInterface $userEmailCounter)
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
