<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Entity\Tag;
use Appform\BackendBundle\Form\DisciplineType;
use Appform\BackendBundle\Form\SearchVisitorsType;
use Appform\BackendBundle\Form\SpecialtyType;
use Appform\FrontendBundle\Entity\Redirect;
use Appform\FrontendBundle\Entity\Specialty;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Redirects controller.
 *
 * @Route("/specialties")
 */
class SpecialtiesController extends Controller
{

    public $filename = false;

    /**
     * @Route("/", name="specialties")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $disciplines = $em->getRepository('AppformFrontendBundle:Specialty')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = null;
        $pagination = $paginator->paginate(
            $disciplines,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('itemsPerPage', 20)
        );

        return [
            'pagination' => $pagination
        ];
    }

    /**
     * @Route("/new", name="specialties_new")
     * @Template()
     */
    public function newAction()
    {
        $specialty = new Specialty();
        $form = $this->createNewForm($specialty);

        return [
            'form' => $form->createView(),
            'specialty' => $specialty
        ];

    }

    /**
     * @Route("/new", name="specialties_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = new Specialty();
        $form = $this->createNewForm($entity);

        $form->submit($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('message', 'The option was successfully saved.');

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('specialties');
            }
            return $this->redirectToRoute('specialties_edit', ['id' => $entity->getId()]);
        }
    }

    /**
     * Creates a form to search an Order.
     * @return \Symfony\Component\Form\Form The form
     */
    private function createNewForm($entity)
    {
        $form = $this->createForm(
            new SpecialtyType(),
            $entity,
            array(
                'action' => $this->generateUrl('specialties_create'),
                'method' => 'GET',
            )
        );
        return $form;
    }


    /**
     * @Route("/edit/{id}", name="specialties_edit")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $specialty = $em->getRepository('AppformFrontendBundle:Specialty')->find($id);

        $form = $this->createEditForm($specialty);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($specialty);
            $em->flush();

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('specialties');
            }
            return $this->redirect($this->generateUrl('specialties_edit', ['id' => $specialty->getId()]));
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * Creates a form to search an Order.
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm($entity)
    {
        $form = $this->createForm(
            new SpecialtyType(),
            $entity,
            array(
                'action' => $this->generateUrl('specialties_edit', ['id' => $entity->getId()]),
                'method' => 'GET',
            )
        );
        return $form;
    }
}
