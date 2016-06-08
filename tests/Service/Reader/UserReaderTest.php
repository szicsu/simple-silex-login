<?php

declare (strict_types = 1);

namespace Login\Tests\Service\Reader;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Login\Entity\User;
use Login\Service\Reader\UserReader;

class UserReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testFindOneByEmail()
    {
        $email = 'foo@bar.com';

        $repo = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $repo
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturnCallback(function (array  $c) use ($email) {
                $this->assertEquals(array('email' => $email), $c);

                return $this->createMock(User::class);
            });

        /* @var EntityRepository $repo */
        $reader = new UserReader($repo);

        $user = $reader->findOneByEmail($email);
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @throws \Doctrine\ORM\EntityNotFoundException
     * @expectedException \Doctrine\ORM\EntityNotFoundException
     */
    public function testFindOneByEmailWithNotFound()
    {
        $email = 'foo@bar.com';

        $repo = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $repo
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturnCallback(function (array  $c) use ($email) {
                $this->assertEquals(array('email' => $email), $c);

                return;
            });

        /* @var EntityRepository $repo */
        $reader = new UserReader($repo);
        $reader->findOneByEmail($email);
    }

    public function testCountByEmail()
    {
        $email = 'foo@bar.com';

        $repo = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $repo
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->with($this->equalTo('u'))
            ->willReturn($this->createMockQueryBuilder($email));

        /* @var EntityRepository $repo */
        $reader = new UserReader($repo);
        $this->assertSame(42, $reader->countByEmail($email));
    }

    /**
     * @param string $email
     *
     * @return QueryBuilder
     */
    private function createMockQueryBuilder(string $email) : QueryBuilder
    {
        $query = $this->getMockBuilder(AbstractQuery::class)->disableOriginalConstructor()->getMock();
        $query->expects($this->once())->method('getSingleScalarResult')->willReturn('42');

        $qb = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $qb->expects($this->once())->method('select')->with($this->equalTo('count(u.id)'))->willReturnSelf();
        $qb->expects($this->once())->method('where')->with($this->equalTo('u.email = :email'))->willReturnSelf();

        $qb
            ->expects($this->once())
            ->method('setParameter')
            ->with(
                $this->equalTo('email'),
                $this->equalTo($email)
            )
            ->willReturnSelf();
        $qb->expects($this->once())->method('getQuery')->willReturn($query);

        return $qb;
    }
}
