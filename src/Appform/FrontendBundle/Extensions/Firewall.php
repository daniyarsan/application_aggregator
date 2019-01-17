<?php

namespace Appform\FrontendBundle\Extensions;

use Doctrine\ORM\EntityManager;
use GeoIp2\Database\Reader;

class Firewall
{
    private $em;
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

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function isValidIp($remoteIp)
    {
        if ($_SERVER['REMOTE_ADDR'] != '::1') {
            $db =new Reader('GeoLite2-City.mmdb');
            $client_ip=$db->city($_SERVER['REMOTE_ADDR']);
            $client_country=$client_ip->country->isoCode;
            $allowed_countries=array("US","KG");
            return in_array($client_country,$allowed_countries);
        }
        return true;
    }

    public function initFiltering()
    {
        if(!$this->isValidIp($_SERVER['REMOTE_ADDR'])) {
            header("HTTP/1.0 403 Forbidden");
            echo "<h1>Access Forbidden!</h1>";
            echo "<p>Please contact site administration for further information.</p>";
            exit();
        }
    }

}