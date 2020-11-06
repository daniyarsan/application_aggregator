<?php

namespace Appform\FrontendBundle\EventListener;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Mailer\Mailer;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ApplyProcessListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function postPersist(LifeCycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Applicant &&
            $this->container->get('request')->getClientIp() != '::1') {

            $applicant = $args->getEntity();
            $fieldManager = $this->container->get('field_manager');
            $fileGenerator = $this->container->get('file_generator');

            $exportData = $fieldManager->getDataForExport($applicant);
            $fieldsMapping = $fieldManager->getFieldsForExport();

            $attachments[] = $fileGenerator->generatePdf($exportData);
            $attachments[] = $fileGenerator->generateXls($exportData, $fieldsMapping);
            if (!empty($applicant->getDocument()->getPath())) {
                $attachments[] = $this->container->getParameter('resume_upload_dir') . '/' . $applicant->getDocument()->getPath();
            }

            $mailer = $this->container->get('sender');
            $mailer->setTemplateName('AppformFrontendBundle:Default:email_template.html.twig');
            $mailer->setAttachments($attachments);
            $mailer->setParams(array(
                'info' => $exportData
            ));
            $mailer->sendApplyEmail();
        }
    }
}


