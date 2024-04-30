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

class PanierController extends AbstractController
{
    #[Route('/Home', name: 'app_panier')]
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $panierItems = $entityManager->getRepository(Produit::class)->findAll();
        return $this->render('client/index.html.twig', [
            'controller_name' => 'PanierController',
            'panierItems' => $panierItems,
        ]);
    }
    #[Route('/list', name: 'app_list')]
    public function indexc(Request $request): Response
    {   
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find(80);
        $searchTerm = $request->query->get('q');
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('p')
                     ->from(Produit::class, 'p')
                     ->where('p.quantiteProduit >=0');
                     if ($searchTerm) {
                        $queryBuilder->andWhere('p.nomProduit LIKE :searchTerm')
                                     ->setParameter('searchTerm', '%'.$searchTerm.'%');
                    }
        $produits = $queryBuilder->getQuery()->getResult();
    
        return $this->render('client/components/list.html.twig', [
            'b' => $produits,
            'idUser' => $user->getIdUser(),
        ]);
    }

    #[Route('/admin', name: 'app_admin')]
    public function indexpro(): Response
    {
        $produit = $this->getDoctrine()->getManager()->getRepository(Produit::class)->findAll();
        return $this->render('admin/component/products.html.twig', [
            'b'=>$produit
        ]);
    }

    #[Route('/add_to_basket/{idProduit}/{userId}', name: 'add_to_basket')]
public function addToBasket(int $idProduit): Response
{   
    
    $entityManager = $this->getDoctrine()->getManager();

    $produit = $entityManager->getRepository(Produit::class)->find($idProduit);
    
    if ($produit && $produit->getQuantiteProduit() > 0) {

        $produit->setQuantiteProduit($produit->getQuantiteProduit() - 1);
        $user = $entityManager->getRepository(User::class)->find(80);
    $panier = new Panier();
    $panier->setProduit($produit);
    $panier->setIdUser($user->getidUser());
    $entityManager->persist($panier);
    $entityManager->flush();
    $this->addFlash('success', 'The product has been added to the basket.');
    return $this->redirectToRoute('app_list');
} else {
    return $this->redirectToRoute('app_list');
}
}
#[Route('/panier/{idUser}', name: 'app_pan')]
    public function indexpanier(int $idUser): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
    $panierItems = $entityManager->getRepository(Panier::class)->findBy(['idUser' => $idUser,'status' => 1 ]);

    $totalPrice = 0;
    
    foreach ($panierItems as $item) {
        $totalPrice += $item->getProduit()->getPrixProduit();
        
    }
   
        return $this->render('client/components/panier.html.twig', [
            'controller_name' => 'PanierController',
            'idUser' => $idUser,
            'panierItems' => $panierItems,
            'totalPrice' => $totalPrice,
        ]);
        
    }

    #[Route('/delpanier/{idProduit}', name: 'del_pan')]
    public function deleteProduit(int $idProduit): Response
    {
    $entityManager = $this->getDoctrine()->getManager();
    $panier = $entityManager->getRepository(Panier::class)->find($idProduit);
    if (!$panier) {
        throw $this->createNotFoundException('Product not found in the basket');
    }
    $produit = $panier->getProduit();
    if ($produit) {
        $produit->setQuantiteProduit($produit->getQuantiteProduit() + 1);
        $entityManager->remove($panier);
        $entityManager->flush();
        return $this->redirectToRoute('app_pan', ['idUser' => $panier->getIdUser()]);
    } 
    }  
    

}