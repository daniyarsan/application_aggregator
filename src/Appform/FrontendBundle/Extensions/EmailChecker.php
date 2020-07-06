<?php


namespace Appform\FrontendBundle\Extensions;


use SMTPValidateEmail\Validator;

class EmailChecker
{
    protected $sender;

    public function __construct()
    {
        $this->sender = 'xyz@xzzz.com'; // for SMTP FROM:<> command
    }

    public function validate($email)
    {
        $validator = new Validator($email, $this->sender);
        $results   = $validator->validate();

        return reset($results);
    }
}

