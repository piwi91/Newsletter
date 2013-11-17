<?php

namespace Piwicms\Admin\NewsletterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityRepository;

class NewsletterStep2Type extends AbstractType
{

    private $blocks;

    public function __construct($blocks)
    {
        $this->blocks = $blocks;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $index = 0;
        /** @var $block \Piwicms\System\CoreBundle\Entity\ViewBlock */
        foreach ($this->blocks as $block) {
            $index++;
            $builder->add('mailingBlock_' . $index, 'wysiwyg', array(
                'attr' => array(
                    'data-index' => $index,
                    'data-id' => $block->getId(),
                    'data-block' => $block->getName(),
                    'class' => 'mailingblock tinymce'
                ),
                'label' => $block->getName(),
                'required' => true
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(''));
    }

    public function getName()
    {
        return 'piwicms_newsletter_step2';
    }
}