<?php

namespace Piwicms\System\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WysiwygType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('class' => 'tinymce')
        ));
    }
    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'wysiwyg';
    }
}