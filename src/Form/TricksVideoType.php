<?php

namespace App\Form;

use App\Entity\TricksVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints as Assert;

class TricksVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('videoFile', VichFileType::class, [
                'label' => 'Vidéos',
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],'constraints' => [
                    new Assert\File([
                        'maxSize' => '20M',
                        'maxSizeMessage' => 'La video est trop volumineuse. La taille maximale autorisée est de 20 Mo.',
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
