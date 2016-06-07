<?php

declare (strict_types = 1);

namespace Login\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue("AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $salt;

    /**
     * @ORM\Column(type="datetime", nullable=false )
     *
     * @var \DateTime
     */
    private $created_date;

    /**
     * @ORM\Column(type="datetime", nullable=false )
     *
     * @var \DateTime
     */
    private $modified_date;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Version
     *
     * @var int
     */
    private $object_version;

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->created_date;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedDate()
    {
        return $this->modified_date;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return int
     */
    public function getObjectVersion()
    {
        return $this->object_version;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        if (null === $this->salt) {
            $this->salt = random_bytes(255);
        }

        return $this->salt;
    }

    /**
     * @ORM\PrePersist
     */
    public function doPrePersist()
    {
        $this->created_date = new \DateTime();
        $this->modified_date = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function doPreUpdate()
    {
        $this->modified_date = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        //nothing
    }
}
