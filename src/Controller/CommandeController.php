<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\UserChoiceType;
use App\Entity\User;
use App\Entity\Commande;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email; 

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(): Response
    {
        $commande = $this->getDoctrine()->getManager()->getRepository(Commande::class)->findAll();
        return $this->render('commande/index.html.twig', [
            'b'=>$commande
        ]);
    }

    #[Route('/confirm/{idUser}', name: 'update_status')]
    public function updateStatus(int $idUser): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    
   
    $user = $entityManager->getRepository(User::class)->find($idUser);
    
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }
    
    
    $panierItems = $entityManager->getRepository(Panier::class)->findBy(['idUser' => $idUser, 'status' => 1]);
    
    $totalPrice = 0;
  
    foreach ($panierItems as $panierItem) {
       
        $panierItem->setStatus(0);
       
        $totalPrice += $panierItem->getProduit()->getprixProduit(); 
    }
   
    $commande = new Commande();
    $commande->setTotal($totalPrice);
    $commande->setUser($user);
    
    $entityManager->persist($commande);
   
    $entityManager->flush();
    
    return $this->redirectToRoute('app_list');
    }


    #[Route('/confirm-commande/{id}', name: 'confirm_commande')]
    public function confirmCommande(Request $request, int $id, MailerInterface $mailer): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $commande = $entityManager->getRepository(Commande::class)->find($id);
        
        if (!$commande) {
            throw $this->createNotFoundException('Commande not found');
        }
        $commande->setCommandeSt(0);
        $entityManager->flush();

        $user = $commande->getUser();


        $email = (new Email())
        ->from('ahmet26chokri@gmail.com')
        ->to('scfldmckl@gmail.com')
        ->subject('Your Order ')
        ->text('Your order has been confirmed.');

        $mailer->send($email);

        return $this->redirectToRoute('app_commande');
    }

}
