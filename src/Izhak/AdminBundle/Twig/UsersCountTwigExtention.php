<?php

namespace Izhak\AdminBundle\Twig;

use Doctrine\ORM\EntityManager;
use Izhak\UserBundle\Entity\User;

class UsersCountTwigExtention extends \Twig_Extension
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * UserRepository constructor.
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user_count_extention';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('usersCount', [$this, 'usersCount'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return array
     */
    public function usersCount()
    {
        $repository = $this->em->getRepository('UserBundle:User');
        $users = $repository->findAll();
        $usersCount = count($users);

        return $usersCount;
    }

}