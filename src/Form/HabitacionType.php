<?php

namespace App\Form;

use App\Entity\Habitacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class HabitacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipo')
            ->add('planta')
            ->add('imagen', FileType::class, [
                'label' => "Fichero de planta",
                "attr" => array("class" => "form-control"),
                "data_class" => null,
                'mapped' => true,
                'required' => true,

                'constraints' => [new File([
                    'mimeTypes' => ['image/png', 'image/jpeg', 'image/gif'],
                    'mimeTypesMessage' => 'Solo se permiten imagenes'
                ])]
            ])->add('extras')
            ->add('precio');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Habitacion::class,
        ]);
    }
}
