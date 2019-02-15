<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\Discipline;
use Appform\FrontendBundle\Entity\Document;
use Appform\FrontendBundle\Entity\Specialty;
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
        $session = $this->container->get('session');
        $session->set('origin', $agency);

        // Count Online Users and Log Visitors
        $token = $this->get('counter')->init();

        $form = $this->createAppForm(new Applicant(), $agency);
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

        $em = $this->getDoctrine()->getManager();
        $visitorLogger = $this->get('visitor_logger');
        $agency = $request->get('agency');
        $helper = $this->get('Helper');

        $applicant = new Applicant();

        $form = $this->createAppForm($applicant, $agency);
        $form->submit($request);

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
        $sourcingHasDiscipline = $rejectionRepository->sourcingHasDiscipline($agency, $form->get('personalInformation')->get('discipline')->getData());
        $sourcingHasSpecialty = $rejectionRepository->sourcingHasSpecialty($agency, $form->get('personalInformation')->get('specialtyPrimary')->getData());
        if ($sourcingHasDiscipline) {
            $form->addError(new FormError($sourcingHasDiscipline->getRejectMessage()));
        } else if ($sourcingHasSpecialty) {
            $form->addError(new FormError($sourcingHasSpecialty->getRejectMessage()));
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
            $personalInfo->setApplicant($applicant);
            $disciplineInfo = $this->getDoctrine()->getManager()->getRepository('AppformFrontendBundle:Discipline')->find($personalInfo->getDiscipline());
            $specialtyInfo = $this->getDoctrine()->getManager()->getRepository('AppformFrontendBundle:Discipline')->find($personalInfo->getSpecialtyPrimary());


            $filename = "HCEN - {$disciplineInfo->getShort()}, ";
            if ($personalInfo->getDiscipline() == 5) {
                $filename .= "{$specialtyInfo->getName()}, ";
            }
            $filename .= "{$applicant->getLastName()}, {$applicant->getFirstName()} - {$randNum}";
            $filename = str_replace('/', '-', $filename);

            $document = new Document();
            $document->setApplicant($applicant);
            $document->setPdf($filename);
            $document->setXls($filename);
            $applicant->setDocument($document);
            $document->setFileName($filename);

            $em->persist($document);
            $em->persist($personalInfo);
            $em->persist($applicant);
            $em->flush();

            $visitorLogger->logVisitor($applicant);

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
        $session = $this->container->get('session');
        $session->set('origin', $agency);

        // Count Online Users and Log Visitors
        $token = $this->get('counter')->init();

        $form = $this->createAppForm(new Applicant(), $agency);

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

        $form = $this->createAppForm($applicant, $agency);
        $form->submit($request);

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
        $sourcingHasDiscipline = $rejectionRepository->sourcingHasDiscipline($agency, $form->get('personalInformation')->get('discipline')->getData());
        $sourcingHasSpecialty = $rejectionRepository->sourcingHasSpecialty($agency, $form->get('personalInformation')->get('specialtyPrimary')->getData());
        if ($sourcingHasDiscipline) {
            $form->addError(new FormError($sourcingHasDiscipline->getRejectMessage()));
        } else if ($sourcingHasSpecialty) {
            $form->addError(new FormError($sourcingHasSpecialty->getRejectMessage()));
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
     * @Route("/validate/{type}", name="appform_frontend_form_validate")
     * @Method("POST")
     */
    public function validateAction($type, Request $request)
    {
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
            }
        }

        return new JsonResponse($response);
    }

    private function createAppForm(Applicant $entity, $agency)
    {
        $form = $this->createForm(new ApplicantType($this->container, $this->getDoctrine()->getManager(), $agency), $entity);
        return $form;
    }


    /**
     * List of specialties per disciplines and agency.
     *
     * @Route("/specialties-list", name="appform_frontend_specialtiesList")
     * @Method("GET")
     */
    public function specialtiesListAction(Request $request)
    {
        $response = array();
        $disciplineSid = $request->get('discipline');

        $em = $this->getDoctrine()->getManager();
        $disciplineEntity = $em->getRepository('AppformFrontendBundle:Discipline')->findOneBy(array(
            'sid' => $disciplineSid
        ));

        if (!$disciplineEntity) {
            $response = array('error' => 'Not found exception.');
            return new JsonResponse($response);
        }

        $specialtiesList = $em->getRepository('AppformFrontendBundle:Specialty')->findBy(array(
            'type' => $disciplineEntity->getType(),
            'hidden' => 0
        ));

        foreach ($specialtiesList as $specialty) {
            $response[] = array(
                "id" => $specialty->getSid(),
                "name" => $specialty->getName()
            );
        }
        return new JsonResponse($response);
    }

}
