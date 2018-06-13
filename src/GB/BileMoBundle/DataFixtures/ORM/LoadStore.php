<?php
// src/GB/BileMoBundle/DataFixtures/ORM/LoadStore.php

namespace GB\BileMoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GB\BileMoBundle\Entity\Store;
use Symfony\Component\Yaml\Yaml;

class LoadStore implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $storesSource = file_get_contents(__DIR__.'/StoresData.json');
        $stores = json_decode($storesSource, true);
        $index=1;

        foreach ($stores as $store) {                        
            $storeToPersist = new Store();
            $storeToPersist->setUserName($store['username']);
            $storeToPersist->setStoreName($store['storename']);
            $storeToPersist->setPassword(hash('sha512', $store['password']));
            $storeToPersist->setAddress($store['address']);

            $manager->persist($storeToPersist);
        }
        
        $manager->flush();
    }
}
