<?php

namespace App\Form;

use App\Entity\Actuacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActuacionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('fechaAlta')
            ->add('descripcion')
            ->add('direccion')
            ->add('tpActuacion')
            ->add('expediente')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Actuacion::class,
        ]);
    }
}
