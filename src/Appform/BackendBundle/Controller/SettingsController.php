<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Entity\Settings;
use Appform\BackendBundle\Form\SettingsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Setting controller.
 *
 * @Route("/settings")
 */
class SettingsController extends Controller
{
    private $defaultSettings = array(
        'webSite',
        'banTool'
    );

    /**
     * Lists all Setting entities.
     *
     * @Route("/", name="setting")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'form' => $this->createEditForm(
                $this->get('hcen.settings'),
                array('specific_settings' => $this->defaultSettings)
            )->createView(),
            'tab' => 'general-settings',
        );
    }

    /**
     * Creates a form to edit a Setting entity.
     *
     * @param Settings $entity The entity
     * @param array $options Form Options
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Settings $entity, $options = array())
    {
        $form = $this->createForm(
            new SettingsType($this->container),
            $entity,
            array_merge(
                array(
                    'action' => $this->generateUrl('setting_update'),
                    'method' => 'PUT',
                ),
                $options
            )
        );

        return $form;
    }

    /**
     * Saves Setting.
     *
     * @Route("/", name="setting_update")
     * @Method("PUT")
     * @Template("AppformBackendBundle:Settings:index.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $this->get('hcen.settings');
        $options = array(
            'specific_settings' => $this->defaultSettings
        );

        $editForm = $this->createEditForm($entity, $options);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            foreach ($entity->getSettings() as $setting) {
                $em->persist($setting);
            }

            $em->flush();
            $this->get('session')->getFlashBag()->add('message', 'The settings were successfully saved.');
        }

        switch ($editForm->getClickedButton()->getName()) {
            case 'saveWebSiteSettings':
                $tab = 'website-settings';
                break;
        }
        return array(
            'form' => $editForm->createView()
        );
    }
}
