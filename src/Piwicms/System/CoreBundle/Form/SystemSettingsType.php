<?php

namespace Piwicms\System\CoreBundle\Form;

use Piwicms\System\CoreBundle\Form\SystemSettings\GeneralSystemSettingsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class SystemSettingsType
 * @package Piwicms\System\SystemBundle\Form
 */
class SystemSettingsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formData = array(
            'general' => array()
        );
        $formData = array_merge($builder->getData(), $formData);
        $builder
            ->add('general', new GeneralSystemSettingsType(),
                array(
                    "data" => $formData['general']
                )
            );

        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,

        ));
    }

    public function getName()
    {
        return 'piwicms_system_settings';
    }
}
