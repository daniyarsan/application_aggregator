<?php

namespace Appform\FrontendBundle\Extensions;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Mailer
{
    const SEND_TO = 'healthcaretravelers@gmail.com';
    const SEND_TO_CC = 'moreinfo@healthcaretravelers.com';

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
        $this->fromEmail = 'daniyar.san@gmail.com';
        $this->fromName = 'HCEN';
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

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
    }

    public function sendApplyEmail()
    {
        $template = $this->twig->loadTemplate($this->templateName);
        $subject = $template->render('subject');
        $htmlBody = $template->render('body_html', $this->params);

        $message = \Swift_Message::newInstance()
            ->setFrom($this->fromEmail, $this->fromName)
            ->setSubject($subject)
            ->setBody($htmlBody, 'text/html')
            ->setTo(self::SEND_TO)
            ->addCc(self::SEND_TO_CC)
            ->addCc('daniyar.san@gmail.com');

            foreach ($this->attachments as $attachment) {
                $message->attach(\Swift_Attachment::fromPath($attachment));
            }

        $this->mailer->send($message);
    }
    
/*    public function sendMessage()
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
    }*/
}
