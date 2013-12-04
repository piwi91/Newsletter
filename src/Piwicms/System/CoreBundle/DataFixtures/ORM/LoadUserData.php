<?php

namespace Piwicms\System\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Piwicms\System\CoreBundle\Entity\User;
use Piwicms\System\CoreBundle\Entity\Group;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $groupAdmin = new Group('Administrators');
        $groupAdmin->addRole('ROLE_USER');
        $groupAdmin->addRole('ROLE_ADMIN');

        $manager->persist($groupAdmin);

        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPlainPassword('admin');
        $userAdmin->setFirstname('admin');
        $userAdmin->setEmail('info@piwi-web.com');
        $userAdmin->setSurname('admin');
        $userAdmin->setGender('male');
        $userAdmin->setAddress('-');
        $userAdmin->setZipcode('-');
        $userAdmin->setCity('-');
        $userAdmin->setCountry('nl');
        $userAdmin->setEnabled(true);
        $userAdmin->setActive(true);
        $userAdmin->addGroup($groupAdmin);

        $manager->persist($userAdmin);

        $manager->flush();
    }
}
?>