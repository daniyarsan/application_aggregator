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

    public function prePersist(LifeCycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Applicant) {
            $applicant = $args->getEntity();
            $fieldManager = $this->container->get('field_manager');
            $fileGenerator = $this->container->get('file_generator');
            $visitorLogger = $this->container->get('visitor_logger');

            $exportData = $fieldManager->getDataForExport($applicant);
            $fieldsMapping = $fieldManager->getFieldsForExport();

            $attachment[] = $fileGenerator->generatePdf($exportData);
            $attachment[] = $fileGenerator->generateXls($exportData, $fieldsMapping);

            $mailer = $this->container->get('sender');
            $mailer->setTemplateName('AppformFrontendBundle:Default:email_template.html.twig');
            $mailer->setParams(array(
                'info' => $exportData,
                'attachment' => $attachment
            ));
            $mailer->sendMessage();

            $visitorLogger->logVisitor($args->getEntity());
        }
    }
}


