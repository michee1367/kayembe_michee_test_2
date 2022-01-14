<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);  
               
    }
    /**
     * @Route("/api/v1/signup", methods={"POST"})
     */
  
    public function signup(Request $request,SerializerInterface $serializer,EntityManagerInterface $em){
        $jsonRecu=$request->getContent();

         $Enregistrement=$serializer ->deserialize($jsonRecu,User::class,'json');

         $em->persist($Enregistrement);
         $em->flush();


         return $this->json($Enregistrement,201,[]);
     }
      /**
     * @Route("/api/v1/login", methods={"POST"})
     */

    public function login(ManagerRegistry $doctrine,UserRepository $userRepository, Request $request,SerializerInterface $serializer,EntityManagerInterface $em){
        
        
        $jsonRecu=$request->getContent();

        $Enregistrement=$serializer ->deserialize($jsonRecu,User::class,'json');
        $number= $Enregistrement-> number;

        $users= $doctrine-> getRepository(User::class)->findOneBy([
        'number' => $number]);
          $ra=rand(10000,99999);
        if(!$users){
            return $this->json("vous n etes pas enregistre",525,[]);
        }
else{
        $entityManager = $doctrine->getManager();
        $users = $entityManager->getRepository(User::class)->findOneBy([
            'number' => $number]);
        $users->setPassword($ra);
        $entityManager->flush();
        return $this->json($ra,201,[]);
}
        
    }

      /**
     * @Route("/api/v1/verify", methods={"POST"})
     */
    public function otp(ManagerRegistry $doctrine,UserRepository $userRepository, Request $request,SerializerInterface $serializer,EntityManagerInterface $em){
        
        
        $jsonRecu=$request->getContent();

        $Enregistrement=$serializer ->deserialize($jsonRecu,User::class,'json');
        $number= $Enregistrement-> number;
        $password= $Enregistrement-> Password;

        $users= $doctrine-> getRepository(User::class)->findOneBy([
        'number' => $number,
        'Password'=>$password] 
        
    );
    
    $hash = strtotime(date("Y"))."-".rand(1,100)."-".rand(101,200)."-".rand(201,999);
    if(!$users){
        return $this->json("vous n'etes pas enresgistre",400);
    }else{
        return $this->json([
            "token"=>$hash,
            "data"=>$users,
            "message"=>"Vous avez été authentifié"
        ]);
    }
    }
    
}