<?php

declare (strict_types = 1);

namespace Login\Service\Reader;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
use Login\Entity\User;

class UserReader implements UserCounterByEmailInterface, UserFinderByEmailInterface
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityRepository $repository
     */
    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findOneByEmail(string $email): User
    {
        $criteria = $this->createEmailCriteria($email);
        $user = $this->repository->findOneBy($criteria);
        if (!$user instanceof User) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(self::getEntityClass(), $criteria);
        }

        return $user;
    }

    public function countByEmail(string $email) : int
    {
        $query = $this->repository->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
        ;

        return (int) $query->getSingleScalarResult();
    }

    private function createEmailCriteria(string $email)
    {
        return array('email' => $email);
    }

    public static function getEntityClass() : string
    {
        return User::class;
    }
}
