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

    /**
     * Contact form.
     *
     * @Route("/", name="appform_contact_main")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createContactForm();
        $form->submit($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $sender = $this->container->get('sender');
            $sender->setFromEmail($data['email']);
            $sender->setToEmail('daniyar.san@gmail.com');
            $sender->setParams(['body' => $data['message']]);
            $sender->sendMessage();
            $this->get('session')->getFlashBag()->add('message', 'Message has been sent successfully');
            return $this->render('@AppformFrontend/contact/success.html.twig');
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