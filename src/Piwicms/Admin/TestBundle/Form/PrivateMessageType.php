<?php

namespace Piwicms\Admin\PrivateMessageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;

class PrivateMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => true
            ))
            ->add('toUser', 'entity', array(
                'class' => 'PiwicmsSystemCoreBundle:User',
                'required' => true
            ))
            ->add('text', 'wysiwyg', array(
                'required' => true
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwicms\System\CoreBundle\Entity\PrivateMessage'
        ));
    }

    public function getName()
    {
        return 'piwicms_privatemessage';
    }
}