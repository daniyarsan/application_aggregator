<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contact controller.
 *
 * @Route("/contact")
 */
class ContactController extends Controller
{

    protected $emailSet;

    const ACCOUNT_EMAIL = 'mary.crawford@healthcaretravelers.com';
    const WEBMASTER_EMAIL = 'daniyar.san@gmail.com';
    const MAIN_EMAIL = 'mike@healthcaretravelers.com';

    public function __construct()
    {
        $this->emailSet = [
            'Administrator' => self::MAIN_EMAIL,
            'Accounting' => self::ACCOUNT_EMAIL,
            'Recruitment' => self::MAIN_EMAIL,
            'Services Information' => self::MAIN_EMAIL,
            'Webmaster' => self::WEBMASTER_EMAIL
        ];
    }
    /**
     * Contact form.
     *
     * @Route("/", name="appform_contact_main")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createContactForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $sender = $this->container->get('sender');
            $sender->setTemplateName('@AppformFrontend/contact/email.html.twig');
            $sender->setFromEmail($data['email']);
            $sender->setToEmail($this->emailSet[$data['department']]);
            $sender->setParams([
                'message' => $data['message'],
                'subject' => 'Contact form request to ' . $data['department']
            ]);
            $sender->sendMessage();

            $this->get('session')->getFlashBag()->add('message', 'Message has been sent successfully');

            return $this->redirect($this->generateUrl('appform_contact_success'));
        }

        return $this->render('@AppformFrontend/contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Contact form success.
     *
     * @Route("/success", name="appform_contact_success")
     */
    public function successAction()
    {
        return $this->render('@AppformFrontend/contact/success.html.twig');
    }

    public function createContactForm()
    {
        return $this->createForm(new ContactType());
    }
}