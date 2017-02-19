<?php

namespace Izhak\AdminBundle\Controller;

use Izhak\UserBundle\Entity\User;
use Izhak\UserBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package Izhak\AdminBundle\Controller
 * @Route("/admin/user")
 */
class UserController extends Controller
{

    /**
     * Creates a form to delete a User entity.
     * @param User $user The User entity
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(User $user)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, [
                'label' => 'Remove',
                'attr' => ['class' => 'btn btn-xs btn-danger']
            ])
            ->getForm();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/delete/{id}", name="admin_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('UserBundle:User')->findOneBy(['id' => $id]);
            if (!$user) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }
            $em->remove($user);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Користувача "' . $user->getUsername() . '" видалено!');
        }

        return $this->redirect($this->generateUrl('admin_user_list'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/edit/{id}", name="admin_user_edit")
     * @Method({"GET", "POST"})
     * @Template("@Admin/User/new.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->findOneBy(['id' => $id]);
        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $originalPassword = $user->getPassword();
        $editForm = $this->createForm(UserType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $plainPassword = $editForm->get('plainPassword')->getData();
            if (!empty($plainPassword)) {
                //encode the password
                $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
                $tempPassword = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($tempPassword);
            } else {
                $user->setPassword($originalPassword);
            }
            $em->persist($user);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Користувача "' . $user->getUsername() . '" відредаговано!');

            return $this->redirect($this->generateUrl('admin_user_list'));
        }

        return ['userForm' => $editForm->createView()];
    }

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
        if (!$users) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($users, $request->query->getInt('page', 1), 20);

        $deleteForm = [];
        foreach ($users as $entity) {
            $deleteForm[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        }

        return ['users' => $pagination, 'deleteForm' => $deleteForm];
    }

    /**
     * @Route("/new", name="admin_user_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class, $user);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('admin_user_list');
            }
        }

        return ['userForm' => $form->createView()];
    }

    /**
     * @param $id
     * @return array
     * @Route("/show/{id}", name="admin_user_show")
     * @Template()
     */
    public function showAction($id)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('UserBundle:User')->find($id);
            if (!$user) {
                throw $this->createNotFoundException('Unable to find User.');
            }

            return ['user' => $user];
        }

        return [];
    }

}
