<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Prénom'],

            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Nom'],
            ])
            ->add('mail', EmailType::class, [
                'label' => 'Adresse mail',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Mot de Passe',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Mot de Passe'],
                ],
                'second_options' => [
                    'label' => 'Répéter le Mot de Passe',
                    'attr' => ['class' => 'form-control', 'placeholder' => 'Mot de Passe'],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
            ])
            ->add('termsAccepted', CheckboxType::class, array(
                'mapped' => false,
                'constraints' => new IsTrue(),
                'label' => 'Je reconnais avoir lu et accepté les CGU du site',
                'label_attr' => ['class' => 'form-check-label required'],
                'attr' => ['class' => 'form-check-input'],
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}