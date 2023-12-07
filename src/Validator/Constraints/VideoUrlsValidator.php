<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class VideoUrlsValidator extends ConstraintValidator
{
    
    /**
     * Controller to validate video url submission
     *
     * @param [type] $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof VideoUrls) {
            throw new UnexpectedTypeException($constraint, VideoUrls::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (strpos($value, 'https://') !== 0) {
            $this->context->buildViolation('La première URL de la vidéo doit commencer par "https://".')
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }

        if (strpos($value, 'http://') !== false) {
            $this->context->buildViolation('Toutes les URLs de la vidéo doivent commencer par "https://".')
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }
    }
}
