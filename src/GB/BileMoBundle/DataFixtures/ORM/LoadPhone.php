<?php
// src/GB/BileMoBundle/DataFixtures/ORM/LoadPhone.php

namespace GB\BileMoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use GB\BileMoBundle\Entity\Phone;
use Symfony\Component\Yaml\Yaml;

class LoadPhone implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $phones = Yaml::parse(file_get_contents(__DIR__.'/PhonesData.yml'));

        foreach ($phones as $phone) {
            $phoneToPersist = new Phone();
            $phoneToPersist->setName($phone['name']);
            $phoneToPersist->setBrand($phone['brand']);
            $phoneToPersist->setScreenSize($phone['screenSize']);
            $phoneToPersist->setPictureResolution($phone['pictureResolution']);
            $phoneToPersist->setProcessor($phone['processor']);

            $manager->persist($phoneToPersist);
        }
        
        $manager->flush();
    }
}
