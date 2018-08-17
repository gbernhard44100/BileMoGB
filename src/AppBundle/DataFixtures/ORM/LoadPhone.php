<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

class LoadPhone extends Fixture
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
