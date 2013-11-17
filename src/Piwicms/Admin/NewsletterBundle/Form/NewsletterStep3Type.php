<?php

namespace Piwicms\Admin\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;

class NewsletterStep3Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start', 'datetimepicker', array(
                'required' => true,
                'label' => 'Start sending on'
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwicms\System\CoreBundle\Entity\Mailing'
        ));
    }

    public function getName()
    {
        return 'piwicms_newsletter_step3';
    }
}