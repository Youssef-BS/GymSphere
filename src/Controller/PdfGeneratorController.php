<?php
 
 namespace App\Controller;

 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Annotation\Route;
 use Symfony\Component\HttpFoundation\Request;
 use Dompdf\Dompdf;
 use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;

 class PdfGeneratorController extends AbstractController
 {
     #[Route('/pdf/generator', name: 'app_pdf_generator')]
     public function index(Request $request, PanierRepository $panierRepository, EntityManagerInterface $entityManager): Response
{
    $totalCost = $request->query->get('total');
    if ($totalCost === null) {
        // Handle the case where total is missing or invalid
        return new Response('Missing or invalid total parameter', Response::HTTP_BAD_REQUEST);
    }

    // Data to pass to the view
    $data = [
        'name'    => 'mohsen Doe',
        'address' => 'USA',
        'Prix'    => $totalCost,
        'email'   => 'jyhed.doe@email.com'
    ];

    // Query to fetch command details
    $query = $entityManager->createQuery(
        'SELECT c.id, c.total FROM App\Entity\Commande c'
    );
    $commandes = $query->getResult();

    // Calculate the total price of all commandes
    $totalCommandePrice = 0;
    foreach ($commandes as $commande) {
        $totalCommandePrice += $commande['total'];
    }

    // Generate HTML from Twig template
    $html = $this->renderView('pdf_generator/index.html.twig', [
        'data'               => $data,
        'commandes'          => $commandes,
        'totalCommandePrice' => $totalCommandePrice // Pass the total price of commandes to the template
    ]);

    // Initialize Dompdf
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);

    // Render the PDF
    $dompdf->render();

    // Create a Response to trigger file download
    return new Response(
        $dompdf->stream('FACTURE.pdf', ["Attachment" => true]),
        Response::HTTP_OK,
        ['Content-Type' => 'application/pdf']
    );
}
 
     private function imageToBase64($path) {
         $path = $path;
         $type = pathinfo($path, PATHINFO_EXTENSION);
         $data = file_get_contents($path);
         $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
         return $base64;
     }
 }