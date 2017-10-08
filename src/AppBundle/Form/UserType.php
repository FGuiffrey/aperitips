<?php

namespace AppBundle\Form;

use AppBundle\Entity\Auth\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType.
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'    => 'form.user.firstname',
                'required' => false,
            ])
            ->add('lastname', TextType::class, [
                'label'    => 'form.user.lastname',
                'required' => false,
            ])
            ->add('pictureFile', FileType::class, [
                'label'    => 'form.user.picture',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.user.edit_submit',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => User::class,
            'translation_domain' => 'form'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'appbundle_ruser';
    }
}
