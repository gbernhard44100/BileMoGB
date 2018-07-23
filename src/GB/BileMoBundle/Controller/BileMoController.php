<?php

namespace GB\BileMoBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use GB\BileMoBundle\Entity\Phone;
use GB\BileMoBundle\Entity\Customer;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Hateoas\Configuration\Annotation as Hateoas;
use Nelmio\ApiDocBundle\Annotation as Doc;

use FOS\RestBundle\Controller\Annotations\RequestParam;

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
     * @Doc\ApiDoc(
     *      section = "Phones",
     *      description = "Get the list of phones.",
     *      headers={
     *         {
     *             "name"="Token",
     *             "required"="true",
     *             "description"="JWT Token provided once logged in (POST request with username and password by using the Route /login_check)."
     *         }
     *      },
     *      statusCodes={
     *          200="Return the list of phones.",
     *          401="The JWT Token is not valid. You need to login to obtain a new JWT Token."
     *      }
     * )
     */
    public function phonesAction()
    {
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
     * @Doc\ApiDoc(
     *      section = "Phones",
     *      resource = true,
     *      description = "Get the detail information about the selected phone.",
     *      requirements={
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "\d+",
     *              "description" = "the id number of the phone."
     *          }
     *      },
     *      headers={
     *         {
     *             "name"="Token",
     *             "required"="true",
     *             "description"="JWT Token provided once logged in (POST request with username and password by using the Route /login_check)."
     *         }
     *      },
     *      statusCodes={
     *          200="Returned the detail information of the selected phone.",
     *          401="The JWT Token is not valid. You need to login to obtain a new JWT Token.",
     *          404="Return an error message because the id provided doesn't meet the requirements or the phone having this id number doesn't exist in the database."
     *      }
     * ) 
     */
    public function phoneDetailAction(Phone $phone)
    {
        return $phone;
    }

    /**
     * @Rest\Get(
     *      path = "/customers",
     *      name = "gb_bilemo_customers",
     * )
     * @Rest\View(
     *      statusCode = 200,
     *      serializerGroups = {"GET_CUSTOMERS"}
     * )
     * @Doc\ApiDoc(
     *      section = "Customer",
     *      description = "Get the list of customers linked to the authenticated user.",
     *      headers={
     *         {
     *             "name"="Token",
     *             "required"="true",
     *             "description"="JWT Token provided once logged in (POST request with username and password by using the Route /login_check)."
     *         }
     *      },
     *      statusCodes={
     *          200="Return the list of customers linked to the authenticated user.",
     *          401="The JWT Token is not valid. You need to login to obtain a new JWT Token."
     *      }
     * )
     */
    public function customersFromStoreAction()
    {
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
     *      serializerGroups = {"GET_CUSTOMER_DETAIL", "PHONE_GET"}
     * )
     * @Doc\ApiDoc(
     *      section = "Customer",
     *      resource = true,
     *      description = "Get the detail information about the selected customer.",
     *      requirements={
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "\d+",
     *              "description" = "the id number of the customer."
     *          }
     *      },
     *      statusCodes={
     *          200="Returned the detail information about the selected customer.",
     *          401="The JWT Token is not valid. You need to login to obtain a new JWT Token.",
     *          403="The user authenticated is not allowed to have access to the customer selected because this customer is not linked to his account.",
     *          404="Return an error message because the id provided doesn't meet the requirements or the customer having this id number doesn't exist in the database."
     *      },
     *      headers={
     *         {
     *             "name"="Token",
     *             "required"="true",
     *             "description"="JWT Token provided once logged in (POST request with username and password by using the Route /login_check)."
     *         }
     *     }
     * )
     */
    public function customerDetailAction(Customer $customer = null)
    {
        if ($customer->getStore() == $this->getUser()) {
            return $customer;
        } else {
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
     * @ParamConverter("customer", converter="fos_rest.request_body",
     *      options={"deserializationContext"={"groups"={"GET_CUSTOMER_DETAIL", "GET_CUSTOMER_PHONE"}, "version"="1.0"}})
     * @Doc\ApiDoc(
     *      section = "Customer",
     *      resource = true,
     *      description = "Get the detail information about the selected customer.",
     *      statusCodes={
     *          201="Return the detail information of the customer that has just been created.",
     *          400="Return the reasons why the content filled in the form is not valid.",
     *          401="The JWT Token is not valid. You need to login to obtain a new JWT Token."
     *      },
     *      headers={
     *         {
     *             "name"="Token",
     *             "required"="true",
     *             "description"="JWT Token provided once logged in (POST request with username and password by using the Route /login_check)."
     *         },
     *         {
     *             "name"="Content-Type",
     *             "description"="The format type of your content you fill in your POST request."
     *         }
     *      },
     * )
     */
    public function createCustomer(Customer $customer, ConstraintViolationListInterface $violations)
    {
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
     * @Doc\ApiDoc(
     *      section = "Customer",
     *      requirements={
     *          {
     *              "name" = "id",
     *              "dataType" = "integer",
     *              "requirement" = "\d+",
     *              "description" = "the id number of the customer."
     *          }
     *      },
     *      statusCodes={
     *          204="The customer selected has been properly deleted from the database.",
     *          401="The JWT Token is not valid. You need to login to obtain a new JWT Token.",
     *          403="The user authenticated is not allowed to have access to the customer selected because this customer is not linked to his account.",
     *          404="Return an error message because the id provided doesn't meet the requirements or the customer having this id number doesn't exist in the database."
     *      },
     *      headers={
     *         {
     *             "name"="Token",
     *             "required"="true",
     *             "description"="JWT Token provided once logged in (POST request with username and password by using the Route /login_check)."
     *         }
     *     }
     * )
     */
    public function deleteCustomer(Customer $customer)
    {
        if ($customer->getStore() == $this->getUser()) {
            $message = 'The customer '.$customer->getFirstName().' '.$customer->getLastName().' has been properly removed from the database.';
            $this->em->remove($customer);
            $this->em->flush();
            return $this->view(Response::HTTP_NO_CONTENT.': '.$message);
        } else {
            throw new AccessDeniedException('You are not allowed to suppress this customer.');
        }
    }

}
