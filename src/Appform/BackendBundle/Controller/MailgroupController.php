<?php

namespace Appform\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appform\BackendBundle\Entity\Mailgroup;
use Appform\BackendBundle\Form\MailgroupType;

/**
 * Mailgroup controller.
 *
 * @Route("/mailgroup")
 */
class MailgroupController extends Controller
{

    /**
     * Lists all Mailgroup entities.
     *
     * @Route("/", name="mailgroup")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppformBackendBundle:Mailgroup')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Mailgroup entity.
     *
     * @Route("/", name="mailgroup_create")
     * @Method("POST")
     * @Template("AppformBackendBundle:Mailgroup:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Mailgroup();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('mailgroup', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Mailgroup entity.
     *
     * @param Mailgroup $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Mailgroup $entity)
    {
        $form = $this->createForm(new MailgroupType($this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )), $entity, array(
            'action' => $this->generateUrl('mailgroup_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Mailgroup entity.
     *
     * @Route("/new", name="mailgroup_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Mailgroup();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Mailgroup entity.
     *
     * @Route("/{id}/edit", name="mailgroup_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppformBackendBundle:Mailgroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mailgroup entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Mailgroup entity.
    *
    * @param Mailgroup $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Mailgroup $entity)
    {
        $form = $this->createForm(new MailgroupType($this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )), $entity, array(
            'action' => $this->generateUrl('mailgroup_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Mailgroup entity.
     *
     * @Route("/{id}", name="mailgroup_update")
     * @Method("PUT")
     * @Template("AppformBackendBundle:Mailgroup:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppformBackendBundle:Mailgroup')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mailgroup entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('mailgroup_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a Mailgroup entity.
     *
     * @Route("/{id}", name="mailgroup_delete")
     * @Method("GET")
     */
    public function deleteAction(Request $request, $id)
    {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppformBackendBundle:Mailgroup')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Mailgroup entity.');
            }

            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('mailgroup'));
    }
}
