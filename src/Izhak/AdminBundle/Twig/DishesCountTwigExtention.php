<?php

namespace Izhak\AdminBundle\Twig;

use Doctrine\ORM\EntityManager;

class DishesCountTwigExtention extends \Twig_Extension
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
        return 'dish_count_extention';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('dishesCount', [$this, 'dishesCount'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @return array
     */
    public function dishesCount()
    {
        $repository = $this->em->getRepository('AdminBundle:Dish');
        $dishes = $repository->findAll();
        $dishesCount = count($dishes);

        return $dishesCount;
    }

}