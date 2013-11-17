<?php

namespace Piwicms\System\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimepickerType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'class' => 'timepicker',
                'data-date-format' => "hh:ii"
            ),
            'widget' => 'single_text'
        ));
    }
    public function getParent()
    {
        return 'time';
    }

    public function getName()
    {
        return 'timepicker';
    }
}