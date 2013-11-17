<?php

namespace Piwicms\Admin\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;

class NewsletterType extends AbstractType
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
                'required' => false
            ))
            ->add('text', 'wysiwyg', array(
                'required' => true
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwicms\System\CoreBundle\Entity\MailingList'
        ));
    }

    public function getName()
    {
        return 'piwicms_newsletter';
    }
}