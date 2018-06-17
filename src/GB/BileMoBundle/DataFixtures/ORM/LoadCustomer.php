<?php
// src/GB/BileMoBundle/DataFixtures/ORM/LoadCustomer.php

namespace GB\BileMoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GB\BileMoBundle\Entity\Customer;
use Symfony\Component\Yaml\Yaml;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use GB\BileMoBundle\DataFixtures\ORM\LoadPhone;
use GB\BileMoBundle\DataFixtures\ORM\LoadStore;

class LoadCustomer implements FixtureInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $customersSource = file_get_contents(__DIR__.'/CustomersData.json');
        $customers = json_decode($customersSource, true);

        $phoneRepository = $manager->getRepository('GBBileMoBundle:Phone');
        $storeRepository = $manager->getRepository('GBBileMoBundle:Store');
        
        foreach ($customers as $customer) {
            $customerToPersist = new Customer();
            $customerToPersist->setFirstname($customer['first_name']);
            $customerToPersist->setLastname($customer['last_name']);
            $customerToPersist->setPhonenumber($customer['phoneNumber']);
            $customerToPersist->setGender($customer['gender']);
            $customerToPersist->setAddress($customer['address']);
            $customerToPersist->setEmail($customer['email']);

            $customerToPersist->setStore(
                    $storeRepository->findOneById($customer['storeId'])
                    );
            $customerToPersist->setPhone(
                    $phoneRepository->findOneById($customer['phoneId'])
                    );
            
            $manager->persist($customerToPersist);
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
