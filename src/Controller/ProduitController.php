<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Intl\Locales;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Form\UserChoiceType;

class ProduitController extends AbstractController
{
   

    
    #[Route('/admin', name: 'app_produit')]
    public function index(): Response
    {
        $produit = $this->getDoctrine()->getManager()->getRepository(Produit::class)->findAll();
        

        return $this->render('admin/index.html.twig', [
            'b'=>$produit
        ]);
    }
    #[Route('/addproduit', name: 'add_produit')]
    public function addProduit(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $existingProduit = $this->getDoctrine()->getRepository(Produit::class)->findOneBy(['nomProduit' => $produit->getNomProduit(), 'prixProduit' => $produit->getPrixProduit()]);
            if ($existingProduit) {
                $existingProduit->setQuantiteProduit($existingProduit->getQuantiteProduit() + 1);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', 'Product quantity updated successfully.');
            } else {
            
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_produit_new');
                }
                $produit->setphotoProduit('frontend/assets/images/'.$newFilename);
        }
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            $this->addFlash('success', 'The product has been added!');
            return $this->redirectToRoute('app_admin');
        }}
        return $this->render('produit/form.html.twig',['f'=>$form->createView()]);
    }

    #[Route('/delproduit/{idProduit}', name: 'del_produit')]
    public function deleteProduit(int $idProduit): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($idProduit);

        if (!$produit) {
            throw $this->createNotFoundException('Product not found');
        }

        $entityManager->remove($produit);
        $entityManager->flush();
        $this->addFlash('success', 'The product has been deleted');
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/updateproduit/{idProduit}', name: 'up_produit')]
    public function updateProduit(Request $request, int $idProduit): Response
    {
        $produit = $this->getDoctrine()->getManager()->getRepository(Produit::class)->find($idProduit);
        $form = $this->createForm(UserChoiceType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_produit_new');
                }
                $produit->setphotoProduit('frontend/assets/images/'.$newFilename);
        }
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'The product has been updated!');
            return $this->redirectToRoute('up_produit', ['idProduit' => $idProduit]);
        }
        return $this->render('admin/component/produitDetails.html.twig',['f'=>$form->createView(),'produit' => $produit]);
    }
    
    #[Route('/product', name: 'app_admin')]
    public function indexc(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $produitRepository = $entityManager->getRepository(Produit::class);
        $searchTerm = $request->query->get('q');
        if ($searchTerm) {
            $produits = $produitRepository->createQueryBuilder('p')
                ->where('p.nomProduit LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$searchTerm.'%')
                ->getQuery()
                ->getResult();
        } else {
            $produits = $produitRepository->findAll();
        }

        return $this->render('admin/component/products.html.twig', [
            'b'=>$produits
        ]);
    }
    #[Route('/admin/product', name: 'prod_admin')]
    public function indexproduit(): Response
    {
        $produit = $this->getDoctrine()->getManager()->getRepository(Produit::class)->findAll();
        return $this->render('admin/component/produitDetails.html.twig', ['b'=>$produit ]);
    }
    #[Route('/change_price/{idProduit}/{discount}', name: 'change_price')]
    public function changePrice(int $idProduit, int $discount): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $product = $entityManager->getRepository(Produit::class)->find($idProduit);
        $originalPrice = $product->getPrixProduit();
        $discountedPrice = $originalPrice * ( 1 - ($discount / 100)); 
        $product->setPrixProduit($discountedPrice);
        $entityManager->flush();
        $this->addFlash('success', 'The product has been offred');
        return $this->redirectToRoute('app_admin'); 
    }

    #[Route('/stats', name: 'app_statistics')]
    public function productStats(EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT p.idProduit, COUNT(p.idProduit) AS occurrence
            FROM App\Entity\Panier p
            GROUP BY p.idProduit
            ORDER BY occurrence DESC'
        );
        
    $productsOccurrence = $query->getResult();

        return $this->render('statistics/index.html.twig', [
            'productsOccurrence' => $productsOccurrence,
        ]);
    }


}   
