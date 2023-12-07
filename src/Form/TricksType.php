<?php

namespace App\Form;

use App\Entity\Tricks;
use App\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Validator\Constraints\VideoUrls;

class TricksType extends AbstractType
{
    /**
     * Build formular to create a tricks
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('TriksImage', FileType::class, [
                'multiple' => true,
                'label' => 'Ajouter une photo',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => ['class' => 'form-control'],
                'mapped' => false
            ])
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
            ->add('id_group', EntityType::class, [
                'label' => 'Groupe',
                'class' => Group::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'required' => true,
                'attr' => ['class' => 'form-select'],
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer',
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }
    //end buildForm()
    
    /**
     * Options validation configuration for Tricks class
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }

}

