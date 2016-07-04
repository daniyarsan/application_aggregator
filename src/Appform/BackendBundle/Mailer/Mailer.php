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
	private $toEmails = array();

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
	 * @var string
	 */
	private $subject;

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
	 * @param array $toEmails
	 */
	public function setToEmails(array $toEmails)
	{
		$this->toEmails = $toEmails;
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


	public function sendMessage()
	{
		$template = $this->twig->loadTemplate('AppformBackendBundle:Default:email_template.html.twig');
		$htmlBody = $template->render($this->params);

		$message = \Swift_Message::newInstance()
			->setSubject($this->subject)
			->setFrom($this->fromEmail, $this->fromName)
			->setTo($this->fromEmail);
		foreach ($this->toEmails as $toEmail) {
			$message->addBcc($toEmail);
		}
		$message->setBody($htmlBody, 'text/html');

		$this->mailer->send($message);
	}
}

