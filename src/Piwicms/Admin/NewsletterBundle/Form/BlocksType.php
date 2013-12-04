<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pimwiddershoven
 * Date: 30-10-13
 * Time: 17:47
 * To change this template use File | Settings | File Templates.
 */

namespace Piwicms\Admin\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

class BlocksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', 'wysiwyg', array(
                'attr' => array(
                    'class' => 'tinymce'
                ),
                'required' => true
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Piwicms\System\CoreBundle\Entity\MailingBlock'
        ));
    }

    public function getName()
    {
        return 'piwicms_view_blocks';
    }
}