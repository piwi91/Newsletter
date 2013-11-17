<?php

namespace Piwicms\System\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder
            ->add('firstname', 'text', array(
                'required' => true
            ))
            ->add('middlename', 'text', array(
                'required' => false
            ))
            ->add('surname', 'text', array(
                'required' => true
            ))
            ->add('gender', 'choice', array(
                'required' => true,
                'choices' => array('male', 'female')
            ))
            ->add('address', 'text', array(
                'required' => true
            ))
            ->add('zipcode', 'text', array(
                'required' => true
            ))
            ->add('city', 'text', array(
                'required' => true
            ))
            ->add('country', 'country', array(
                'required' => true
            ))
            ->add('phone', 'text', array(
                'required' => false
            ))
            ->add('mobilePhone', 'text', array(
                'required' => false
            ))
            ->add('fax', 'text', array(
                'required' => false
            ))
            ->add('company', 'text', array(
                'required' => false
            ))
            ->add('companyVatNumber', 'text', array(
                'required' => false
            ))
            ->add('receiveNewsletter', 'checkbox', array(
                'required' => true
            ));
    }

    public function getName()
    {
        return 'piwicms_user_profile';
    }
}
?>