<?php

namespace App\Form;

use App\Entity\TricksVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Importer le TextType
use Symfony\Component\Validator\Constraints as Assert;

class TricksVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('videoUrl', TextType::class, [
            'label' => 'URL de la vidéo',
            'required' => false,
            'label_attr' => [
                'class' => 'form-label mt-4',
            ],
            'constraints' => [
                new Assert\Url([
                    'message' => 'L\'URL de la vidéo n\'est pas valide.',
                ]),
                new Assert\Length([
                    'max' => 255,
                    'maxMessage' => 'L\'URL de la vidéo est trop longue (maximum {{ limit }} caractères).',
                ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TricksVideo::class,
        ]);
    }
}
