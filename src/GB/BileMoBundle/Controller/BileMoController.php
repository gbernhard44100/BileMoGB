<?php

namespace GB\BileMoBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Doctrine\ORM\EntityManagerInterface;
use GB\BileMoBundle\Entity\Phone;
use GB\BileMoBundle\Entity\Store;
use GB\BileMoBundle\Entity\Customer;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Hateoas\Configuration\Annotation as Hateoas;

class BileMoController extends FOSRestController
{
    
    private $em;
    
    public function __construct(EntityManagerInterface $em)
    {        
        $this->em = $em;
    }
       
    /**
     * @Rest\Get(
     *     path = "/phones",
     *     name = "gb_bilemo_phones"
     * )
     * @Rest\View(
     *     statusCode = 200,
     *     serializerGroups = {"PHONE_GET"}
     * )
     */
    public function phonesAction(){
        $phones = $this->em->getRepository("GBBileMoBundle:Phone")->findAll();
        return $phones;       
    }
    
    /**
     * @Rest\Get(
     *      path = "/phones/{id}",
     *      name = "gb_bilemo_phone_detail",
     *      requirements = {"id"="\d+"}
     * ) 
     * @Rest\View(
     *      statusCode = 200,
     *      serializerGroups = {"PHONE_DETAIL_GET"}
     * )
     * 
     */
    public function phoneDetailAction(Phone $phone){        
        return $phone;
    }
    
    /**
     * @Rest\Get(
     *      path = "/customers",
     *      name = "gb_bilemo_customers",
     *      requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *      statusCode = 200,
     *      serializerGroups = {"GET_CUSTOMERS"}
     * )
     */
    public function customersFromStoreAction(){
        $customers = $this->em->getRepository("GBBileMoBundle:Customer")->findByStore($this->getUser());
        return $customers;  
    }
    
    /**
     * @Rest\Get(
     *      path = "/customers/{id}",
     *      name = "gb_bilemo_customer_detail",
     *      requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *      statusCode = 200,
     *      serializerGroups = {"GET_CUSTOMER_DETAIL"}
     * )
     */
    public function customerDetailAction(Customer $customer){
        if($customer->getStore() == $this->getUser()){
            return $customer;
        }
        else{
            throw new AccessDeniedException('You are not allowed to have access to this customer.');
        }
    }
    
    /**
     * 
     * @Rest\Post(
     *      path = "customers",
     *      name = "gb_bilemo_customer_create",
     * )
     * @Rest\View(
     *      statusCode = 201,
     *      serializerGroups = {"GET_CUSTOMER_DETAIL"}
     * )
     * @ParamConverter("customer", converter="fos_rest.request_body")
     * 
     */
    public function createCustomer(Customer $customer, ConstraintViolationListInterface $violations){
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            return $this->view($message, Response::HTTP_BAD_REQUEST);
        }
        $customer->setStore($this->getUser());
        
        $this->em->persist($customer);
        $this->em->flush();
        return $customer;        
    }
    
    /**
     * 
     * @Rest\Delete(
     *      path = "customers/{id}",
     *      name = "gb_bilemo_customer_delete",
     *      requirements = {"id"="\d+"},
     * )
     */
    public function deleteCustomer(Customer $customer){
        if($customer->getStore() == $this->getUser()){
            $message = 'The customer '.$customer->getFirstName().' '.$customer->getLastName().' has been properly deleted from the database.';
            $this->em->remove($customer);
            $this->em->flush();
            return $this->view($message, Response::HTTP_RESET_CONTENT);
        }
        else{
            throw new AccessDeniedException('You are not allowed to suppress this customer.');
        }
    }
}
