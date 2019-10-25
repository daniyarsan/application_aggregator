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
        $invoicingRepo = $em->getRepository('AppformBackendBundle:Stats\Invoicing');

        $campaigns = $em->getRepository('AppformBackendBundle:Campaign')->findAll();

        foreach ($campaigns as $campaign) {
            if (!$campaign->getIspublished()) {
                $output->writeln('<comment>Sending Lead ' . $campaign->getApplicant() . ' mails to - ' . $campaign->getName() . ' campaign</comment>');
                $publishTime = $campaign->getPublishat()->format('U');
                if ($publishTime && time() >= $publishTime) {
                    $applicant = false;
                    if ($applicantData = $em->getRepository('AppformFrontendBundle:Applicant')->getApplicantsData($campaign->getApplicant())) {
                        $applicant = $fieldmanager->generateFormFields($applicantData);
                    }

                    if (!empty($applicant)) {
                        $attachments = array();
                        if (isset($applicant[ 'pdf' ]) && in_array('pdf', $campaign->getFiles()[ 0 ])) {
                            $attachments[] = $applicant[ 'pdf' ];
                        }
                        if (isset($applicant[ 'xls' ]) && in_array('xls', $campaign->getFiles()[ 0 ])) {
                            $attachments[] = $applicant[ 'xls' ];
                        }
                        if (isset($applicant[ 'path' ]) && in_array('doc', $campaign->getFiles()[ 0 ])) {
                            $attachments[] = $applicant[ 'path' ];
                        }

                        foreach ($campaign->getAgencygroup()->getAgencies() as $agency) {
                            if ($agency->getActive()) {
                                $subject = preg_replace_callback('/(\[.*?\])/',
                                    function ($matches) use ($applicant, $agency) {
                                        $match = trim($matches[ 0 ], '[]');
                                        if ($match == 'agencyName') {
                                            return $agency->getName();
                                        } else {
                                            if ($applicant[ 'discipline' ] != 'RN' && $match != 'specialtyPrimary' || $applicant[ 'discipline' ] == 'RN') {
                                                return $applicant[ $match ];
                                            }
                                        }
                                    }, $campaign->getSubject());

                                $emails = explode(',', $agency->getEmail());

                                foreach ($emails as $email) {
                                    try {
                                        $mailer = $this->getContainer()->get('hcen.mailer');
                                        $mailer->setToEmail($email);
                                        $mailer->setSubject(str_replace(' ,', '', $subject));
                                        $mailer->setAttachments($attachments);
                                        $mailer->setParams(array('info' => $applicant));
                                        if ($mailer->sendMessage()) {
                                            $output->writeln('<comment>Sent to ' . $agency->getName() . '[' . $email . ']</comment>');
                                        }
                                    } catch (\Exception $e) {
                                        $output->writeln($e->getMessage());
                                    }

                                }
                            } else {
                                $output->writeln('<comment>Agency ' . $agency->getName() . ' is not active</comment>');
                            }
                        }
                    }

                    $campaign->setIspublished(1);
                    $campaign->setPublishdate(new \DateTime());
                    $invoicingRepo->saveInvoicingStats($campaign);
                    $em->flush($campaign);

                } else {
                    $output->writeln('<comment>Campaign ' . $campaign->getName() . ' is waiting to be sent.</comment>');
                }
            }
        }
        // Do whatever
        $output->writeln('Done!');
    }

    private function runCommand($string)
    {
        // Split namespace and arguments
        $namespace = split(' ', $string)[ 0 ];

        // Set input
        $command = $this->getApplication()->find($namespace);
        $input = new StringInput($string);

        // Send all output to the console
        $returnCode = $command->run($input, $this->output);

        return $returnCode != 0;
    }

}