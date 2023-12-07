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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;

class EditTricksType extends AbstractType
{
    /**
     * Build formular for tricks update
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
                    'id' => 'photoUpdateBtn',
                    'class' => 'form-label mt-4'
                ],
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'mapped' => false
            ])
            
            ->add('tricksVideo', TextType::class, [
                'label' => 'Ajouter une (ou plusieurs) vidéo(s)',
                'label_attr' => [
                    'id' => 'videoUpdate',
                    'class' => 'form-label mt-4'
                ],
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Assert\Url([
                        'message' => 'L\'URL de la vidéo n\'est pas valide.',
                    ]),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'L\'URL de la vidéo est trop longue (maximum {{ limit }} caractères).',
                    ]),
                ],
            ])      
            ->add('id_group', EntityType::class, [
                'class' => Group::class,
                'label' => 'Group',
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
                'label' => 'Modifier',
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
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
            'id' => null,
        ]);
    }

}
