<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Form\DisciplineType;
use Appform\BackendBundle\Form\SearchVisitorsType;
use Appform\FrontendBundle\Entity\Discipline;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Disciplines controller.
 *
 * @Route("/disciplines")
 */
class DisciplinesController extends Controller
{

    public $filename = false;

    /**
     * @Route("/", name="disciplines")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $disciplines = $em->getRepository('AppformFrontendBundle:Discipline')->findAll();

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
     * @Route("/edit/{id}", name="disciplines_edit")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $discipline = $em->getRepository('AppformFrontendBundle:Discipline')->find($id);

        $form = $this->createEditForm($discipline);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($discipline);
            $em->flush();

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('disciplines');
            }
            return $this->redirect($this->generateUrl('disciplines_edit', ['id' => $discipline->getId()]));
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
            new DisciplineType(),
            $entity,
            array(
                'action' => $this->generateUrl('disciplines_edit', ['id' => $entity->getId()]),
                'method' => 'GET',
            )
        );
        return $form;
    }


    /**
     * @Route("/new", name="disciplines_new")
     * @Template()
     */
    public function newAction()
    {
        $discipline = new Discipline();
        $form = $this->createNewForm($discipline);

        return [
            'form' => $form->createView(),
            'discipline' => $discipline
        ];

    }

    /**
     * @Route("/create", name="disciplines_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = new Discipline();
        $form = $this->createNewForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('message', 'The option was successfully saved.');

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('disciplines');
            }
            return $this->redirectToRoute('disciplines_edit', ['id' => $entity->getId()]);
        }
    }

    /**
     * Creates a form to search an Order.
     * @return \Symfony\Component\Form\Form The form
     */
    private function createNewForm($entity)
    {
        $form = $this->createForm(
            new DisciplineType(),
            $entity,
            array(
                'action' => $this->generateUrl('disciplines_create'),
                'method' => 'POST',
            )
        );
        return $form;
    }

}
