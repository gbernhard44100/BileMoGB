<?php

namespace GB\BileMoBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Doctrine\ORM\EntityManagerInterface;
use GB\BileMoBundle\Entity\Phone;
use GB\BileMoBundle\Entity\Store;
use GB\BileMoBundle\Entity\User;

use FOS\RestBundle\Controller\Annotations as Rest;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     *      path = "/users",
     *      name = "gb_bilemo_users",
     *      requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *      statusCode = 200,
     *      serializerGroups = {"GET_USERS"}
     * )
     */
    public function usersFromStoreAction(){
        $users = $this->em->getRepository("GBBileMoBundle:User")->findByStore($this->getUser());
        return $users;  
    }
    
    /**
     * @Rest\Get(
     *      path = "/users/{id}",
     *      name = "gb_bilemo_user_detail",
     *      requirements = {"id"="\d+"}
     * )
     * @Rest\View(
     *      statusCode = 200,
     *      serializerGroups = {"GET_USER_DETAIL"}
     * )
     */
    public function userDetailAction(User $user){
        if($user->getStore() == $this->getUser()){
            return $user;
        }
        else{
            throw new AccessDeniedException('You are not allowed to have access to this user.');
        }
    }
    
    /**
     * 
     * @Rest\Post(
     *      path = "users",
     *      name = "gb_bilemo_user_create",
     * )
     * @Rest\View(
     * statusCode = 201,
     * serializerGroups = {"GET_USER_DETAIL"}
     * )
     * @ParamConverter("user", converter="fos_rest.request_body")
     * 
     */
    public function createUser(User $user, ConstraintViolationListInterface $violations){
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }
            return $this->view($message, Response::HTTP_BAD_REQUEST);
        }
        $user->setStore($this->getUser());
        
        $this->em->persist($user);
        $this->em->flush();
        return $user;        
    }
    
    /**
     * 
     * @Rest\Delete(
     *      path = "users/{id}",
     *      name = "gb_bilemo_user_delete",
     *      requirements = {"id"="\d+"},
     * )
     * @Rest\View(statusCode = 204)
     * 
     */
    public function deleteUser(User $user){
        if($user->getStore() == $this->getUser()){
            $this->em->remove($user);
            $this->em->flush();
        }
        else{
            throw new AccessDeniedException('You are not allowed to suppress this user.');
        }
    }
}
