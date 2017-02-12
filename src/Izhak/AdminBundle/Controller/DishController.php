<?php

namespace Izhak\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DishController
 * @package Izhak\AdminBundle\Controller
 * @Route("/admin/dish")
 */
class DishController extends Controller
{
    /**
     * @return array
     * @Route("/list", name="admin_dish_list")
     * @Template()
     */
    public function listAction()
    {
        return [];
    }
}
