<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Form\DisciplineType;
use Appform\BackendBundle\Form\SearchVisitorsType;
use Appform\BackendBundle\Form\SpecialtyType;
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
     * @Route("/edit/{id}", name="specialties_edit")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $discipline = $em->getRepository('AppformFrontendBundle:Specialty')->find($id);

        $form = $this->createEditForm($discipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($discipline);
            $em->flush();

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('specialties');
            }
            return $this->redirect($this->generateUrl('specialties_edit', ['id' => $discipline->getId()]));
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
