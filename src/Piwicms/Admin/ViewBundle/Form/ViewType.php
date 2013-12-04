<?php

namespace Piwicms\Admin\ViewBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

class ViewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => true
            ))
            ->add('module', 'choice', array(
                'choices' => array(
                    'email' => 'E-mail',
                    'twig' => 'Twig template'
                ),
                'required' => true
            ))
            ->add('view', 'wysiwyg', array(
                'attr' => array('class' => 'tinymce big'),
                'required' => true
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwicms\System\CoreBundle\Entity\View'
        ));
    }

    public function getName()
    {
        return 'piwicms_view';
    }
}