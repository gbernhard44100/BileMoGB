<?php

namespace AppBundle\Form;

use AppBundle\Entity\Phone;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
        $builder->add('firstName', TextType::class, array(
            'required' => true,
            'documentation' => array(
                'type' => 'string',
                'description' => 'First name of the new customer.'
            )
        ))
        ->add('lastName', TextType::class, array(
            'required' => true,
            'documentation' => array(
                'type' => 'string',
                'description' => 'Last name of the new customer.'
            )
        ))
        ->add('phoneNumber', TextType::class, array(
            'required' => true,
            'documentation' => array(
                'type' => 'string',
                'description' => 'Phone number of the new customer.'
            )
        ))
        ->add('gender', ChoiceType::class, array(
            'required' => true,
            'choices' => array('Monsieur' => 'Mr', 'Madame' => 'Mme'),
            'documentation' => array(
                'type' => 'string',
                'description' => 'Title of the new customer : it has to be written Mr or Mme.'
            )
        ))
        ->add('email', TextType::class, array(
            'required' => true,
            'documentation' => array(
                'type' => 'string',
                'description' => 'The email address of the new Customer: it has to be a valid email address.'
            )
        ))
        ->add('address', TextType::class, array(
            'required' => true,
            'documentation' => array(
                'type' => 'string',
                'description' => 'The postal address of the new Customer.'
            )
        ))
        ->add('phone', IntegerType::class, array(
            'required' => false,
            'documentation' => array(
                'type' => 'integer',
                'description' => 'the id of the new customer\'s phone model, if this one is included in your database.'
            ),
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
