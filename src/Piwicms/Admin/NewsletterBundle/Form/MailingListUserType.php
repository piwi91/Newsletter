<?php

namespace Piwicms\Admin\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;

class MailingListUserType extends AbstractType
{

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $id = $this->id;

        $builder
            ->add('mailingUser', 'entity', array(
                'class' => 'PiwicmsSystemCoreBundle:MailingUser',
                'required' => false,
                'empty_value' => '',
                'query_builder' => function (EntityRepository $er) use ($id)
                    {
                        $qb = $er->createQueryBuilder('mailingUser');
                        $qb ->leftJoin('mailingUser.mailingList', 'mailingList', 'WITH', $qb->expr()->notIn('mailingList.id', array($id)));
                        return $qb;
                    }
            ))
            ->add('firstname', 'text', array(
                'required' => false
            ))
            ->add('surname', 'text', array(
                'required' => false
            ))
            ->add('emailaddress', 'text', array(
                'widget_addon_prepend' => array(
                    'text' => '@'
                ),
                'required' => false
            ))
            ->add('submit', 'submit', array(
                'label' => 'save',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(''));
    }

    public function getName()
    {
        return 'piwicms_mailinglistuser';
    }
}