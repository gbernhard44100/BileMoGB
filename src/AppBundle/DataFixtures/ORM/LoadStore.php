<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Store;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoadStore extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $storesSource = file_get_contents(__DIR__.'/StoresData.json');
        $stores = json_decode($storesSource, true);
        
        foreach ($stores as $store) {                        
            $storeToPersist = new Store();
            $storeToPersist->setUserName($store['username']);
            $storeToPersist->setStoreName($store['storename']);
            $encoded = $this->encoder->encodePassword($storeToPersist, $store['password']);
            $storeToPersist->setPassword($encoded);
            $storeToPersist->setAddress($store['address']);
            $manager->persist($storeToPersist);
        }        
        $manager->flush();
    }
}
