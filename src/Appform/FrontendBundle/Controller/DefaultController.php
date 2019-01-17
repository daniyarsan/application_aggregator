<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\AppUser;
use Appform\FrontendBundle\Entity\Document;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\AppUserType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        /* Init firewall to ban fraud by IP */
        $firewall = $this->get('Firewall');
        $firewall->initFiltering();

        $helper = $this->get('Helper');
        $session = $this->container->get('session');

        $applicant = new Applicant();
        $template = '@AppformFrontend/Default/index.html.twig';
        if ($request->get('type') == 'solid') {
            $template = '@AppformFrontend/Default/jobboard.html.twig';
        }

        /* Get Referrer and set it to session */
        $utm_source = $request->get('utm_source') ? $request->get('utm_source') : false;
        $utm_medium = $request->get('utm_medium') ? $request->get('utm_medium') : false;
        $referer = $utm_source ? $utm_source : '';
        $referer .= $utm_source && $utm_medium ? '-' . $utm_medium : '';

        $session->set('origin', $referer != '' ? $referer : 'Original');
        $session->set('refer_source', $request->headers->get('referer') != '' ? $request->headers->get('referer') : 'Original');

        $token = $helper->getRandomString(21);

        // Count Online Users and Log Visitors
        $counter = $this->get('counter');
        $usersOnline = $counter->count();
        $counter->logVisitor($token);

        $form = $this->createForm(new ApplicantType($helper, $applicant, $referer));
        $data = array(
            'referrer' => $referer,
            'usersOnline' => $usersOnline,
            'form' => $form->createView(),
            'formToken' => $token
        );

        return $this->render($template, $data);
    }

    /**
     * Apply Action.
     *
     * @Route("/apply", name="appform_frontend_apply")
     */
    public function applyAction(Request $request)
    {
        /* Init firewall to ban fraud by IP */
        $firewall = $this->get('Firewall');
        $firewall->initFiltering();
        
        $session = $this->container->get('session');

        $applicant = new Applicant();
        $form = $this->createForm(new ApplicantType($this->get('Helper'), $applicant, null));
        $repository = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant');

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);
            if ($form->isValid()) {
                $applicant = $form->getData();

                /* XHR Validation */
                if ($request->isXmlHttpRequest()) {
                    $response = [];
                    $response[ 'status' ] = false;
                    $response[ 'statusText' ] = 'Applied Successfully';

                    $res = $this->captchaVerify($request->get('g-recaptcha-response'));
                    if (!$res) {
                        $response[ 'status' ] = true;
                        $response[ 'statusText' ] = 'Please add captcha';
                        return new JsonResponse($response);
                    }
                    if ($repository->findOneBy(array('email' => $applicant->getEmail()))) {
                        $response[ 'status' ] = true;
                        $response[ 'statusText' ] = 'Such application already exists in database';
                        return new JsonResponse($response);
                    }

                    if ($applicant->getFirstName() == $applicant->getLastName()) {
                        $response[ 'status' ] = true;
                        $response[ 'statusText' ] = 'First Name and Last Name are simmilar';
                        return new JsonResponse($response);
                    }

                    if ($session->get('origin') == 'Indeed-cpc') {
                        if (in_array($applicant->getPersonalInformation()->getYearsLicenceSp(), [0])) {
                            $response[ 'status' ] = true;
                            $response[ 'statusText' ] = 'We are sorry but at this time we cannot accept your information.
                            The facilities of the HCEN Client Staffing Agencies require 2 years’
                            minimum experience in your chosen specialty. Thank you.';
                            return new JsonResponse($response);
                        }
                    } else {
                        if (in_array($applicant->getPersonalInformation()->getYearsLicenceSp(), [0, 1])) {
                            $response[ 'status' ] = true;
                            $response[ 'statusText' ] = 'We are sorry but at this time we cannot accept your information.
                            The facilities of the HCEN Client Staffing Agencies require 2 years’
                            minimum experience in your chosen specialty. Thank you';
                            return new JsonResponse($response);
                        }
                    }
                    if ($repository->findOneByIpCheck($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '::1') {
                        $response[ 'status' ] = true;
                        $response[ 'statusText' ] = 'Bad phone format';
                        return new JsonResponse($response);
                    }

                    $rejectionRepository = $this->getDoctrine()->getRepository('AppformBackendBundle:Rejection');
                    $localRejection = $rejectionRepository->findByVendor($session->get('origin'));
                    if ($localRejection) {
                        foreach ($localRejection as $localRejectionRule) {
                            if (in_array($applicant->getPersonalInformation()->getDiscipline(), $localRejectionRule->getDisciplinesList())
                                && (in_array($applicant->getPersonalInformation()->getSpecialtyPrimary(), $localRejectionRule->getSpecialtiesList()))) {
                                $response[ 'status' ] = true;
                                $response[ 'statusText' ] = $localRejectionRule->getRejectMessage();
                            }
                        }
                    }
                    return new JsonResponse($response);
                }

                if ($repository->findOneBy(array('email' => $applicant->getEmail()))) {
                    $this->get('session')->getFlashBag()->add('error', 'Such application already exists in database');
                    return $this->redirect($this->generateUrl('appform_frontend_homepage'));
                }

                $applicant->setAppReferer($session->get('origin'));
                $session = $this->container->get('session');
                $applicant->setRefUrl($session->get('refer_source'));
                $applicant->setToken($request->get('formToken'));

                /* Removed Unecessary loop */
                $randNum = mt_rand(100000, 999999);
                $randNum = $repository->findOneBy(array('candidateId' => $randNum)) != $randNum ? $randNum : mt_rand(100000, 999999);

                $applicant->setCandidateId($randNum);
                $applicant->setIp($request->getClientIp());
                $personalInfo = $applicant->getPersonalInformation();

                $helper = $this->get('Helper');
                /** Redirect to Specialty fix **/
                $searchString = $personalInfo->getDiscipline() != 5 ? $helper->getDiscipline($personalInfo->getDiscipline()) : $helper->getDiscipline($personalInfo->getDiscipline()) . '+' . $helper->getSpecialty($personalInfo->getSpecialtyPrimary());
                $searchString = strpos($searchString, 'Surgical Tech') !== FALSE ? 'Surgical Tech' : $searchString;

                $location = $helper->getStates($personalInfo->getState());

                $this->get('session')->getFlashBag()->add('searchString', $searchString);
                $this->get('session')->getFlashBag()->add('location', $location);
                /** Redirect to Specialty fix **/

                $filename = "HCEN - {$helper->getDisciplineShort($personalInfo->getDiscipline())}, ";
                if ($personalInfo->getDiscipline() == 5) {
                    $filename .= "{$helper->getSpecialty($personalInfo->getSpecialtyPrimary())}, ";
                }
                $filename .= "{$applicant->getLastName()}, {$applicant->getFirstName()} - {$randNum}";
                $filename = str_replace('/', '-', $filename);
                $personalInfo->setApplicant($applicant);

                /* fix of the hack */
                $personalInfo->setLicenseState(array_diff($personalInfo->getLicenseState(), array(0)));
                $personalInfo->setDesiredAssignementState(array_diff($personalInfo->getDesiredAssignementState(), array(0)));

                /* Phone 1+ removal */
                $personalInfo->setPhone(str_replace('1+', '', $personalInfo->getPhone()));

                if ($document = $applicant->getDocument()) {
                    $document->setApplicant($applicant);
                    $document->setPdf($filename . '.' . 'pdf');
                    $document->setXls($filename . '.' . 'xls');
                } else {
                    $document = new Document();
                    $document->setApplicant($applicant);
                    $document->setPdf($filename . '.' . 'pdf');
                    $document->setXls($filename . '.' . 'xls');
                    $applicant->setDocument($document);
                }

                $document->setFileName($filename);

                $em = $this->getDoctrine()->getManager();
                $em->persist($document);
                $em->persist($personalInfo);
                $em->persist($applicant);
                $em->flush();

                $mgRep = $this->getDoctrine()->getRepository('AppformBackendBundle:Mailgroup');
                $mailPerOrigin = $mgRep->createQueryBuilder('m')
                    ->where('m.originsList LIKE :origin')
                    ->setParameter('origin', '%' . $applicant->getAppReferer() . '%')
                    ->setMaxResults(1)
                    ->getQuery()->getOneOrNullResult();

                $getEmailToSend = $mailPerOrigin ? $mailPerOrigin->getEmail() : false;
                if ($this->sendReport($form, $getEmailToSend)) {
                    $this->get('session')->getFlashBag()->add('message', 'Your application has been sent successfully');
                    // Define if visitor is applied
                    $token = $request->get('formToken');
                    $visitorRepo = $em->getRepository('AppformFrontendBundle:Visitor');
                    $recentVisitor = $visitorRepo->getRecentVisitor($token);
                    if ($recentVisitor) {
                        $applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->getApplicantPerToken($token);
                        if ($applicant) {
                            $recentVisitor->setUserId($applicant[ 'id' ]);
                            $recentVisitor->setDiscipline($this->get('Helper')->getDiscipline($applicant[ 'discipline' ]));
                            $em->persist($recentVisitor);
                            $em->flush();
                        }
                    }
                }

                $session->remove('origin');
                return $this->render('AppformFrontendBundle:Default:success.html.twig', array());

            } else {
                // Field error messages
                foreach ($this->getErrorMessages($form) as $field) {
                    foreach ($field as $errorMsg) {
                        if (is_array($errorMsg)) {
                            foreach ($errorMsg as $message) {
                                $this->get('session')->getFlashBag()->add('error', $message);
                            }
                        } else {
                            $this->get('session')->getFlashBag()->add('error', $errorMsg);
                        }
                    }
                }
                return $this->redirect($this->generateUrl('appform_frontend_homepage'));
            }
        }
        return $this->redirect($this->generateUrl('appform_frontend_homepage'));
    }

    /**
     * Counter.
     *
     * @Route("/counter", name="appform_frontend_counter")
     * @Method("POST")
     */
    public function counterAction()
    {
        // Count Online Users
        $counter = $this->get('counter');
        $usersOnline = $counter->count();
        return new Response($usersOnline);
    }

    protected function sendReport(Form $form, $mailPerOrigin = false)
    {
        $applicant = $form->getData();
        $personalInfo = $applicant->getPersonalInformation();
        $helper = $this->get('Helper');


        $fields = $this->generateFormFields();

        // Create new PHPExcel object
        $objPHPExcel = $this->get('phpexcel')->createPHPExcelObject();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("HealthcareTravelerNetwork")
            ->setLastModifiedBy("HealthcareTravelerNetwork")
            ->setTitle("Applicant Data")
            ->setSubject("Applicant Document");

        /* Filling in excel document */
        $forPdf = array();

        foreach ($fields as $key => $value) {
            $metodName = 'get' . $key;
            if (method_exists($applicant, $metodName)) {
                $data = $applicant->$metodName();
                $data = $data ? $data : '';
                if (is_object($data) && get_class($data) == 'Appform\FrontendBundle\Entity\Document') {
                    $data = $data->getPath() ? 'Yes' : 'No';
                }
            } else {
                if (method_exists($personalInfo, $metodName)) {
                    $data = $personalInfo->$metodName();
                    $data = (is_object($data) && get_class($data) == 'DateTime') ? $data->format('F d,Y') : $data;
                    $data = (is_object($data) && get_class($data) == 'Document') ? $data->format('F d,Y') : $data;
                    $data = ($key == 'state') ? $helper->getStates($data) : $data;
                    $data = ($key == 'discipline') ? $helper->getDiscipline($data) : $data;
                    $data = ($key == 'specialtyPrimary') ? $helper->getSpecialty($data) : $data;
                    $data = ($key == 'specialtySecondary') ? $helper->getSpecialty($data) : $data;
                    $data = ($key == 'yearsLicenceSp') ? $helper->getExpYears($data) : $data;
                    $data = ($key == 'yearsLicenceSs') ? $helper->getExpYears($data) : $data;
                    $data = ($key == 'assignementTime') ? $helper->getAssTime($data) : $data;
                    $data = ($key == 'licenseState' || $key == 'desiredAssignementState') ? implode(',', $data) : $data;
                    if ($key == 'isOnAssignement' || $key == 'isExperiencedTraveler') {
                        $data = $data == true ? 'Yes' : 'No';
                    }
                }
            }

            $data = $data ? $data : '';
            $data = is_array($data) ? '' : $data;
            $forPdf[ 'candidateId' ] = $applicant->getCandidateId();
            $forPdf[ 'applyDate' ] = $applicant->getCreated()->format('m/d/Y - H:i');

            $forPdf[ $key ] = $data;

            $alphabet = $this->generateAlphabetic($fields);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($alphabet[ $key ] . '1', $value)
                ->setCellValue($alphabet[ $key ] . '2', $data);
        }

        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'AppformFrontendBundle:Default:pdf.html.twig',
                $forPdf
            ), $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPdf());

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        $objWriter->save($applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getXls());
        $mailPerOrigin = $mailPerOrigin ? $mailPerOrigin : 'moreinfo@healthcaretravelers.com';

        $textBody = $this->renderView('AppformFrontendBundle:Default:email_template.html.twig', array('info' => $forPdf));

        $message = \Swift_Message::newInstance()
            ->setFrom('from@example.com')
            ->setTo('daniyar.san@gmail.com')
            ->addCc($mailPerOrigin)
            ->addCc('HealthCareTravelers@Gmail.com')
            ->setSubject('HCEN new Applicaton from More Info')
            ->setBody($textBody, 'text/html')
            ->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPdf()))
            ->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getXls()));

        if ($applicant->getDocument()->getPath()) {
            $message->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPath()));
        }

        return $this->get('mailer')->send($message);
    }

    private function getErrorMessages(\Symfony\Component\Form\Form $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $template = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();
            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }
            $errors[ $key ] = $template;
        }
        if ($form->count()) {
            foreach ($form as $child) {
                if (!$child->isValid()) {
                    $errors[ $child->getName() ] = $this->getErrorMessages($child);
                }
            }
        }
        return $errors;
    }

    protected function generateFormFields()
    {
        /* Data Generation*/
        $formTitles1 = array('id' => 'Candidate #');
        $formTitles2 = array();
        $form1 = $this->createForm(new ApplicantType($this->get('Helper'), null, null));
        $form2 = $this->createForm(new PersonalInformationType($this->get('Helper')));

        $children1 = $form1->all();
        $children2 = $form2->all();

        foreach ($children1 as $child) {
            $config = $child->getConfig();
            if ($config->getOption("label") != null) {
                $formTitles1[ $child->getName() ] = $config->getOption("label");
            }
        }
        foreach ($children2 as $child) {
            $config = $child->getConfig();
            if ($config->getOption("label") != null) {
                $formTitles2[ $child->getName() ] = $config->getOption("label");
            }
        }
        return array_merge($formTitles1, $formTitles2);
    }

    protected function generateAlphabetic($fields)
    {
        $alphabet = array();
        $alphas = range('A', 'Z');
        $i = 0;
        foreach ($fields as $key => $value) {
            $alphabet[ $key ] = $alphas[ $i ];
            $i++;
        }

        return $alphabet;
    }

    /**
     * Check Ip XHR
     *
     * @Route("/ipcheck", name="appform_frontend_ipcheck")
     * @Method("GET")
     */
    public function checkIpAction(Request $request) {
        $response = [];
        $response[ 'status' ] = false;
        $response[ 'message' ] = 'This is not an Ajax request';

        if ($request->isXmlHttpRequest()) {

            $repository = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant');

            if (1) {
                $response[ 'status' ] = true;
                $response[ 'statusText' ] = 'Such application already exists in database';
            }
        }
        return new JsonResponse($response);
    }

    /**
     * @param $recaptcha
     * @return mixed
     * Get success response from recaptcha and return it to controller
     */
    public function captchaVerify($recaptcha){
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret"=>"6LfhV4gUAAAAAHWwdP91m0uM9F4HMZ1HYHitg2My","response"=>$recaptcha));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data->success;
    }

}
