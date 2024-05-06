<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;

class StatisticsController extends AbstractController
{
    #[Route('/statss', name: 'apps_statistics')]
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