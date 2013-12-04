<?php

namespace Piwicms\Admin\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

class NewsletterStep1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => true
            ))
            ->add('template', 'entity', array(
                'class' => 'PiwicmsSystemCoreBundle:View',
                'property' => 'name',
                'empty_value' => 'Choose a template',
                'required' => true
            ))
            ->add('mailinglist', 'entity', array(
                'class' => 'PiwicmsSystemCoreBundle:MailingList',
                'property' => 'name',
                'empty_value' => 'Choose a mailing list',
                'required' => true,
                'multiple' => true
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(''));
    }

    public function getName()
    {
        return 'piwicms_newsletter_step1';
    }
}