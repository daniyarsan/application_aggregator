<?php

namespace Appform\BackendBundle\Controller;

use Doctrine\ORM\Query\QueryException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appform\BackendBundle\Entity\Campaign;
use Appform\BackendBundle\Form\CampaignType;

/**
 * Campaign controller.
 *
 * @Route("/campaign")
 */
class CampaignController extends Controller
{

    /**
     * Lists all Campaign entities.
     *
     * @Route("/", name="campaign")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
	    $queryBuilder = $em->getRepository('AppformBackendBundle:Campaign')->findAll();

	    // Pagination
	    $paginator = $this->get('knp_paginator');
	    $pagination = null;
	    $paginatorOptions = array(
		    'defaultSortFieldName' => 'a.id',
		    'defaultSortDirection' => 'desc');
	    try {
		    $pagination = $paginator->paginate(
			    $queryBuilder,
			    $this->get('request')->query->get('page', 1),
			    isset($data['show_all']) && $data['show_all'] == 1 ? count($queryBuilder->getQuery()->getArrayResult()) : $this->get('request')->query->get('itemsPerPage', 20),
			    $paginatorOptions
		    );
	    } catch (QueryException $ex) {
		    $pagination = $paginator->paginate(
			    $queryBuilder,
			    1,
			    isset($data['show_all']) && $data['show_all'] == 1 ? count($queryBuilder->getQuery()->getArrayResult()) : $this->get('request')->query->get('itemsPerPage', 20),
			    $paginatorOptions
		    );
	    }
	    // End Pagination

        return array(
	        'pagination' => $pagination
        );
    }
    /**
     * Creates a new Campaign entity.
     *
     * @Route("/", name="campaign_create")
     * @Method("POST")
     * @Template("AppformBackendBundle:Campaign:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Campaign();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $applicant = $request->get('appform_backendbundle_campaign');
        $entity->setApplicant($applicant['applicant']);
        $campaignName = str_replace('.pdf', '', $em->getRepository('AppformFrontendBundle:Applicant')->find($applicant['applicant'])->getDocument()->getPdf());
        $entity->setName($campaignName);

        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('campaign'));
    }

    /**
     * Creates a form to create a Campaign entity.
     *
     * @param Campaign $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Campaign $entity)
    {
        $form = $this->createForm(new CampaignType(), $entity, array(
            'action' => $this->generateUrl('campaign_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Campaign entity.
     *
     * @Route("/new", name="campaign_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Campaign();
        $form   = $this->createCreateForm($entity);
	    $form->get('subject')->setData($this->get('hcen.settings')->getWebSite()->getSubject());
	    return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Campaign entity.
     *
     * @Route("/{id}", name="campaign_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppformBackendBundle:Campaign')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Campaign entity.
     *
     * @Route("/{id}/edit", name="campaign_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppformBackendBundle:Campaign')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Campaign entity.
    *
    * @param Campaign $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Campaign $entity)
    {
        $form = $this->createForm(new CampaignType(), $entity, array(
            'action' => $this->generateUrl('campaign_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Campaign entity.
     *
     * @Route("/{id}", name="campaign_update")
     * @Method("PUT")
     * @Template("AppformBackendBundle:Campaign:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppformBackendBundle:Campaign')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('campaign_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Campaign entity.
     *
     * @Route("/{id}", name="campaign_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppformBackendBundle:Campaign')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Campaign entity.');
            }

            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('campaign'));
    }

    /**
     * Deletes a Campaign entity.
     *
     * @Route("/{id}", name="campaign_clone")
     * @Method("POST")
     */
    public function cloneAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppformBackendBundle:Campaign')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Campaign entity.');
        }

        $new_entity = clone $entity;
        $new_entity->setIspublished(0);
        $new_entity->setPublishdate(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($new_entity);
        $em->flush();
        $this->get('session')->getFlashBag()->add('message', 'Campaign has been cloned successfully');

        return $this->redirect($this->generateUrl('campaign'));
    }

    /**
     * Creates a form to delete a Campaign entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('campaign_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
