<?php

declare (strict_types = 1);

namespace Login\Service\Reader;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Login\Entity\User;

class UserReader implements UserCounterByEmailInterface, UserFinderByEmailInterface
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
        $criteria = $this->createEmailCriteria($email);
        $user = $this->getRepository()->findOneBy($criteria);
        if (!$user instanceof User) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(User::class, $criteria);
        }

        return $user;
    }

    public function countByEmail(string $email) : int
    {
        $query = $this->getRepository()->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
        ;

        return (int) $query->getSingleScalarResult();
    }

    private function getRepository() : EntityRepository
    {
        return $this->manager->getRepository(User::class);
    }

    private function createEmailCriteria(string $email)
    {
        return array('email' => $email);
    }
}
