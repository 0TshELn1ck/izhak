<?php

namespace Izhak\AdminBundle\Controller;

use Izhak\AdminBundle\Entity\Dish;
use Izhak\AdminBundle\Form\DishType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DishController
 * @package Izhak\AdminBundle\Controller
 * @Route("/admin/dish")
 */
class DishController extends Controller
{

    /**
     * @param Dish $dish
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Dish $dish)
    {

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_dish_delete', ['id' => $dish->getId()]))
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
     * @Route("/delete/{id}", name="admin_dish_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $dish = $em->getRepository('AdminBundle:Dish')->findOneBy(['id' => $id]);
            if (!$dish) {
                throw $this->createNotFoundException('Unable to find Dish entity.');
            }
            $em->remove($dish);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Страву "' . $dish->getName() . '" видалено!');
        }

        return $this->redirect($this->generateUrl('admin_dish_list'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/edit/{id}", name="admin_dish_edit")
     * @Template("@Admin/Dish/new.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $dish = $em->getRepository('AdminBundle:Dish')->findOneBy(['id' => $id]);
        $editForm = $this->createForm(DishType::class, $dish);
        $editForm->handleRequest($request);
        if (!$dish) {
            throw $this->createNotFoundException('Unable to find Dish entity.');
        }
        if ($editForm->isValid()) {
            $em->persist($dish);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Страву "' . $dish->getName() . '" відредаговано!');

            return $this->redirect($this->generateUrl('admin_dish_list'));
        }

        return ['dishForm' => $editForm->createView()];

    }

    /**
     * @param Request $request
     * @return array
     * @Route("/list", name="admin_dish_list")
     * @Template()
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dishes = $em->getRepository('AdminBundle:Dish')->findAll();
        if (!$dishes) {
            throw $this->createNotFoundException('Unable to find Dish entity.');
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($dishes, $request->query->getInt('page', 1), 20);

        $deleteForm = [];
        foreach ($dishes as $entity) {
            $deleteForm[$entity->getId()] = $this->createDeleteForm($entity)->createView();
        }

        return ['dishes' => $pagination, 'deleteForm' => $deleteForm];
    }

    /**
     * @Route("/new", name="admin_dish_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $dish = new Dish();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(DishType::class, $dish);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($dish);
                $em->flush();

                return $this->redirectToRoute('admin_dish_list');
            }
        }

        return ['dishForm' => $form->createView()];
    }

    /**
     * @param $id
     * @return array
     * @Route("/show/{id}", name="admin_dish_show")
     * @Template()
     */
    public function showAction($id)
    {
        if ($id) {
            $em = $this->getDoctrine()->getManager();
            $dish = $em->getRepository('AdminBundle:Dish')->find($id);
            if (!$dish) {
                throw $this->createNotFoundException('Unable to find Dish.');
            }

            return ['dish' => $dish];
        }

        return [];
    }

}
