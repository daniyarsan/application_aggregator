<?php
/**
 * Created by PhpStorm.
 * User: imanali
 * Date: 4/28/15
 * Time: 3:33 PM
 */

namespace Appform\BackendBundle;


use Symfony\Component\HttpFoundation\Session\Session;

class SessionRequestProcessor {
    private $session;
    private $token;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function processRecord(array $record)
    {
        if (null === $this->token) {
            try {
                $this->token = substr($this->session->getId(), 0, 8);
            } catch (\RuntimeException $e) {
                $this->token = 'adminToken';
            }
            $this->token .= '-' . substr(uniqid(), -8);
        }
        $record['extra']['token'] = $this->token;

        return $record;
    }

}