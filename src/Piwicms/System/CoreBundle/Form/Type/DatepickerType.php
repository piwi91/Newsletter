<?php

namespace Piwicms\System\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatepickerType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'class' => 'datepicker',
                'data-date-format' => "dd-mm-yyyy"
            ),
            'widget' => 'single_text',
            'format' => "dd-MM-yyyy"
        ));
    }
    public function getParent()
    {
        return 'date';
    }

    public function getName()
    {
        return 'datepicker';
    }
}