<?php
// src/GB/BileMoBundle/DataFixtures/ORM/LoadUser.php

namespace GB\BileMoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GB\BileMoBundle\Entity\User;
use Symfony\Component\Yaml\Yaml;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use GB\BileMoBundle\DataFixtures\ORM\LoadPhone;
use GB\BileMoBundle\DataFixtures\ORM\LoadStore;

class LoadUser implements FixtureInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $usersSource = file_get_contents(__DIR__.'/UsersData.json');
        $users = json_decode($usersSource, true);

        $phoneRepository = $manager->getRepository('GBBileMoBundle:Phone');
        $storeRepository = $manager->getRepository('GBBileMoBundle:Store');
        
        foreach ($users as $user) {
            $userToPersist = new User();
            $userToPersist->setFirstName($user['first_name']);
            $userToPersist->setLastName($user['last_name']);
            $userToPersist->setPhoneNumber($user['phoneNumber']);
            $userToPersist->setGender($user['gender']);
            $userToPersist->setAddress($user['address']);
            $userToPersist->setemail($user['email']);

            $userToPersist->setStore(
                    $storeRepository->findOneById($user['storeId'])
                    );
            $userToPersist->setPhone(
                    $phoneRepository->findOneById($user['phoneId'])
                    );
            
            $manager->persist($userToPersist);
        }
        
        $manager->flush();
    }
    
        public function getDependencies()
    {
        return array(
            LoadPhone::class,
            LoadStore::class,
        );
    }
}
