<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\ORM\LoadPhone;
use AppBundle\DataFixtures\ORM\LoadStore;
use AppBundle\Entity\Store;
use AppBundle\Entity\Phone;
use AppBundle\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCustomer extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $customersSource = file_get_contents(__DIR__.'/CustomersData.json');
        $customers = json_decode($customersSource, true);

        $phoneRepository = $manager->getRepository(Phone::class);
        $storeRepository = $manager->getRepository(Store::class);
        
        foreach ($customers as $customer) {
            $customerToPersist = new Customer();
            $customerToPersist->setFirstName($customer['first_name']);
            $customerToPersist->setLastName($customer['last_name']);
            $customerToPersist->setPhoneNumber($customer['phoneNumber']);
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
