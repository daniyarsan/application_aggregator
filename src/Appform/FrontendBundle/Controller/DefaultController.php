<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\Discipline;
use Appform\FrontendBundle\Entity\Document;
use Appform\FrontendBundle\Entity\Specialty;
use Appform\FrontendBundle\Extensions\Helper;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\PersonalInformationType;
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
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $agency = $request->get('utm_source');

        if (!empty($request->get('utm_medium'))) {
            $agency .= '-' . $request->get('utm_medium');
        }
        $session = $this->container->get('session');
        $session->set('origin', $agency);

        if($this->getRequest()->getHost() != 'app.healthcaretravelers.com') {
            $refString = $agency ? $this->getRequest()->getHost() . '_' . $agency : $this->getRequest()->getHost();
            return $this->forward('Appform\FrontendBundle\Controller\DefaultController::formAction', [
                'agency' => $refString
            ]);
        }

        // jG8 conversion tracking
        $jg8 = $request->get('jg_clickid') ? $request->get('jg_clickid') : false;
        $lensa = $request->get('click_id') ? $request->get('click_id') : false;

        // Count Online Users and Log Visitors
        $token = $this->get('counter')->init();

        $form = $this->createAppForm(new Applicant(), $agency);
        return $this->render('@AppformFrontend/Default/index.html.twig', array(
            'form' => $form->createView(),
            'formToken' => $token,
            'agency' => $agency,
            'jg8' => $jg8,
            'lensa' => $lensa,
        ));
    }

    /**
     * Apply form.
     *
     * @Route("/landing", name="appform_frontend_landing")
     * @Method("GET")
     */
    public function landingAction(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header_remove("X-Frame-Options");

        $agency = $request->get('utm_source');
        if (!empty($request->get('utm_medium'))) {
            $agency .= '-' . $request->get('utm_medium');
        }
        $session = $this->container->get('session');
        $session->set('origin', $agency);

        // Count Online Users and Log Visitors
        $token = $this->get('counter')->init();

        // jG8 conversion tracking
        $jg8 = $request->get('jg_clickid') ? $request->get('jg_clickid')  : false;
        $lensa = $request->get('click_id') ? $request->get('click_id') : false;

        $form = $this->createAppForm(new Applicant(), $agency);
        return $this->render('@AppformFrontend/Default/landing.html.twig', array(
            'form' => $form->createView(),
            'formToken' => $token,
            'agency' => $agency
        ));
    }

    /**
     * Form for particular agency.
     *
     * @Route("/form/{agency}", name="appform_frontend_form")
     * @Method("GET")
     */
    public function formAction($agency, Request $request)
    {
        $this->get('Firewall')->initFiltering();

        if (!empty($request->get('utm_source'))) {
            $agency .= '_' . $request->get('utm_source');
        }
        $session = $this->container->get('session');
        $session->set('origin', $agency);

        // Count Online Users and Log Visitors
        $token = $this->get('counter')->init();

        // jG8 conversion tracking
        $jg8 = $request->get('jg_clickid') ? $request->get('jg_clickid') : false;
        $lensa = $request->get('click_id') ? $request->get('click_id')  : false;

        $form = $this->createAppForm(new Applicant(), $agency);
        return $this->render('@AppformFrontend/Default/index.html.twig', array(
            'form' => $form->createView(),
            'formToken' => $token,
            'agency' => $agency,
            'jg8' => $jg8,
            'lensa' => $lensa,
        ));
    }


    /**
     * Apply Action.
     *
     * @Route("/apply", name="appform_frontend_apply")
     */
    public function applyAction(Request $request)
    {
        $template = $request->get('template') ? $request->get('template') : '@AppformFrontend/Default/index.html.twig';

        $this->get('Firewall')->initFiltering();
        $em = $this->getDoctrine()->getManager();
        $visitorLogger = $this->get('visitor_logger');
        $agency = $request->get('agency');

        $applicant = new Applicant();

        $form = $this->createAppForm($applicant, $agency);
        $form->submit($request);


        if ($agency == 'ZR_nurse' && substr(strrchr($form->get('email')->getData(), "@"), 1) == 'yahoo.com') {
            $form->addError(new FormError('Bad phone format'));
        }

        /* Years of experience rejection */
        if (in_array($form->get('personalInformation')->get('yearsLicenceSp')->getData(), [0, 1])) {
            $form->addError(new FormError('We are sorry but at this time we cannot accept your information.
                            The facilities of the HCEN Client Staffing Agencies require 2 years’
                            minimum experience in your chosen specialty. Thank you'));
        }

        /* Ban duplicated ips */
        /*        $banEnabled = $this->get('hcen.settings')->getWebSite()->getBanDuplicatedIp();
                if ($banEnabled && $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->findOneByIpCheck($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '::1') {
                    $form->addError(new FormError('Bad phone format'));
                }*/

        /* fake rejection */
        if ($form->get('personalInformation')->get('discipline')->getData() == 6) {
            if (!in_array($form->get('personalInformation')->get('state')->getData(), $form->get('personalInformation')->get('licenseState')->getData())) {
                $form->addError(new FormError('500 Internal Server Error'));
            }
        }

        if (in_array($form->get('personalInformation')->get('discipline')->getData(), [10, 12, 16])) {
            if (!in_array($form->get('personalInformation')->get('state')->getData(), $form->get('personalInformation')->get('licenseState')->getData())) {
                $form->addError(new FormError('Ip Conflict Error'));
            }
        }

        /*        if (!$this->get('email_checker')->validate($form->get('email')->getData())) {
                    $form->addError(new FormError('Unable to process your form at this time 855.335.9924'));
                }*/

        /* Main rejection rule */
        $rejectionRepository = $this->getDoctrine()->getRepository('AppformBackendBundle:Rejection');
        $sourcingHasDiscipline = $rejectionRepository->sourcingHasDiscipline($agency, $form->get('personalInformation')->get('discipline')->getData());
        $sourcingHasSpecialty = $rejectionRepository->sourcingHasSpecialty($agency, $form->get('personalInformation')->get('specialtyPrimary')->getData());
        if ($sourcingHasDiscipline) {
            $form->addError(new FormError($sourcingHasDiscipline->getRejectMessage()));
        } else if ($sourcingHasSpecialty) {
            $form->addError(new FormError($sourcingHasSpecialty->getRejectMessage()));
        }

        if (empty($form->get('personalInformation')->get('licenseState')->getData())) {
            $form->addError(new FormError('Missing "License State" field'));
        }
        if (empty($form->get('personalInformation')->get('desiredAssignementState')->getData())) {
            $form->addError(new FormError('Missing "Desired Assignment State" field'));
        }

        if ($form->isValid()) {
            $applicant = $form->getData();
            $applicant->setAppReferer($agency);
            $applicant->setRefUrl($request->headers->get('referer'));
            $applicant->setToken($request->get('formToken'));
            $headers = [];
            foreach ($request->headers as $key => $header) {
                if($key != 'accept-encoding') {
                    $headers[] = ucwords($key, '-') . ': ' . implode(', ', $header);
                }
            }
            $applicant->setHeadersMeta($headers);
            $randNum = mt_rand(100000, 999999);
            $applicant->setCandidateId($randNum);
            $applicant->setIp($request->getClientIp());
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

            $visitorLogger->logVisitor($applicant);

            $jg8Tracking = $request->get('jg8') ? $request->get('jg8') : false;
            $lensaTracking = $request->get('lensa') ? $request->get('lensa') : false;

            /* JobG8 Tracking */
            $helper = $this->container->get('helper');
            if ($jg8Tracking) {
                $helper->get('https://www.jobg8.com/Conversion.aspx?id=k%2fmfRKumzD%2fE8HzBwewI7wa&jg_type=1&jg_clickid=' . $jg8Tracking);
            }
            if ($lensaTracking) {
                $helper->post('https://lensa.com/track/application/v1/', [
                    'click_id' => $lensaTracking
                ]);
            }

            return $this->redirect($this->generateUrl(
                'appform_frontend_success',
                [
                    'agency' => $agency,
                    'discipline' => $personalInfo->getDiscipline(),
                    'specialty' => $personalInfo->getSpecialtyPrimary(),
                    'state' => $personalInfo->getState()
                ]
            ));
        }

        return $this->render($template, array(
            'form' => $form->createView(),
            'formToken' => $request->get('formToken'),
            'agency' => $agency
        ));
    }

    /**
     *  From Success Action.
     *
     * @Route("/success", name="appform_frontend_success")
     * @Method("GET")
     */
    public function successAction(Request $request)
    {
        $helper = $this->container->get('helper');
        $redirectUrl = Helper::JFN_REDIRECT_URL;
        $params = [];
        $disciplineId = $request->get('discipline');
        $specialtyId = $request->get('specialty');
        $state = $request->get('state');

        $disciplineEntity = $this->getDoctrine()->getManager()->getRepository('AppformFrontendBundle:Discipline')->findOneById($disciplineId);
        $specialtyEntity = $this->getDoctrine()->getManager()->getRepository('AppformFrontendBundle:Specialty')->findOneById($specialtyId);
        if (!empty($disciplineEntity->getSjbId())) {
            $params[] = 'JobCategory[multi_like][]=' . $disciplineEntity->getSjbId();
        }
        if ($state) {
            $params[] = 'Location_State[equal]=' . $helper->getStates($state);
        }

        if(in_array($disciplineEntity->getName(), $helper->JFNDisciplines())) {
            if (!empty($specialtyEntity->getSjbId())) {
                $params[] = 'id_Job_Specialty[multi_like][]=' . $specialtyEntity->getSjbId();
            }
            $redirectUrl .= !empty($params) ?  '/jobs?' . implode('&', $params) : '';
        } else {
            $redirectUrl = Helper::JFA_REDIRECT_URL;
            $redirectUrl .= !empty($params) ?  '/jobs?' . implode('&', $params) : '';
        }

        $data['redirectUrl'] = $redirectUrl;

        return $this->render('@AppformFrontend/Default/success.html.twig', $data);
    }

    /**
     *  From Validate Action.
     *
     * @Route("/validate/{type}", name="appform_frontend_form_validate")
     * @Method("POST")
     */
    public function validateAction($type, Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header_remove("X-Frame-Options");

        $response = [];
        $response[ 'status' ] = true;

        if ($request->isXmlHttpRequest()) {
            $agency = $request->get('agency');
            $form = $this->createAppForm(new Applicant(), $agency);
            $form->submit($request);

            $rejectionRepository = $this->getDoctrine()->getRepository('AppformBackendBundle:Rejection');

            switch ($type) {
                case 'discipline' :
                    $sourcingHasDiscipline = $rejectionRepository->sourcingHasDiscipline($agency, $form->get('personalInformation')->get('discipline')->getData());
                    if ($sourcingHasDiscipline) {
                        $response[ 'status' ] = false;
                        $response[ 'message' ] = $sourcingHasDiscipline->getRejectMessage();
                    }
                    break;
                case 'specialty' :
                    $sourcingHasSpecialty = $rejectionRepository->sourcingHasSpecialty($agency, $form->get('personalInformation')->get('specialtyPrimary')->getData());
                    if ($sourcingHasSpecialty) {
                        $response[ 'status' ] = false;
                        $response[ 'message' ] = $sourcingHasSpecialty->getRejectMessage();
                    }
                    break;

                case 'email' :
                    $email = $form->get('email')->getData();
                    $isValid = $this->get('email_checker')->validate($email);
                    if (!$isValid) {
                        $response[ 'status' ] = false;
                        $response[ 'message' ] = 'Email is not valid. Please enter valid email.';
                    }
                    break;
            }
        }

        return new JsonResponse($response);
    }

    /**
     * List of specialties per disciplines and agency.
     *
     * @Route("/specialties-list", name="appform_frontend_specialtiesList")
     * @Method("GET")
     */
    public function specialtiesListAction(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header_remove("X-Frame-Options");

        $response = array();
        $disciplineId = $request->get('discipline');
        $agency = $request->get('agency');

        $em = $this->getDoctrine()->getManager();
        $disciplineEntity = $em->getRepository('AppformFrontendBundle:Discipline')->findOneById($disciplineId);

        if (!$disciplineEntity) {
            $response = array('error' => 'Not found exception.');
            return new JsonResponse($response);
        }

        $specialtiesList = $em->getRepository('AppformFrontendBundle:Specialty')->getSpecialtiesListByDisciplines([$disciplineEntity->getId()], $agency);

        if (empty($specialtiesList)) {
            $specialtiesList = $em->getRepository('AppformFrontendBundle:Specialty')->getSpecialtiesListByTypeAgency($disciplineEntity->getType(), $agency);
        }

        foreach ($specialtiesList as $specialty) {
            $response[] = array(
                "id" => $specialty['id'],
                "name" => $specialty['name']
            );
        }
        return new JsonResponse($response);
    }

    private function createAppForm(Applicant $entity, $agency)
    {
        $form = $this->createForm(new ApplicantType($this->container, $this->getDoctrine()->getManager(), $agency), $entity);
        return $form;
    }

    /**
     * Page form.
     *
     * @Route("/main", name="appform_frontend_main")
     * @Method("GET")
     */
    public function mainAction(Request $request)
    {
        $agency = $request->get('utm_source');

        if (!empty($request->get('utm_medium'))) {
            $agency .= '-' . $request->get('utm_medium');
        }
        $session = $this->container->get('session');
        $session->set('origin', $agency);

        if($this->getRequest()->getHost() != 'app.healthcaretravelers.com') {
            $refString = $agency ? $this->getRequest()->getHost() . '_' . $agency : $this->getRequest()->getHost();
            return $this->forward('Appform\FrontendBundle\Controller\DefaultController::formAction', [
                'agency' => $refString
            ]);
        }

        // jG8 conversion tracking
        $jg8 = $request->get('jg_clickid') ? $request->get('jg_clickid') : false;
        $lensa = $request->get('click_id') ? $request->get('click_id') : false;

        // Count Online Users and Log Visitors
        $token = $this->get('counter')->init();

        $form = $this->createAppForm(new Applicant(), $agency);
        return $this->render('@AppformFrontend/Default/main.html.twig', array(
            'form' => $form->createView(),
            'formToken' => $token,
            'agency' => $agency,
            'jg8' => $jg8,
            'lensa' => $lensa,
        ));
    }

}
