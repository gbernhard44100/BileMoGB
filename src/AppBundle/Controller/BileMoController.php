<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Phone;
use AppBundle\Entity\Customer;
use AppBundle\Form\CustomerType;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Hateoas\Configuration\Annotation as Hateoas;
use Nelmio\ApiDocBundle\Annotation\Operation;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

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
     * @Operation(
     *     tags={"Phones"},
     *     summary="Get the list of phones.",
     *     @SWG\Response(
     *         response="200",
     *         description="Return the list of phones."
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="The JWT Token is not valid. You need to login to obtain a new JWT Token."
     *     )
     * )
     */
    public function phonesAction()
    {
        $phones = $this->em->getRepository(Phone::class)->findAll();
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
     * @Operation(
     *     tags={"Phones"},
     *     summary="Get the detail information about the selected phone.",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="Id of the phone you want to have the detail information."
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned the detail information of the selected phone."
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="The JWT Token is not valid. You need to login to obtain a new JWT Token."
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Return an error message because the id provided doesn't meet the requirements or the phone having this id number doesn't exist in the database."
     *     )
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
     * @Operation(
     *     tags={"Customer"},
     *     summary="Get the list of customers linked to the authenticated user.",
     *     @SWG\Response(
     *         response="200",
     *         description="Returned the list of customers linked to the authenticated user."
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="The JWT Token is not valid. You need to login to obtain a new JWT Token."
     *     )
     * )
     */
    public function customersFromStoreAction()
    {
        $customers = $this->em->getRepository(Customer::class)->findByStore($this->getUser());
        return $customers;
    }

    /**
     * @Rest\Get(
     *      path = "/customers/{id}",
     *      name = "gb_bilemo_customer_detail",
     *      requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *      statusCode = 201,
     *      serializerGroups = {"GET_CUSTOMER_DETAIL", "PHONE_GET"}
     * )
     * @Operation(
     *     tags={"Customer"},
     *     summary="Get the detail information about the selected customer.",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="Id of the customer you want to have the detail information."
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Returned the detail information about the selected customer."
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="The JWT Token is not valid. You need to login to obtain a new JWT Token."
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="The user authenticated is not allowed to have access to the customer selected because this customer is not linked to his account."
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Return an error message because the id provided doesn't meet the requirements or the customer having this id number doesn't exist in the database."
     *     )
     * )
     */
    public function customerDetailAction(Customer $customer)
    {
        if ($customer->getStore() == $this->getUser()) {
            return $customer;
        } else {
            throw new AccessDeniedException('You are not allowed to have access to this customer.');
        }
    }

    /**
     * @Rest\Post(
     *      path = "customers",
     *      name = "gb_bilemo_customer_create",
     * )
     * @Rest\View(
     *      statusCode = 201,
     *      serializerGroups = {"GET_CUSTOMER_DETAIL", "PHONE_GET"}
     * )
     * @ParamConverter(
     *      "customer",
     *      converter="fos_rest.request_body", 
     *      options={"deserializationContext"={"groups"={"GET_CUSTOMER_DETAIL"}, "version"="1.0"}}
     * )
     * @Operation(
     *     tags={"Customer"},
     *     summary="Register a new customer in your store database.",
     *     @SWG\Parameter(
     *         name="form",
     *         in="body",
     *         description="Customer form filled in json.",
     *         @Model(type=AppBundle\Form\CustomerType::class)
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Return the detail information of the customer that has just been created."
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Return the reasons why the content filled in the form is not valid."
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="The JWT Token is not valid. You need to login to obtain a new JWT Token."
     *     )
     * )
     */
    public function createCustomer(Customer $customer, ConstraintViolationListInterface $violations, Request $request)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            return $this->view($message, Response::HTTP_BAD_REQUEST);
        }
        $customer->setStore($this->getUser());
        $phoneId = $request->request->get('phone');
        $phone = $this->em->getRepository(Phone::class)->findOneById($phoneId);       
        $customer->setPhone($phone);
        $this->em->persist($customer);
        $this->em->flush();
        return $this->view($customer, Response::HTTP_CREATED, ['Location' => $this->generateUrl(
            'gb_bilemo_customer_detail',
            ['id' => $customer->getId()]
        )]);
    }

    /**
     * @Rest\Delete(
     *      path = "customers/{id}",
     *      name = "gb_bilemo_customer_delete",
     *      requirements = {"id"="\d+"},
     * )
     * @Rest\View(statusCode = 204)
     * @Operation(
     *     tags={"Customer"},
     *     summary="Delete the selected customer.",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         type="integer",
     *         description="Id of the customer you want to delete."
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="The customer selected has been properly deleted from the database."
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="The JWT Token is not valid. You need to login to obtain a new JWT Token."
     *     ),
     *     @SWG\Response(
     *         response="403",
     *         description="The user authenticated is not allowed to have access to the customer selected because this customer is not linked to his account."
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Return an error message because the id provided doesn't meet the requirements or the customer having this id number doesn't exist in the database."
     *     )
     * )
     */
    public function deleteCustomer(Customer $customer)
    {
        if ($customer->getStore() == $this->getUser()) {
            $this->em->remove($customer);
            $this->em->flush();
        } else {
            throw new AccessDeniedException('You are not allowed to suppress this customer.');
        }
    }
}
