<?php

namespace Piwicms\System\CoreBundle\Form\SystemSettings;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class SystemSettingsType
 * @package Piwicms\System\SystemBundle\Form
 */
class GeneralSystemSettingsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //new usersettings must be added here. These will automatically be added when a user changes his usersettings
        //add a label and add it to translation

        $builder
            ->add('something', 'text', array(
                'label' => 'system.core.setting.general.something',
                'attr' => array('class'=>'textField')
            ))
            ->add('new', 'text', array(
                'label' => 'system.core.setting.general.new',
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
        return 'piwicms_general_system_settings';
    }
}
