<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VideoUrls extends Constraint
{
    public $message = 'Les URLs des vidéos doivent être séparées par des virgules et commencer par "https://".';
}
