<?php


namespace Appform\FrontendBundle\Extensions;


use WhoisApi\EmailVerifier\Builders\ClientBuilder;

class EmailChecker
{

    public function __construct()
    {
    }

    public function validate($email)
    {
        $builder = new ClientBuilder();
        $client = $builder->build('at_dghvai0g1hd5r5P4XJ5mHxaaQ68tu');
        $result = $client->get($email, ['_hardRefresh']);

        return $result->smtpCheck;
    }
}

