<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 18.11.16
 * Time: 22:20
 */

namespace App\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PreRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('merchandiser', ChoiceType::class,
                array(
                    'label' => 'Your type of use',
                    'choices' => array(
                        'client' => 'client',
                        'merchandiser' => 'merchandiser',
                    )
                )
            )
            ->add('save', SubmitType::class );
    }


    public function getBlockPrefix()
    {
        return 'app_user_preegistration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\UserBundle\Entity\Users'
        ));
    }
}