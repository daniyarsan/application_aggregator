<?php
/**
 * Created by PhpStorm.
 * User: daniyar
 * Date: 20.06.16
 * Time: 23:15
 */

namespace Appform\BackendBundle\Command;

use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\StringInput;

class SenderCommand extends ContainerAwareCommand
{

	protected function configure()
	{
		$this
			->setName('sender:run')
			->setDescription('Runs Sender to submit leads to vendors.');
			//->addArgument('my_argument', InputArgument::OPTIONAL, 'Argument description');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('<comment>Running Cron Tasks...</comment>');
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$fieldmanager = $this->getContainer()->get('hcen.fieldmanager');
		$campaigns = $em->getRepository('AppformBackendBundle:Campaign')->findAll();

		foreach ($campaigns as $campaign) {
			if (!$campaign->getIspublished()) {
				$publishTime = $campaign->getPublishat()->format('U');
				if ($publishTime && time() >= $publishTime) {
					foreach ($campaign->getApplicants() as $applicantId) {
						$applicant = false;
						try {
							$applicantData = $em->getRepository('AppformFrontendBundle:Applicant')->getApplicantsData($applicantId);
							$applicant = $fieldmanager->generateFormFields($applicantData);
						} catch (NoResultException $ex) {
							$output->writeln('<comment>'. $ex->getMessage() .' - '. $campaign->getName() .'</comment>');
						}
						if ($applicant) {
							try {
								$mailer = $this->getContainer()->get('hcen.mailer');
								$i = 0;
								foreach ($campaign->getAgencygroup()->getAgencies() as $agency) {
									if ($i == 0) {
										$mailer->setToEmail($agency->getEmail());
									} else {
										$mailer->addCc( $agency->getEmail() );
									}
									$i++;
								}
								$mailer->setSubject( $campaign->getSubject() );
								//$mailer->setAttach();

								$mailer->setTemplateName('BackendBundle:Sender:email_template.html.twig');
								$mailer->setParams(array('info' => $applicant));
								$mailer->sendMessage();
							} catch (\Exception $e) {
							}
						}
					}
					$campaign->setIspublished(1);
					$campaign->setPublishdate(new \DateTime());
				}
			}
		}
		// Do whatever
		$output->writeln('Done!');
	}

	protected function testsetting(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('<comment>Running Cron Tasks...</comment>');

		$this->output = $output;
		$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$crontasks = $em->getRepository('AppformBackendBundle:CronTask')->findAll();

		foreach ($crontasks as $crontask) {
			// Get the last run time of this task, and calculate when it should run next
			$lastrun = $crontask->getLastRun() ? $crontask->getLastRun()->format('U') : 0;
			$nextrun = $lastrun + $crontask->getInterval();

			// We must run this task if:
			// * time() is larger or equal to $nextrun
			$run = (time() >= $nextrun);

			if ($run) {
				$output->writeln(sprintf('Running Cron Task <info>%s</info>', $crontask));

				// Set $lastrun for this crontask
				$crontask->setLastRun(new \DateTime());

				try {
					$commands = $crontask->getCommands();
					foreach ($commands as $command) {
						$output->writeln(sprintf('Executing command <comment>%s</comment>...', $command));

						// Run the command
						$this->runCommand($command);
					}

					$output->writeln('<info>SUCCESS</info>');
				} catch (\Exception $e) {
					$output->writeln('<error>ERROR</error>');
				}

				// Persist crontask
				$em->persist($crontask);
			} else {
				$output->writeln(sprintf('Skipping Cron Task <info>%s</info>', $crontask));
			}
		}

		// Flush database changes
		$em->flush();

		$output->writeln('<comment>Done!</comment>');
	}

	private function runCommand($string)
	{
		// Split namespace and arguments
		$namespace = split(' ', $string)[0];

		// Set input
		$command = $this->getApplication()->find($namespace);
		$input = new StringInput($string);

		// Send all output to the console
		$returnCode = $command->run($input, $this->output);

		return $returnCode != 0;
	}

}