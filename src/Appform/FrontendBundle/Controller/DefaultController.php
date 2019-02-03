<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\AppUser;
use Appform\FrontendBundle\Entity\Document;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\AppUserType;
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
     * Counter.
     *
     * @Route("/counter", name="appform_frontend_counter")
     * @Method("POST")
     */
    public function counterAction()
    {
        return new Response($this->get('counter')->count());
    }

    /**
     * Apply form.
     *
     * @Route("/", name="appform_frontend_homepage")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $this->get('Firewall')->initFiltering();

        $utm_source = $request->get('utm_source') ? $request->get('utm_source') : false;
        $utm_medium = $request->get('utm_medium') ? $request->get('utm_medium') : false;
        $agency = $utm_source ? $utm_source : '';
        $agency .= $utm_source && $utm_medium ? '-' . $utm_medium : '';

        // Count Online Users and Log Visitors
        $token = $this->get('counter')->init();

        $form = $this->createMultiForm(new Applicant(), $agency);

        return $this->render('@AppformFrontend/Default/index.html.twig', array(
            'usersOnline' => $this->get('counter')->count(),
            'form' => $form->createView(),
            'formToken' => $token,
            'agency' => $agency
        ));
    }

    /**
     * Apply Action.
     *
     * @Route("/apply", name="appform_frontend_apply")
     */
    public function applyAction(Request $request)
    {
        $this->get('Firewall')->initFiltering();

        $applicant = new Applicant();
        $agency = $request->get('agency');
        $helper = $this->get('Helper');

        $form = $this->createMultiForm($applicant, $agency);
        $form->submit($request);

        /* Captcha Checker */
        $captchaVerified = $this->get('util')->captchaVerify($request->get('g-recaptcha-response'));
        if (!$captchaVerified) {
            $form->addError(new FormError('Please add captcha before submit.'));
        }
        /* Years of experience rejection */
        if (in_array($form->get('personalInformation')->get('yearsLicenceSp')->getData(), [0, 1])) {
            $form->addError(new FormError('We are sorry but at this time we cannot accept your information.
                            The facilities of the HCEN Client Staffing Agencies require 2 years’
                            minimum experience in your chosen specialty. Thank you'));
        }

        /* fake rejection */
        if ($form->get('personalInformation')->get('discipline')->getData() == 5) {
            if (!in_array($form->get('personalInformation')->get('state')->getData(), $form->get('personalInformation')->get('licenseState')->getData())) {
                $form->addError(new FormError('Server error 500'));
            }
        }

        /* Main rejection rules */
        $rejectionRepository = $this->getDoctrine()->getRepository('AppformBackendBundle:Rejection');
        $localRejection = $rejectionRepository->findByVendor($agency);
        if ($localRejection) {
            foreach ($localRejection as $localRejectionRule) {
                if (in_array($form->get('personalInformation')->get('discipline')->getData(), $localRejectionRule->getDisciplinesList())
                    || in_array($form->get('personalInformation')->get('specialtyPrimary')->getData(), $localRejectionRule->getSpecialtiesList())) {
                    $form->addError(new FormError($localRejectionRule->getRejectMessage()));
                }
            }
        }

        if ($form->isValid()) {
            $applicant = $form->getData();
            $applicant->setAppReferer($agency);
            $applicant->setRefUrl($request->headers->get('referer'));
            $applicant->setToken($request->get('formToken'));
            $randNum = mt_rand(100000, 999999);
            $applicant->setCandidateId($randNum);
            $applicant->setIp($request->getClientIp());
            $personalInfo = $applicant->getPersonalInformation();

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
            return $this->redirect($this->generateUrl('appform_frontend_success'));
        }

        return $this->render('@AppformFrontend/Default/index.html.twig', array(
            'usersOnline' => $this->get('counter')->count(),
            'form' => $form->createView(),
            'formToken' => $request->get('formToken'),
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

        // Count Online Users and Log Visitors
        $token = $this->get('counter')->init();

        $form = $this->createMultiForm(new Applicant(), $agency);

        return $this->render('@AppformFrontend/Multiform/index.html.twig', array(
            'usersOnline' => $this->get('counter')->count(),
            'form' => $form->createView(),
            'formToken' => $token,
            'agency' => $agency
        ));
    }

    /**
     *  From Apply Action.
     *
     * @Route("/submit", name="appform_frontend_submit")
     * @Method("POST")
     */
    public function submitAction(Request $request)
    {
        $this->get('Firewall')->initFiltering();

        $applicant = new Applicant();
        $agency = $request->get('agency');
        $helper = $this->get('Helper');

        $form = $this->createMultiForm($applicant, $agency);
        $form->submit($request);

        /* Captcha Checker */
        $captchaVerified = $this->get('util')->captchaVerify($request->get('g-recaptcha-response'));
        if (!$captchaVerified) {
            $form->addError(new FormError('Please add captcha before submit.'));
        }
        /* Years of experience rejection */
        if (in_array($form->get('personalInformation')->get('yearsLicenceSp')->getData(), [0, 1])) {
            $form->addError(new FormError('We are sorry but at this time we cannot accept your information.
                            The facilities of the HCEN Client Staffing Agencies require 2 years’
                            minimum experience in your chosen specialty. Thank you'));
        }

        /* fake rejection */
        if ($form->get('personalInformation')->get('discipline')->getData() == 5) {
            if (!in_array($form->get('personalInformation')->get('state')->getData(), $form->get('personalInformation')->get('licenseState')->getData())) {
                $form->addError(new FormError('Server error 500'));
            }
        }

        /* Main rejection rules */
        $rejectionRepository = $this->getDoctrine()->getRepository('AppformBackendBundle:Rejection');
        $localRejection = $rejectionRepository->findByVendor($agency);
        if ($localRejection) {
            foreach ($localRejection as $localRejectionRule) {
                if (in_array($form->get('personalInformation')->get('discipline')->getData(), $localRejectionRule->getDisciplinesList())
                    || in_array($form->get('personalInformation')->get('specialtyPrimary')->getData(), $localRejectionRule->getSpecialtiesList())) {
                    $form->addError(new FormError($localRejectionRule->getRejectMessage()));
                }
            }
        }

        if ($form->isValid()) {
            $applicant = $form->getData();
            $applicant->setAppReferer($agency);
            $applicant->setRefUrl($request->headers->get('referer'));
            $applicant->setToken($request->get('formToken'));
            $randNum = mt_rand(100000, 999999);
            $applicant->setCandidateId($randNum);
            $applicant->setIp($request->getClientIp());
            $personalInfo = $applicant->getPersonalInformation();

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
            return $this->redirect($this->generateUrl('appform_frontend_form_success'));
        }

        return $this->render('@AppformFrontend/Multiform/index.html.twig', array(
            'usersOnline' => $this->get('counter')->count(),
            'form' => $form->createView(),
            'formToken' => $request->get('formToken'),
            'agency' => $agency
        ));
    }

    /**
     *  From Apply Action.
     *
     * @Route("/success", name="appform_frontend_success")
     * @Method("GET")
     */
    public function successAction()
    {
        return $this->render('@AppformFrontend/Default/success.html.twig', array());
    }

    /**
     *  From Apply Action.
     *
     * @Route("/form-success", name="appform_frontend_form_success")
     * @Method("GET")
     */
    public function formSuccessAction()
    {
        return $this->render('@AppformFrontend/Multiform/success.html.twig', array());
    }

    /**
     *  From Apply Action.
     *
     * @Route("/validate", name="appform_frontend_form_validate")
     * @Method("POST")
     */
    public function validateAction(Request $request)
    {
        $response = [];
        $response[ 'status' ] = true;

        if ($request->isXmlHttpRequest()) {
            $agency = $request->get('agency');
            $form = $this->createMultiForm(new Applicant(), $agency);
            $form->submit($request);

            /* Main rejection rules */
            $rejectionRepository = $this->getDoctrine()->getRepository('AppformBackendBundle:Rejection');
            $localRejection = $rejectionRepository->findByVendor($agency);
            if ($localRejection) {
                foreach ($localRejection as $localRejectionRule) {
                    if (in_array($form->get('personalInformation')->get('discipline')->getData(), $localRejectionRule->getDisciplinesList())
                        || in_array($form->get('personalInformation')->get('specialtyPrimary')->getData(), $localRejectionRule->getSpecialtiesList())) {
                        $response[ 'status' ] = false;
                        $response[ 'message' ] = $localRejectionRule->getRejectMessage();
                    }
                }
            }
        }

        return new JsonResponse($response);
    }

    private function createMultiForm(Applicant $entity, $agency)
    {
        $form = $this->createForm(new ApplicantType($this->container, $agency), $entity);
        return $form;
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

            $alphabet = $helper->generateAlphabetic($fields);

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
        $form1 = $this->createForm(new ApplicantType($this->container, null));
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


}
