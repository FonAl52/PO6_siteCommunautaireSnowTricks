<?php

namespace App\Form;

use App\Entity\TricksVideo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Validator\Constraints\VideoUrls;
class TricksVideoType extends AbstractType
{
    /**
     * Build formular for tricksVideo update
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('tricksVideo', TextType::class, [
            'label' => 'Ajouter des vidéos (Les URLs des vidéos doivent commencer par "https://)',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'attr' => ['class' => 'form-control'],
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new VideoUrls(),
            ],
        ])
        ->add('submit', SubmitType::class, [
            'label' => "Modifier la vidéo",
            'attr' => [
                'class' => 'btn btn-primary mt-4'
            ]
        ]);
    }
    //end buildForm()

    /**
     * Options validation configuration for TricksVideo class
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TricksVideo::class,
        ]);
    }

}
