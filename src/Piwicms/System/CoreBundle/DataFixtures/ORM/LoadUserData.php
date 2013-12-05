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
        $groupAdmin->addRole('ROLE_GUEST');
        $groupAdmin->addRole('ROLE_MODERATOR');
        $manager->persist($groupAdmin);

        $groupGuests = new Group('Guests');
        $groupGuests->addRole('ROLE_GUEST');
        $manager->persist($groupGuests);

        $groupUsers = new Group('Users');
        $groupUsers->addRole('ROLE_GUEST');
        $groupUsers->addRole('ROLE_USER');
        $manager->persist($groupUsers);

        $groupModerators = new Group('Moderators');
        $groupModerators->addRole('ROLE_GUEST');
        $groupModerators->addRole('ROLE_USER');
        $groupModerators->addRole('ROLE_MODERATOR');
        $manager->persist($groupModerators);

        $groupOwners = new Group('Owners');
        $groupOwners->addRole('ROLE_USER');
        $groupOwners->addRole('ROLE_ADMIN');
        $groupOwners->addRole('ROLE_GUEST');
        $groupOwners->addRole('ROLE_MODERATOR');
        $groupOwners->addRole('ROLE_OWNER');
        $manager->persist($groupOwners);

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
        $userAdmin->setCountry('NL');
        $userAdmin->setEnabled(true);
        $userAdmin->setActive(true);
        $userAdmin->addGroup($groupAdmin);

        $manager->persist($userAdmin);

        $manager->flush();
    }
}
?>