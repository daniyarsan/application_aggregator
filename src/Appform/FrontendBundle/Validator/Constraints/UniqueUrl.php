<?php

namespace Appform\FrontendBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUrl extends Constraint
{
    public $message = "The URL you've entered is already used in the system.";

    public function validatedBy()
    {
        return 'unique_url';
    }
}
