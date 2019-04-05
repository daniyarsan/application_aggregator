<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Entity\Tag;
use Appform\BackendBundle\Form\DisciplineType;
use Appform\BackendBundle\Form\RedirectType;
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
 * @Route("/redirects")
 */
class RedirectsController extends Controller
{

    /**
     * @Route("/", name="redirects")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $redirects = $em->getRepository('AppformFrontendBundle:Redirect')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = null;
        $pagination = $paginator->paginate(
            $redirects,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('itemsPerPage', 20)
        );

        return [
            'pagination' => $pagination
        ];
    }

    /**
     * @Route("/edit/{id}", name="redirects_edit")
     * @Template()
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $redirect = $em->getRepository('AppformFrontendBundle:Redirect')->find($id);

        $form = $this->createEditForm($redirect);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($redirect);
            $em->flush();

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('redirects');
            }
            return $this->redirect($this->generateUrl('redirects_edit', ['id' => $redirect->getId()]));
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
            new RedirectType(),
            $entity,
            array(
                'action' => $this->generateUrl('redirects_edit', ['id' => $entity->getId()]),
                'method' => 'POST',
            )
        );
        return $form;
    }

    /**
     * @Route("/new", name="redirects_new")
     * @Template()
     */
    public function newAction()
    {
        $specialty = new Redirect();
        $form = $this->createNewForm($specialty);

        return [
            'form' => $form->createView(),
            'specialty' => $specialty
        ];

    }

    /**
     * @Route("/create", name="redirects_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        $entity = new Redirect();
        $form = $this->createNewForm($entity);

        $form->submit($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('message', 'The option was successfully saved.');

            if ($form->get('saveAndExit')->isClicked()) {
                return $this->redirectToRoute('redirects');
            }
            return $this->redirectToRoute('redirects_edit', ['id' => $entity->getId()]);
        }
    }

    /**
     * Creates a form to search an Order.
     * @return \Symfony\Component\Form\Form The form
     */
    private function createNewForm($entity)
    {
        $form = $this->createForm(
            new RedirectType(),
            $entity,
            array(
                'action' => $this->generateUrl('redirects_create'),
                'method' => 'POST',
            )
        );
        return $form;
    }

}
