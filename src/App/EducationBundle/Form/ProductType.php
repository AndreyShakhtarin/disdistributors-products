<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 04.09.16
 * Time: 13:55
 */

namespace App\EducationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\EducationBundle\Entity\Product;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\EducationBundle\Controller\ProductController;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\EducationBundle\Form\ImageType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $current_user = $this->getUser();
        $builder
            ->add('name', HiddenType::class)
            ->add('product', TextType::class,
                array('constraints' =>
                    array(
                        new NotBlank
                        (
                            array(
                                'message' => 'Ввидите Имя продукта'
                            )
                        ),
                        new Length
                        (
                            array(
                                'min' => 5,
                                'minMessage' => 'Ввидете {{ limit }} или более символов'
                            )
                        ),
                        new Regex(
                            array(
                                'pattern' => '/@!^&*/',
                                'match' => false,
                                'message' => 'Поле не может содержать следующие символы {{ pattern }}'
                            )
                        )

                    )
                )
            )
            ->add('price', TextType::class,
                array(
                    'constraints' =>
                    array(
                        new NotBlank
                        (
                            array(
                                'message' => 'Ввидите цену продукта'
                            )
                        ),
                        new Type(
                            array(
                                'type' => 'int',
                                'message' => 'Формат поля число'
                            )
                        ),
                    )
                ))
            ->add('currency', ChoiceType::class,
                array( 'choices' => Product::getCurrencies()))
            ->add('short_description', TextareaType::class,
                array(
                    'constraints' =>
                    array(
                        new NotBlank(
                            array(
                                'message' => 'Ввидите описание продукта'
                            )
                        ),
                        new Length(
                            array(
                                'min' => 20,
                                'max' => 100,
                                'minMessage' => 'Описание должно не меньше {{ limit }} символов',
                                'maxMessage' => 'Описание не может превышать {{ limit }} символов'
                            )
                        )

                    )
                ))
            ->add('description', TextareaType::class,array(
                'constraints' =>
                    array(
                        new NotBlank(
                            array(
                                'message' => 'Ввидите описание продукта'
                            )
                        ),

                    )
            ))
            ->add('type', ChoiceType::class, [
                'choices' => Product::getTypes(),
            ])
            ->add('company', TextType::class,
                array(
                    'constraints' =>
                        array(
                            new NotBlank(
                                array(
                                    'message' => 'Ввидите имя компании'
                                )
                            ),
                            new Length(
                                array(
                                    'min' => 2,
                                    'max' => 30,
                                    'minMessage' => 'Название компании не меньше {{ limit }} символов',
                                    'maxMessage' => 'Название компании не должно превышать {{ limit }} символов',

                                )
                            )
                        )
                ))
            ->add('logo', FileType::class,
                array(
                    'label' => 'Company logo',
                    'constraints' =>
                        array(
                            new NotBlank(
                                array(
                                    'message' => 'Укажите логотип компании'
                                )
                            ),
                            new Image(
                                array(
                                    'mimeTypes' => 'image/*',
                                    'minWidth'  => 200,
                                    'maxWidth'  => 200,
                                    'minHeight' => 200,
                                    'maxHeight' => 200,
                                    'mimeTypesMessage' => 'тип файла дожен быть image',
                                    'minWidthMessage' => 'Ширина и высота картинки должна быть равна 200 px',
                                    'maxWidthMessage' => 'Ширина и высота картинки должна быть равна 200 px',
                                    'minHeightMessage' => 'Ширина и высота картинки должна быть равна 200 px',
                                    'maxHeightMessage' => 'Ширина и высота картинки должна быть равна 200 px',
                                )
                            ),

                        )
                ))
            ->add('location', TextType::class,
                array(
                    'constraints' =>
                        array(
                            new NotBlank(
                                array(
                                    'message' => 'Ввидите место нахождение'
                                )
                            ),
                            new Type(
                                array(
                                    'type' => 'string',
                                    'message' => 'Город, Страна'
                                )
                            )
                        )
                )
            )
            ->add('url' , HiddenType::class)
            ->add('file', FileType::class,
                array(
                    'label' => 'Upload File',
                    'constraints' =>
                        array(
                            new File(
                                array(
                                    'maxSize' => '15M',
                                    'maxSizeMessage' => 'Размер файла не должен превышать 15М'
                                )
                            )
                        )
                ))
            ->add('product_picture', FileType::class,
                array(
                    'label' => 'Main picture for product',
                    'constraints' =>
                        array(
                            new Image(
                                array(
                                    'mimeTypes' => 'image/*',
                                    'minWidth'  => 200,
                                    'maxWidth'  => 200,
                                    'minHeight' => 200,
                                    'maxHeight' => 200,
                                    'mimeTypesMessage' => 'тип файла дожен быть image',
                                    'minWidthMessage' => 'Ширина и высота картинки должна быть равна 200 px',
                                    'maxWidthMessage' => 'Ширина и высота картинки должна быть равна 200 px',
                                    'minHeightMessage' => 'Ширина и высота картинки должна быть равна 200 px',
                                    'maxHeightMessage' => 'Ширина и высота картинки должна быть равна 200 px',
                                )
                            )
                        )
                ))
            ->add('save', SubmitType::class);
        $builder->add('image', CollectionType::class, array(
            'entry_type' => ImageType::class,
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,

        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }

    public function getUser()
    {
        $current_user = ProductController::getCurrentUser();
        return $current_user;
    }
}