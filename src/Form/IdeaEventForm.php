<?php

namespace App\Form;

use App\Entity\IdeaEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class IdeaEventForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de l\'évènement'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('pictureFile', FileType::class, [
                'label' => 'Image de présentation'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => IdeaEvent::class,
        ));
    }
}