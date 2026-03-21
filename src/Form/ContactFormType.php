<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'row_attr' => ['class' => 'form-row'],
                'constraints' => [
                    new NotBlank(message: 'Indiquez votre nom.'),
                    new Length(max: 120),
                ],
                'attr' => ['autocomplete' => 'name'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'row_attr' => ['class' => 'form-row'],
                'constraints' => [
                    new NotBlank(message: 'Indiquez votre email.'),
                    new Email(message: 'Adresse email invalide.'),
                ],
                'attr' => ['autocomplete' => 'email'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'row_attr' => ['class' => 'form-row'],
                'constraints' => [
                    new NotBlank(message: 'Écrivez un message.'),
                    new Length(min: 8, max: 5000, minMessage: 'Le message est trop court.'),
                ],
                'attr' => ['rows' => 5],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'row_attr' => ['class' => 'form-row form-row--submit'],
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'contact-form symfony-form',
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
