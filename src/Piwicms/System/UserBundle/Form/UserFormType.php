<?php

namespace Piwicms\System\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // add your custom field
        $builder
            ->add('username', 'text', array(
                'required' => true
            ))
            ->add('email', 'text', array(
                'required' => true
            ))
            ->add('groups', 'entity', array(
                'class' => 'PiwicmsSystemCoreBundle:Group',
                'multiple' => true
            ))
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwicms\System\CoreBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'piwicms_user';
    }
}
?>