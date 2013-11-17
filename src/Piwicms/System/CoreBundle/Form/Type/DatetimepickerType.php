<?php

namespace Piwicms\System\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatetimepickerType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'class' => 'datetimepicker',
                'data-date-format' => "dd-mm-yyyy hh:ii"
            ),
            'widget' => 'single_text',
            'date_format' => "dd-MM-yyyy hh:mm"
        ));
    }
    public function getParent()
    {
        return 'datetime';
    }

    public function getName()
    {
        return 'datetimepicker';
    }
}