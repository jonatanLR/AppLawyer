<?php

namespace App\Form;

use App\Config\TextTipo;
use App\Entity\Persona;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\Query\AST\IndexBy;
use PhpParser\Parser\Multiple;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Util\ServerParams;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Image;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // parent::buildForm($builder, $options);
        // dd($builder->getData()->getPersona()->getEmail());
        // dd($options['data']->getEmail());
        $builder
            ->add('persona', PersonaFormType::class, [
                'by_reference' => true,
                //  'data_class' => User::class,
                'label' => false,
                'constraints' => [
                    new UniqueEntity(fields: ['dni'], message: 'Ya existe un registro con este DNI {{ value }}'),
                    new UniqueEntity(fields: ['email'], message: 'Ya existe un corre con esta misma direccion')
                ]
            ])
            ->add('numAbogado');
        // show the password field if the form is to create user
        if ($options['isEdit'] === false) {
            $builder->add('password', PasswordType::class);
        }
        // si estamos en create isEdit el igual a false
        if ($options['isEdit'] === false) {
            $builder->add('roles', EntityType::class, [
                'class' => Role::class,
                'mapped' => false,
                'expanded' => true,
                'multiple' => true
            ])
                ->add('foto', FileType::class, [
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new Image(['maxSize' => '1024k'])
                    ]
                ])
                ->add('tipo', ChoiceType::class, [
                    'choices' => [
                        'Admin' => 1,
                        'Abogado' => 2,
                        'Suplente' => 3,
                    ],
                    'placeholder' => 'Elige una opcion',
                    // 'class' => TextTipo::class,
                    'attr' => ['class' => 'form-control form-select form-select-sm'],
                    'expanded' => false,
                    'multiple' => false
                ]);
        } elseif ($options['isEdit'] === true) {
            $builder->add('roles', EntityType::class, [
                'class' => Role::class,
                'mapped' => false,
                'expanded' => true,
                'multiple' => true,
                'data' => $options['data']->getRole()
            ])
                ->add('fotoActual', HiddenType::class, [
                    'mapped' => false,
                    'label' => 'Foto Actual',
                    'data' => $options['data']->getFoto(),
                    'disabled' => true
                ])
                ->add('foto', FileType::class, [
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new Image([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/gif',
                                'image/jpeg',
                                'image/png',
                                'image/jpg',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid Image',
                        ])
                    ],
                    'data_class' => null,
                    'label' => 'Actualizar Foto'
                ])
                ->add('tipo', ChoiceType::class, [
                    'choices' => [
                        'Admin' => 'Admin',
                        'Abogado' => 'Abogado',
                        'Suplente' => 'Suplente',
                    ],
                    // 'data' => 3,
                    // 'placeholder' => 'Elige una opcion',
                    // 'class' => TextTipo::class,
                    'attr' => ['class' => 'form-control form-select form-select-sm'],
                    'expanded' => false,
                    'multiple' => false,
                    'data' => $options['data']->getTipo()
                ])
                ->add('email', HiddenType::class, [
                    'mapped' => false,
                    'data' => $options['data']->getEmail()
                ]);
        }

        $builder->add('Grabar', SubmitType::class, [
            'attr' => ['class' => 'btn-primary btn-lg']
        ]);

        // event to add the email field  the value persona-email to user-email
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            // dd($data);
            $form->add('email', HiddenType::class)->setData($data['persona']['email']);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('isEdit', false);
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
