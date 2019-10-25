<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\Discipline;
use Appform\FrontendBundle\Entity\Document;
use Appform\FrontendBundle\Entity\Specialty;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * Apply form.
     *
     * @Route("/", name="appform_frontend_homepage")
     * @Template("@AppformFrontend/Default/index.html.twig")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        /* Get Origin */
        $origin = $request->getSession()->get('origin');
        $request->getSession()->set('referrer', $request->headers->get('referer'));

        $form = $this->createAppForm(new Applicant(), $origin);
        return [
            'usersOnline' => $this->get('counter')->getCurrentOnlineVisitors(),
            'form' => $form->createView(),
            'formToken' => $this->get('counter')->init(),
            'agency' => $origin
        ];
    }


    /**
     * Form for particular agency.
     *
     * @Route("/form/{origin}", name="appform_frontend_form")
     * @Template("@AppformFrontend/Default/index.html.twig")
     * @Method("GET")
     */
    public function formAction($origin, Request $request)
    {
        $form = $this->createAppForm(new Applicant(), $origin);
        $request->getSession()->set('referrer', $request->headers->get('referer'));

        return [
            'usersOnline' => $this->get('counter')->getCurrentOnlineVisitors(),
            'form' => $form->createView(),
            'formToken' => $this->get('counter')->init(),
            'agency' => $origin
        ];
    }

    /**
     * Apply Action.
     *
     * @Route("/apply", name="appform_frontend_apply")
     */
    public function applyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $agency = $request->get('agency');

        $form = $this->createAppForm(new Applicant(), $agency);

        $form->submit($request);

        if ($form->isValid()) {
            $applicant = $form->getData();

            $applicant->setAppReferer($agency);
            $applicant->setRefUrl($request->getSession()->get('referrer'));
            $applicant->setToken($request->get('formToken'));
            $applicant->setCandidateId();
            $applicant->setIp($request->getClientIp());
            $applicant->setUserAgent($request->headers->get('User-Agent'));
            $applicant->setCookies($request->cookies->all());

            $personalInfo = $applicant->getPersonalInformation();
            $personalInfo->setApplicant($applicant);

            $filename = $this->get('file_generator')->getFileName($applicant);

            if ($document = $applicant->getDocument()) {
                $document->setApplicant($applicant);
                $document->setPdf($filename);
                $document->setXls($filename);
            } else {
                $document = new Document();
                $document->setApplicant($applicant);
                $document->setPdf($filename);
                $document->setXls($filename);
                $applicant->setDocument($document);
            }
            $document->setFileName($filename);

            $em->persist($document);
            $em->persist($personalInfo);
            $em->persist($applicant);
            $em->flush();

            return $this->redirectToRoute('appform_frontend_success', array('agency' => $agency));
        }

        return $this->render('@AppformFrontend/Default/index.html.twig', array(
            'usersOnline' => $this->get('counter')->getCurrentOnlineVisitors(),
            'form' => $form->createView(),
            'formToken' => $request->get('formToken'),
            'agency' => $agency,
            'formErrors' => $this->get('form_errors')->getFormErrors($form)
        ));
    }

    /**
     *  Success Action.
     *
     * @Route("/success", name="appform_frontend_success")
     * @Method("GET")
     * @Template("@AppformFrontend/Default/success.html.twig")
     */
    public function successAction(Request $request)
    {
        $redirectUrl = 'https://healthcaretravelers.com/jobboard';
        $agency = $request->get('agency');

        $rejectionRule = $this->getDoctrine()->getRepository('AppformBackendBundle:Rejection')->findOneByVendor($agency);

        return [
            'redirectUrl' => $redirectUrl,
            'agency' => $agency,
            'conversionCode' => !empty($rejectionRule) && !$rejectionRule->getManualConversionCheck() ? $rejectionRule->getConversionCode() : ''
        ];
    }


    private function createAppForm(Applicant $entity, $agency)
    {
        $form = $this->createForm(new ApplicantType($this->container, $this->getDoctrine()->getManager(), $agency), $entity);
        return $form;
    }
}
