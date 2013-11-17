<?php

namespace Piwicms\Client\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;

class UnsubscripeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailaddress', 'email', array(
                'required' => true,
                'label' => 'client.newsletterbundle.form.emailaddress'
            ))
            ->add('save', 'submit', array(
                'label' => 'client.newsletterbundle.form.submit_unsubscribe',
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
        return 'unsubscribe';
    }
}