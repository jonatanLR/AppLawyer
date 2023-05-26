<?php

namespace App\Form;

use App\Entity\Persona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PersonaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'No debe de ser vacio'),
                    new Regex( pattern: '/\d/', match: false, message: 'Your Nombre cannot contain a number')
                ]
            ])
            ->add('dni', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'No debe de ser vacio'),
                    new Regex(pattern: '/\d/',
                                match: true, 
                                message: "Your DNI cannot contain a string"
                            )
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(message: 'No debe de ser vacio')
                ]
            ])
            ->add('direccion', TextareaType::class)
            ->add('telefono', TelType::class, [
                'constraints' => [
                    new NotBlank(message: 'Telefono no debe ser vacio'),
                    new Regex(pattern: '/^[+]*[(]?[0-9]{1,4}[)]?[0-9-\s\.]+$/', match: true, message: "Your telefono cannot contain a string")
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
            // 'inherit_data' => true,
        ]);
    }
}
