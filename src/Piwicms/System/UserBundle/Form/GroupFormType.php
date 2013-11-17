<?php

namespace Piwicms\System\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\GroupFormType as BaseType;

class GroupFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder
            ->add('roles', 'choice', array(
                'choices' => array(
                    'ROLE_GUEST' => 'Guest',
                    'ROLE_USER' => 'User',
                    'ROLE_MODERATOR' => 'Moderator',
                    'ROLE_ADMIN' => 'Administrator',
                    'ROLE_SUPER_ADMIN' => 'Owner',
                ),
                'required' => true,
                'multiple' => true
            ));
    }

    public function getName()
    {
        return 'piwicms_group';
    }
}
?>