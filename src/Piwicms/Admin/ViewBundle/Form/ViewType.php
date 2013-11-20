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
            ->add('type', 'choice', array(
                'choices' => array(
                    'template' => 'Template',
                    'page' => 'Page'
                )
            ))
            ->add('module', 'choice', array(
                'choices' => array(
                    'email' => 'E-mail',
                    'twig' => 'Twig template'
                ),
                'required' => true
            ))
            ->add('view', 'textarea', array(
                'attr' => array('class' => 'big'),
                'required' => true
            ))
            ->add('viewBlock', 'collection', array(
                'type' => new BlocksType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options' => array (
                    'attr' => array (
                        'class' => 'viewblock'
                    ),
                    'label' => false,
                    'widget_remove_btn' => array(
                        'label' => "remove this",
                        "icon" => "pencil",
                        'attr' => array('class' => 'btn btn-danger')
                    ),
                ),
                'widget_add_btn' => array(
                    'icon' => 'plus',
                    'label' => 'add'
                )
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