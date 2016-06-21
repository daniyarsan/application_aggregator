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
     * @var ContainerInterface
     */
    private $container;

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
     * @param string $fromEmail
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
    }

    /**
     * @param string $toEmail
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;
    }

    /**
     * @param string $templateName
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    public function sendMessage()
    {
        $template = $this->twig->loadTemplate($this->templateName);
        $subject = $template->renderBlock('subject', $this->params);
        $textBody = $template->renderBlock('body_text', $this->params);
        $htmlBody = $template->renderBlock('body_html', $this->params);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->fromEmail, $this->fromName)
            ->setTo($this->toEmail);

        if (!empty($htmlBody)) {
            $message->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);
    }
}

