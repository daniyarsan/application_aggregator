<?php

namespace Appform\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appform\BackendBundle\Entity\Rejection;
use Appform\BackendBundle\Form\RejectionType;

/**
 * Rejection controller.
 *
 * @Route("/reject")
 */
class RejectionController extends Controller
{
    /**
     * Lists all Rejection entities.
     *
     * @Route("/", name="reject")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppformBackendBundle:Rejection')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Rejection entity.
     *
     * @Route("/", name="reject_create")
     * @Method("POST")
     * @Template("AppformBackendBundle:Rejection:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Rejection();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('reject'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Rejection entity.
     *
     * @Route("/new", name="reject_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Rejection();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Rejection entity.
     *
     * @Route("/{id}/show", name="reject_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppformBackendBundle:Rejection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rejection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Rejection entity.
     *
     * @Route("/{id}/edit", name="reject_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppformBackendBundle:Rejection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rejection entity.');
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
     * Edits an existing Rejection entity.
     *
     * @Route("/{id}", name="reject_update")
     * @Method("PUT")
     * @Template("AppformBackendBundle:Rejection:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppformBackendBundle:Rejection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rejection entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('reject_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Rejection entity.
     *
     * @Route("/{id}/delete", name="reject_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppformBackendBundle:Rejection')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rejection entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('reject'));
    }

    /**
     * Creates a form to edit a Rejection entity.
     *
     * @param Rejection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Rejection $entity)
    {
        $form = $this->createForm(new RejectionType($this->container), $entity, array(
            'action' => $this->generateUrl('reject_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Creates a form to create a Rejection entity.
     *
     * @param Rejection $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Rejection $entity)
    {
        $form = $this->createForm(new RejectionType($this->container), $entity, array(
            'action' => $this->generateUrl('reject_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a form to delete a Rejection entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reject_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
