<?php

namespace App\Form;

use App\Entity\Procurador;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProcuradorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('persona', PersonaFormType::class, [
            'by_reference' => true,
             'label' =>false,
             'constraints' => [
                new UniqueEntity(fields:['dni'], message:'Ya existe un registro con este DNI {{ value }}'),
                new UniqueEntity(fields: ['email'], message: 'Ya existe un corre con esta misma direccion')                 ]
             ])
        ->add('numAbogado',TextType::class)
        ->add('grabar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Procurador::class,
        ]);
    }
}
