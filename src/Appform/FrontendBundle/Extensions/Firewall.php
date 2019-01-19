<?php

namespace Appform\FrontendBundle\Extensions;

use Doctrine\ORM\EntityManager;
use GeoIp2\Database\Reader;
use Symfony\Component\DependencyInjection\Container;

class Firewall
{
    private $em;
    private $container;
    private $request;

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $controller
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    public function __construct(Container $container, EntityManager $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function isValidIp()
    {
        if ($_SERVER['REMOTE_ADDR'] != '::1') {
            $settings = $this->container->get('hcen.settings');
            $ipsString = $settings->getWebSite()->getIpForBan();
            $ipsForBan = explode(',', $ipsString);
            if (in_array($_SERVER['REMOTE_ADDR'], $ipsForBan)) {
                return false;
            } else {
                $db =new Reader('GeoLite2-City.mmdb');
                $client_ip=$db->city($_SERVER['REMOTE_ADDR']);
                $client_country=$client_ip->country->isoCode;
                $allowed_countries=array("US","KG");
                return in_array($client_country,$allowed_countries);
            }
        }
        return true;
    }

    public function isValidDomain()
    {
        $session = $this->container->get('session');
        $refDomain = $session->get('origin');

        $settings = $this->container->get('hcen.settings');
        $domainString = $settings->getWebSite()->getDomainForBan();
        $domainsForBan = explode(',', $domainString);

        foreach ($domainsForBan as $domain) {
            if (strstr($refDomain, $domain)) {
                return false;
            }
        }
        return true;
    }

    public function initFiltering()
    {
        if(!$this->isValidIp($_SERVER['REMOTE_ADDR']) || !$this->isValidDomain()) {
            header("HTTP/1.0 403 Forbidden");
            echo "<h1>Access Forbidden!</h1>";
            echo "<p>Please contact site administration for further information.</p>";
            exit();
        }
    }

}