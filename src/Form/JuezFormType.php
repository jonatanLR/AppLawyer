<?php

namespace App\Form;

use App\Entity\Juez;
use App\Entity\Persona;
use Doctrine\ORM\Mapping\FieldResult;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Webmozart\Assert\Assert as AssertAssert;

class JuezFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($builder->create('persona', PersonaFormType::class, [
                'by_reference' => true,
                 'label' =>false,
                 'constraints' => [
                    new UniqueEntity(fields:['dni'], message:'Ya existe un registro con este DNI {{ value }}'),
                    new UniqueEntity(fields: ['email'], message: 'Ya existe un corre con esta misma direccion')                 ]
                 ]))
            ->add('num_profesion',TextType::class)
            ->add('grabar', SubmitType::class)
            // ->add('expedientes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Juez::class
        ]);
    }
}
