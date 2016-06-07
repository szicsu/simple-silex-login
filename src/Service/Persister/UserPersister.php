<?php

namespace Login\Service\Persister;

use Doctrine\ORM\EntityManager;
use Login\Entity\User;

class UserPersister
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

    public function createNew() : User
    {
        return $this->manager->getClassMetadata(User::class)->newInstance();
    }

    public function persist(User $user)
    {
        $this->manager->persist($user);
        $this->manager->flush($user);
    }
}
