<?php

namespace Appform\FrontendBundle\Extensions;

use Symfony\Component\DependencyInjection\ContainerInterface;

class VisitorLogger
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
    }


    public function logVisitor($applicant)
    {
        $request = $this->container->get('request');
        $token = $request->get('formToken');
        $em = $this->manager;

        $disciplineInfo = $em->getRepository('AppformFrontendBundle:Discipline')->find($applicant->getPersonalInformation()->getDiscipline());
        $visitorRepo = $em->getRepository('AppformFrontendBundle:Visitor');

        $recentVisitor = $visitorRepo->getRecentVisitor($token);
        if ($recentVisitor) {
            $applicant = $em->getRepository('AppformFrontendBundle:Applicant')->getApplicantPerToken($token);
            if ($applicant) {
                $recentVisitor->setUserId($applicant[ 'id' ]);
                $recentVisitor->setDiscipline($disciplineInfo->getName());
                $em->persist($recentVisitor);
                $em->flush();
            }
        }
    }

}
