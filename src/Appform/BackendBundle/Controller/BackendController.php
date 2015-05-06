<?php

namespace Appform\BackendBundle\Controller;


use Appform\BackendBundle\Form\ApplicantType;
use DatePeriod;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BackendController extends Controller{

    public $limit = 3;
    public function indexAction(Request $request)
    {
/*        $toReplace = 'C:\htdocs\application\src\Appform\FrontendBundle\Entity/../../../../web/resume/';
        $em = $this->getDoctrine()->getEntityManager();
        $usrInfo = $em->getRepository('AppformFrontendBundle:Applicant')->findAll();
        foreach($usrInfo as $user) {
            $user->getDocument()->setPath(str_replace($toReplace, '', $user->getDocument()->getPath()));
            $user->getDocument()->setPdf(str_replace($toReplace, '', $user->getDocument()->getPdf()));
            $user->getDocument()->setXls(str_replace($toReplace, '', $user->getDocument()->getXls()));
            $em->persist($user);
            $em->flush();
        }*/

        $users = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->getLastMonth();
        return $this->render('AppformBackendBundle:Backend:index.html.twig', array(
            'users' => $users
        ));
    }

    public function usersAction(Request $request)
    {
        if($request->query->get('sort') and $request->query->get('direction')) {
            $sort = $request->query->get('sort') ;
            $direction = $request->query->get('direction');
        }
        else{
            $sort = 'created';
            $direction = 'asc';
        }
        if($request->request->get('search')) {
            $likeField = $request->request->get('search');
            $applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->findLikeByDirection($likeField);
        }
        else {
            $applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->getOrderByDirection($sort, $direction);
        }

        $paginator  = $this->get('knp_paginator');


        $pagination = $paginator->paginate(
            $applicant,
            $this->get('request')->query->get('page', 1),
            $this->limit
        );

        return $this->render('AppformBackendBundle:Backend:users.html.twig', array(
            'pagination' => $pagination
            )
        );
    }

    public function userEditAction($id, Request $request)
    {
        $applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->find($id);
        if(!$applicant){
            throw new NotFoundHttpException("Page not found");
        }

        $applicantForm = $this->createForm(new ApplicantType($this->get('Helper')), $applicant);

        if($request->isMethod('POST')){
            $applicantForm->handleRequest($request);
            if($applicantForm->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($applicant);
                $em->flush();

                return $this->redirect($this->generateUrl('appform_backend_index'));
            }
        }

        return $this->render('@AppformBackend/Backend/editUser.html.twig', array(
                'form' => $applicantForm->createView(),
            )
        );
    }

    public function userDeleteAction($id)
    {
        $applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->find($id);
        if(!$applicant){
            throw new NotFoundHttpException("Page not found");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($applicant);
        $em->flush();

        return $this->redirect($this->generateUrl('appform_backend_index'));
    }

    public function downloadFileAction($url)
    {
        $path = __DIR__.'/../../../../web/resume/';
        if(file_exists($path.$url)) {
            $content = file_get_contents($path.$url);
            $response = new Response();
            $response->headers->set('Content-Type', 'application/octet-stream');
            $response->headers->set('Content-Disposition', 'attachment;filename="' . $url . '"');

            $response->setContent($content);
            return $response;
        }
        else{
            header("HTTP/1.0 404 Not Found");
            throw new NotFoundHttpException("HTTP/1.0 404 Not Found");
        }
    }

    public function userSendMessageAction($id, Request $request)
    {
        if($request->isXmlHttpRequest()) {
            $applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->find($id);

            if (!$applicant) {
                throw new EntityNotFoundException;
            }
            $message = \Swift_Message::newInstance()
                ->setFrom('from@example.com')
                ->setTo('daniyar.san@gmail.com')
                ->addCc('moreinfo@healthcaretravelers.com')
                ->setSubject('HCEN Request for More Info')
                ->setBody('Please find new candidate Lead. HCEN Request for More Info')
                ->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getPdf()))
                ->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getXls()))
                ->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getPath()));

            if ($applicant->getDocument()->getPath()) {
                $message->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getPath()));
            }

            try {
                $sentStatus = $this->get('mailer')->send($message);
            } catch (Exception $e) {
                throw new NotFoundHttpException();
            }

            if ($sentStatus == 1) {
                return new JsonResponse('Your message has been sent successfully', 200);
            } else {
                return new JsonResponse('Error', 500);
            }
        }else{
            throw new BadRequestHttpException('This is not ajax');
        }
    }

    public function sendMessageAction(Request $request){
        if($request->isXmlHttpRequest()) {
            if ($request->isMethod('POST')) {
                $usersIds = $request->request->get('data');
                $sentStatus = false;
                if($usersIds) {
                    foreach ($usersIds as $id) {
                        $applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->find($id);

                        if (!$applicant) {
                            throw new EntityNotFoundException;
                        }
                        $message = \Swift_Message::newInstance()
                            ->setFrom('from@example.com')
                            ->setTo('daniyar.san@gmail.com')
                            ->addCc('moreinfo@healthcaretravelers.com')
                            ->setSubject('HCEN Request for More Info')
                            ->setBody('Please find new candidate Lead. HCEN Request for More Info')
                            ->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getPdf()))
                            ->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getXls()))
                            ->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getPath()));

                        if ($applicant->getDocument()->getPath()) {
                            $message->attach(\Swift_Attachment::fromPath($applicant->getDocument()->getPath()));
                        }

                        try {
                            $sentStatus = $this->get('mailer')->send($message);
                        } catch (Exception $e) {
                            throw new NotFoundHttpException();
                        }
                    }
                }
                if ($sentStatus == 1) {
                    return new JsonResponse('Your message has been sent successfully', 200);
                } else {
                    return new JsonResponse('Error', 500);
                }
            }

        }
        else{
            throw new BadRequestHttpException('This is not ajax');
        }
    }

    public function removeUsersAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $usersIds = $request->request->get('usersId');
            if($usersIds){
                foreach ($usersIds as $id) {
                    $applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->find($id);
                    if(!$applicant){
                        throw new EntityNotFoundException("Page not found");
                    }

                    $em = $this->getDoctrine()->getManager();
                    $em->remove($applicant);
                    $em->flush();
                }
                return new JsonResponse('Your message has been sent successfully', 200);
            }
        }
        else{
            throw new BadRequestHttpException('This is not ajax');
        }
    }

    public function getRegisterUsersAction(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $reg = array();
            $users = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->getLastMonth();
            foreach ($users as $user) {
                $format = new \DateTime($user->getCreated()->format('Y-m-d H:i:s'));
                $reg[] = $format->format('MdD');
            }

            $registerDays = array_count_values($reg);

            $now = new \DateTime();

            $thirtyDaysAgo = $now->sub(new \DateInterval("P30D"));

            $begin = new \DateTime($thirtyDaysAgo->format('Y-m-d'));
            $end = new \DateTime();
            $end = $end->modify('+1 day');

            $interval = new \DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval, $end);

            $daysInMonth = array();
            foreach ($daterange as $date) {
                $daysInMonth[$date->format("MdD")] = 0;
            }

            $result = array_merge($daysInMonth, $registerDays);
            return new JsonResponse($result);
        }
        else{
            throw new BadRequestHttpException('This is bad request');
        }
    }

}