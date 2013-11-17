<?php

namespace Piwicms\System\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class UserSettingsType
 * @package Piwicms\System\UserBundle\Form
 */
class UserSettingsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //new usersettings must be added here. These will automatically be added when a user changes his usersettings
        //add a label and add it to translation

        $builder
            ->add('something', 'text', array(
                'label' => 'system.core.user.something',
                'attr' => array('class'=>'textField')
            ))
            ->add('new', 'text', array(
                'label' => 'system.core.user.new',
            ))

        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    public function getName()
    {
        return 'piwicms_user_settings';
    }
}
