<?php

namespace App\Form;

use App\Entity\Complemento;
use App\Entity\Habitacion;
use App\Entity\Tipo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class HabitacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoIdtipo', EntityType::class, [
                'class' => Tipo::class, 'placeholder' => 'Elige un tipo de habitación'
            ])
            ->add('planta')
            ->add('imagen', FileType::class, [
                'label' => "Fichero de habitación",
                "attr" => array("class" => "form-control"),
                "data_class" => null,
                'mapped' => true,
                'required' => true,


                'constraints' => [new File([
                    'mimeTypes' => ['image/png', 'image/jpeg', 'image/gif'],
                    'mimeTypesMessage' => 'Solo se permiten imagenes'
                ])]
            ])
            //->add('complementoIdcomplemento')
            ->add('complementoIdcomplemento', EntityType::class, [
                // looks for choices from this entity
                'class' => Complemento::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'nombre',

                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => true,
            ])

            ->add('precio');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Habitacion::class,
        ]);
        $resolver->setDefaults(['required' => false,]);
    }
}
