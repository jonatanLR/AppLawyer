<?php

namespace App\Form;

use App\Entity\Cliente;
use App\Entity\Contrario;
use App\Entity\Expediente;
use App\Entity\Juez;
use App\Entity\Juzgado;
use App\Entity\Procurador;
use App\Entity\TpProcedimiento;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExpedienteFormType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options);
        $builder
            ->add('id', TextType::class, [
                'disabled' => true,
                'label' => 'No. Expediente',
                'data' => $options['isEdit'] ? $options['data']->getId() : $options['newExpedID']
            ])
            ->add('titulo')
            ->add('descripcion')
            ->add('fechaAlta', DateTimeType::class, [
                'widget' => 'single_text',
                'with_minutes' => false
            ])
            ->add('estado', ChoiceType::class, [
                'choices' => [
                    'Activo' => 'Activo',
                    'Pendiente' => 'Pendiente',
                    'Cerrado' => 'Cerrado',
                ],
                'placeholder' => 'Elige una opcion',
                'attr' => ['class' => 'form-control form-select form-select-sm'],
                'expanded' => false,
                'multiple' => false,
                'data' => $options['isEdit'] ? $options['data']->getEstado() : ''
            ])
            ->add('numRefExped')
            ->add('numAutos');
            if($options['isEdit'] === false){
            $builder->add('juezes', EntityType::class, [
                'class' => Juez::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => true,
                'label' => 'Jueces',
                'placeholder' => 'Eligir Jueces',
                'attr' => ['class' => 'form-control form-select form-select-sm']  
            ])
            ->add('contrarios', EntityType::class, [
                'class' => Contrario::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => true,
                'attr' => ['class' => 'form-control form-select form-select-sm']
            ])
            ->add('clientes', EntityType::class, [
                'class' => Cliente::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => true,
                'attr' => ['class' => 'form-control form-select form-select-sm']
            ])
            ->add('users',EntityType::class, [
                'class' => User::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => true,
                'attr' => ['class' => 'form-control form-select form-select-sm']
            ]);

            $builder->add('procurador', EntityType::class, [
                'class' => Procurador::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control form-select form-select-sm'],                'placeholder' => 'Eligir Jueces',
                'placeholder' => 'Eligia una opción'
            ])
            ->add('juzgado', EntityType::class, [
                'class' => Juzgado::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control form-select form-select-sm'],                'placeholder' => 'Eligir Jueces',
                'placeholder' => 'Eligia una opción'
            ])
            ->add('tpProcedimiento', EntityType::class, [
                'class' => TpProcedimiento::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control form-select form-select-sm'],
                'placeholder' => 'Eligia una opción'
            ]);
        }else{
            $builder->add('juezes', EntityType::class, [
                'class' => Juez::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => true,
                'label' => 'Jueces',
                'placeholder' => 'Eligir Jueces',
                // 'class' => TextTipo::class,
                'attr' => ['class' => 'form-control form-select form-select-sm'],
                'data' => $options['data']->getJuezes() 
            ])
            ->add('contrarios', EntityType::class, [
                'class' => Contrario::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => true,
                'attr' => ['class' => 'form-control form-select form-select-sm'],
                'data' => $options['data']->getContrarios()
            ])
            ->add('clientes', EntityType::class, [
                'class' => Cliente::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => true,
                'attr' => ['class' => 'form-control form-select form-select-sm'],    
                'data' => $options['data']->getClientes()
            ])
            ->add('users',EntityType::class, [
                'class' => User::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => true,
                'attr' => ['class' => 'form-control form-select form-select-sm'],    
                'data' => $options['data']->getUsers()
            ])
            ->add('procurador', EntityType::class, [
                'class' => Procurador::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control form-select form-select-sm'],                'placeholder' => 'Eligir Jueces',
                'placeholder' => 'Eligia una opción',
                'data' => $options['data']->getProcurador()
            ])
            ->add('juzgado', EntityType::class, [
                'class' => Juzgado::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control form-select form-select-sm'],                'placeholder' => 'Eligir Jueces',
                'placeholder' => 'Eligia una opción',
                'data' => $options['data']->getJuzgado()
            ])
            ->add('tpProcedimiento', EntityType::class, [
                'class' => TpProcedimiento::class,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
                'attr' => ['class' => 'form-control form-select form-select-sm'],
                'placeholder' => 'Eligia una opción',
                'data' =>$options['data']->getTpProcedimiento()
            ]);
        }

            $builder->add($options['isEdit'] ? 'Editar' : 'Grabar', SubmitType::class, [
                'attr' => ['class' => 'btn-primary btn-lg']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('newExpedID', false);
        $resolver->setDefault('isEdit', false);
        $resolver->setDefaults([
            'data_class' => Expediente::class,
        ]);
    }
}
