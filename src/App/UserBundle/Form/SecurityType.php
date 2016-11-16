<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 12.11.16
 * Time: 20:18
 */

namespace App\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecurityType extends AbstractType
{
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\UsernameForm';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fos_user_username';
    }
}