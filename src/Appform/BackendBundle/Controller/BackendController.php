<?php

namespace Appform\BackendBundle\Controller;


use Appform\BackendBundle\Form\ApplicantType;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BackendController extends Controller{

    public function indexAction()
    {
        return $this->render('AppformBackendBundle:Backend:index.html.twig');
    }

    public function usersAction()
    {
        $applicant = $this->getDoctrine()->getRepository('AppformFrontendBundle:Applicant')->findAll();

        return $this->render('AppformBackendBundle:Backend:users.html.twig', array('applicants' => $applicant));
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

}