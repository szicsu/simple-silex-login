<?php

declare (strict_types = 1);

namespace Login\Service\Reader;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Login\Entity\User;

class UserReader
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    public function findOneByEmail(string $email): User
    {
        $criteria = array('email' => $email);
        $user = $this->manager->getRepository(User::class)->findOneBy($criteria);
        if (!$user instanceof User) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(User::class, $criteria);
        }

        return $user;
    }
}
