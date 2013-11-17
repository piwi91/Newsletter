<?php

namespace Piwicms\Client\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;

class SubscribeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', array(
                'required' => true,
                'label' => 'client.newsletterbundle.form.firstname'
            ))
            ->add('surname', 'text', array(
                'required' => true,
                'label' => 'client.newsletterbundle.form.surname'
            ))
            ->add('emailaddress', 'email', array(
                'required' => true,
                'label' => 'client.newsletterbundle.form.emailaddress'
            ))
            ->add('mailingList', 'entity', array(
                'class'         => 'PiwicmsSystemCoreBundle:MailingList',
                'property'      => 'name',
                'multiple'      => true,
                'expanded'      => true,
                'label' => 'client.newsletterbundle.form.mailinglist'
            ))
            ->add('save', 'submit', array(
                'label' => 'client.newsletterbundle.form.submit_subscribe',
                'attr' => array(
                    'class' => 'btn btn-primary'
                ),
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwicms\System\CoreBundle\Entity\MailingUser'
        ));
    }

    public function getName()
    {
        return 'subscribe';
    }
}