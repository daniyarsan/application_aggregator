<?php

namespace Appform\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appform\BackendBundle\Entity\AgencyGroup;
use Appform\BackendBundle\Form\AgencyGroupType;

/**
 * AgencyGroup controller.
 *
 * @Route("/agencygroup")
 */
class AgencyGroupController extends Controller
{

	/**
	 * Lists all AgencyGroup entities.
	 *
	 * @Route("/", name="agencygroup")
	 * @Method("GET")
	 * @Template()
	 */
	public function indexAction()
	{

		$em = $this->getDoctrine()->getManager();

		$entities = $em->getRepository('AppformBackendBundle:AgencyGroup')->findBy(array(), array('name' => 'ASC'));

		return array(
			'entities' => $entities,
		);
	}

	/**
	 * Creates a new AgencyGroup entity.
	 *
	 * @Route("/", name="agencygroup_create")
	 * @Method("POST")
	 * @Template("AppformBackendBundle:AgencyGroup:new.html.twig")
	 */
	public function createAction(Request $request)
	{
		$entity = new AgencyGroup();
		$form = $this->createCreateForm($entity);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();

			return $this->redirect($this->generateUrl('agencygroup'));
		}

		return array(
			'entity' => $entity,
			'form' => $form->createView(),
		);
	}

	/**
	 * Creates a form to create a AgencyGroup entity.
	 *
	 * @param AgencyGroup $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createCreateForm(AgencyGroup $entity)
	{
		$form = $this->createForm(new AgencyGroupType(), $entity, array(
			'action' => $this->generateUrl('agencygroup_create'),
			'method' => 'POST',
		));

		$form->add('submit', 'submit', array('label' => 'Create'));

		return $form;
	}

	/**
	 * Displays a form to create a new AgencyGroup entity.
	 *
	 * @Route("/new", name="agencygroup_new")
	 * @Method("GET")
	 * @Template()
	 */
	public function newAction()
	{
		$entity = new AgencyGroup();
		$form = $this->createCreateForm($entity);

		return array(
			'entity' => $entity,
			'form' => $form->createView(),
		);
	}

	/**
	 * Send an AgencyGroup email.
	 *
	 * @Route("/send-mail/{id}", name="agencygroup_send_mail")
	 * @Method("POST")
	 */
	public function sendMailAction($id, Request $request)
	{
		$data = $request->request->all();

		if ($request->getMethod() == "POST") {
			if (isset($id)) {
				$agencyEmails = [];

				$agencyGroupEntity = $this->getDoctrine()->getManager()->getRepository('AppformBackendBundle:AgencyGroup')->find($id);
				$agencies = $agencyGroupEntity->getAgencies();
				// Get array of Agency Emails
				foreach($agencies as $agency) {
                    $agencyEmails = array_merge($agencyEmails, array_map('trim', explode(',', $agency->getEmail())));
                }

				# Setup the message
				$message = \Swift_Message::newInstance();
				$template = $this->renderView('AppformBackendBundle:Sender:email_agency_send.html.twig', $data['form']);
				$message->setSubject('New Email')
						->setFrom('moreinfo@healthcaretravelers.com')
						->setTo($agencyEmails)
						->setBody($template, 'text/html');

				$result = $this->get('mailer')->send($message);
				if ($request) {
					$this->get('session')->getFlashBag()->add('success', 'Email has been sent');
				} else {
					$this->get('session')->getFlashBag()->add('error', 'Mail doesnt function correctly');
				}
			}
		} else {
			$this->get('session')->getFlashBag()->add('error', 'Request is not valid');
		}
		return $this->redirect($this->generateUrl('agencygroup'));
	}

	/**
	 * Finds and displays a AgencyGroup entity.
	 *
	 * @Route("/{id}", name="agencygroup_show")
	 * @Method("GET")
	 * @Template()
	 */
	public function showAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('AppformBackendBundle:AgencyGroup')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find AgencyGroup entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Displays a form to edit an existing AgencyGroup entity.
	 *
	 * @Route("/{id}/edit", name="agencygroup_edit")
	 * @Method("GET")
	 * @Template()
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('AppformBackendBundle:AgencyGroup')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find AgencyGroup entity.');
		}

		$editForm = $this->createEditForm($entity);
		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Creates a form to edit a AgencyGroup entity.
	 *
	 * @param AgencyGroup $entity The entity
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createEditForm(AgencyGroup $entity)
	{
		$form = $this->createForm(new AgencyGroupType(), $entity, array(
			'action' => $this->generateUrl('agencygroup_update', array('id' => $entity->getId())),
			'method' => 'PUT',
		));

		$form->add('submit', 'submit', array('label' => 'Update'));

		return $form;
	}

	/**
	 * Edits an existing AgencyGroup entity.
	 *
	 * @Route("/{id}", name="agencygroup_update")
	 * @Method("PUT")
	 * @Template("AppformBackendBundle:AgencyGroup:edit.html.twig")
	 */
	public function updateAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('AppformBackendBundle:AgencyGroup')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find AgencyGroup entity.');
		}

		$deleteForm = $this->createDeleteForm($id);
		$editForm = $this->createEditForm($entity);
		$editForm->handleRequest($request);

		if ($editForm->isValid()) {
			$em->flush();

			return $this->redirect($this->generateUrl('agencygroup'));
		}

		return array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Deletes a AgencyGroup entity.
	 *
	 * @Route("/{id}", name="agencygroup_delete")
	 * @Method("DELETE")
	 */
	public function deleteAction(Request $request, $id)
	{
		$form = $this->createDeleteForm($id);
		$form->handleRequest($request);

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('AppformBackendBundle:AgencyGroup')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find AgencyGroup entity.');
		}

		$em->remove($entity);
		$em->flush();

		return $this->redirect($this->generateUrl('agencygroup'));
	}


	/**
	 * Creates a form to delete a AgencyGroup entity by id.
	 *
	 * @param mixed $id The entity id
	 *
	 * @return \Symfony\Component\Form\Form The form
	 */
	private function createDeleteForm($id)
	{
		return $this->createFormBuilder()
			->setAction($this->generateUrl('agencygroup_delete', array('id' => $id)))
			->setMethod('DELETE')
			->add('submit', 'submit', array('label' => 'Delete'))
			->getForm();
	}
}
