<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 26.09.16
 * Time: 21:07
 */

namespace App\EducationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\EducationBundle\Entity\Image;

use Symfony\Component\Validator\Constraints\Image as ImgCons;
use Symfony\Component\Validator\Constraints\NotBlank;
class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class,
                array(
                    'constraints' =>
                        new ImgCons(
                            array(
                                'mimeTypes' => 'image/*',

                            )
                        ),

                )
            )
                ->add('descimage', TextareaType::class,
                    array(

                    )
                );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Image::class,
        ));
    }
}