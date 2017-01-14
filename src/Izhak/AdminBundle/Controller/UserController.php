<?php

namespace Izhak\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package Izhak\AdminBundle\Controller
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return array
     * @Route("/list", name="admin_user_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($users, $request->query->getInt('page', 1), 20);

        return ['users' => $pagination];
    }
}
