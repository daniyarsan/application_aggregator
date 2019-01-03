<?php

namespace Appform\BackendBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Mailer

{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $fromEmail;

    /**
     * @var string
     */
    private $fromName;

    /**
     * @var string
     */
    private $toEmail;

    /**
     * @var string
     */
    private $templateName;

    /**
     * @var array
     */
    private $params = array();

    /**
     * @var array
     */
    private $attachments = array();

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $subject;

    private $cc = array();

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->mailer = $this->container->get('mailer');
        $this->twig = $this->container->get('twig');
        $this->fromEmail = $this->container->get('hcen.settings')->getWebSite()->getEmail();
        $this->fromName = $this->container->get('hcen.settings')->getWebSite()->getName();
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @param array $toEmail
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;
    }

    public function addCC($cc)
    {
        array_push($this->cc, $cc);
    }

    /**
     * @param string $templateName
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @param array $params
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
    }


    public function sendMessage()
    {
        $template = $this->twig->loadTemplate('AppformBackendBundle:Sender:email_template.html.twig');
        $htmlBody = $template->render($this->params);

        $message = \Swift_Message::newInstance()
            ->setSubject($this->subject)
            ->setFrom($this->fromEmail, $this->fromName)
            ->setTo($this->toEmail);

        foreach ($this->cc as $cc) {
            $message->addBcc($cc);
        }
        foreach ($this->attachments as $attachment) {
            if (file_exists($this->container->get('kernel')->getRootDir() . '/../web/resume/' . $attachment)) {
                $message->attach(\Swift_Attachment::fromPath($this->container->get('kernel')->getRootDir() . '/../web/resume/' . $attachment));
            }
        }
        $message->setBody($htmlBody, 'text/html');
        return $this->mailer->send($message);
    }
}

