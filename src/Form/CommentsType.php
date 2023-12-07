<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\Comments;

class CommentsType extends AbstractType
{
    /**
     * Build formular to post a comment
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Rejoignez la discussion laisser un commentaire!',
                'label_attr' => [
                    'class' => 'mb-4'
                ],
                'attr' => ['class' => 'form-control'],
                'constraints' => [new NotBlank()]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Poster mon commentaire',
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }
    //end buildForm()

    /**
     * Options validation configuration for Comments class
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }

}

