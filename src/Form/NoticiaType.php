<?php

namespace App\Form;

use App\Entity\Noticia;
use App\Entity\Categoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NoticiaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titular')
            ->add('descripcion')
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
            ->add('categoriaIdcategoria', EntityType::class, [
                'class' => Categoria::class, 'placeholder' => 'Elige una categoría'
            ])
            ->add('categoriaIdcategoria');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Noticia::class,
        ]);
    }
}
