<?php

namespace App\Form;

use App\Entity\Contrario;
use App\Entity\TipoCC;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function PHPSTORM_META\type;

class ContrarioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options['data']->getTipoCC()->getId());
        $builder
        ->add($builder->create('persona', PersonaFormType::class, [
            'by_reference' => true,
             'label' =>false,
             'constraints' => [
                new UniqueEntity(fields:['dni'], message:'Ya existe un registro con este DNI {{ value }}'),
                new UniqueEntity(fields: ['email'], message: 'Ya existe un corre con esta misma direccion')
              ]
             ]));

            if (false === $options['isEdit']) {
                $builder->add('tipo', EntityType::class, [
                    'class' => TipoCC::class,
                    'mapped' => false,
                    'placeholder' => 'Elija una opcion',
                    'attr' => ['class' => 'form-control form-select'],
                    'choice_label' => 'nombre'
                ])
                ->add('Guardar', SubmitType::class);
            }elseif(true === $options['isEdit']){
                $builder->add('tipo', EntityType::class, [
                    'class' => TipoCC::class,
                    'mapped' => false,
                    'attr' => ['class' => 'form-control form-select'],
                    'data' => $options['data']->getTipoCC()
                ])
                ->add('Guardar', SubmitType::class);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('isEdit', false);
        $resolver->setDefaults([
            'data_class' => Contrario::class,
        ]);
    }
}
