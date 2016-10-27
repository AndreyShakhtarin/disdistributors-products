<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 04.09.16
 * Time: 13:55
 */

namespace App\EducationBundle\Form;

use Symfony\Component\Form\AbstractType;
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
use App\EducationBundle\Form\ImageType;

class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $current_user = $this->getUser();
        $builder
            ->add('name', HiddenType::class)
            ->add('product')
            ->add('price')
            ->add('short_description')
            ->add('description')
            ->add('type', ChoiceType::class, [
                'choices' => Product::getTypes(),
            ])
            ->add('company')
            ->add('logo', FileType::class, array('label' => 'Company logo'))
            ->add('location')
            ->add('url' , HiddenType::class)
            ->add('file', FileType::class, array('label' => 'Upload File'))
            ->add('product_picture', FileType::class, array('label' => 'Main picture for product'))
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