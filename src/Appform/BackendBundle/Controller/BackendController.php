<?php

namespace Appform\BackendBundle\Controller;


use Appform\BackendBundle\Form\ApplicantType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

}