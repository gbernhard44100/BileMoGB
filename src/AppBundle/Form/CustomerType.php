<?php

namespace AppBundle\Form;

use AppBundle\Entity\Phone;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    private $manager;
    
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $phones = $this->manager->getRepository(Phone::class)->findAll();
        $phoneChoices = array();
        foreach($phones as $phone) {
            $phoneChoices[$phone->getName()] = $phone->getId();
        }
        
        $builder->add('firstName', TextType::class, array('required' => true))
        ->add('lastName', TextType::class, array('required' => true))
        ->add('phoneNumber', TextType::class, array('required' => true))
        ->add('gender', ChoiceType::class, array(
            'required' => true,
            'choices' => array('Male' => 'M', 'Female' => 'F')
        ))
        ->add('email', TextType::class, array('required' => true))
        ->add('address', TextType::class, array('required' => true))
        ->add('phone', ChoiceType::class, array(
            'required' => false,
            'choices' => $phoneChoices
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Customer'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_customer';
    }


}
