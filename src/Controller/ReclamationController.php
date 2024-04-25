<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpFoundation\StreamedResponse;
use TCPDF;

use PHPExcel;
use PHPExcel_IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;   

class ReclamationController extends AbstractController
{
    #[Route('/reclamation', name: 'reclamation')]
    public function reclamation(ReclamationRepository $repo): Response
    {
        $reclamations = $repo->findAll();

        // Calcul des statistiques par type de réclamation
        $typesStats = [];
        foreach ($reclamations as $reclamation) {
            $type = $reclamation->getType();
            if (!isset($typesStats[$type])) {
                $typesStats[$type] = 1;
            } else {
                $typesStats[$type]++;
            }
        }

        return $this->render('admin/component/reclamation.html.twig', [
            'reclamations' => $reclamations,
            'typesStats' => $typesStats, // Passer les statistiques au template Twig
        ]);
    }

    #[Route('/reclamation/{id}', name: 'reclamation_details')]
    public function reclamationDetails(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/details.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/reclamation/{id}/delete', name: 'delete_reclamation')]
    public function deleteReclamation(int $id, ReclamationRepository $repo): RedirectResponse
    {
        $reclamation = $repo->find($id);

        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reclamation);
        $entityManager->flush();

        // Redirect back to the reclamation list page
        return $this->redirectToRoute('reclamation');
    }

    #[Route('/addReclamationPage', name: 'add_reclamation_page')]
    public function addReclamationPage(): Response
    {
        return $this->render('reclamation/addReclamation.html.twig');
    }

    #[Route('/reclamation/add', name: 'add_reclamation_form', methods: ['POST'])]
    public function createReclamation(Request $request): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('success', 'Réclamation ajoutée avec succès.');

            return $this->redirectToRoute('add_reclamation_page');
        }

        return $this->redirectToRoute('add_reclamation_page');
    }
    
  #[Route('/reclamation/{id}/edit', name: 'edit_reclamation')]
public function editReclamation(Request $request, int $id, ReclamationRepository $repo): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $reclamation = $repo->find($id);

    if (!$reclamation) {
        throw $this->createNotFoundException('Reclamation not found');
    }

    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupérer les données du formulaire et les appliquer à l'entité Reclamation
        $formData = $form->getData();
        $reclamation->setTitre($formData->getTitre());
        $reclamation->setDescription($formData->getDescription());
        $reclamation->setType($formData->getType());
        $reclamation->setDateReclamation($formData->getDateReclamation());

        // Pas besoin de persister l'entité ici car elle est déjà gérée par Doctrine

        $entityManager->flush();

        return $this->redirectToRoute('reclamation');
    }

    return $this->render('reclamation/details.html.twig', [
        'form' => $form->createView(),
        'reclamation' => $reclamation, 
    ]);
}

    


    
public function generatePdf(int $reclamationId, ContainerInterface $container): Response
{
    // Retrieve the reclamation by ID
    $entityManager = $container->get('doctrine')->getManager();
    $reclamation = $entityManager->getRepository(Reclamation::class)->find($reclamationId);

    if (!$reclamation) {
        throw $this->createNotFoundException('Reclamation not found');
    }

    // Create a new TCPDF instance
    $pdf = new TCPDF();

    // Set document information
    $pdf->SetCreator('YourAppName');
    $pdf->SetAuthor('YourName');
    $pdf->SetTitle('Reclamation PDF');

    // Add a page
    $pdf->AddPage();

    // Include your logo and design elements
    // $pdf->Image('path/to/your/logo.png', 10, 10, 50, 0, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

    // Render the PDF content using Twig
    $html = $this->renderView('pdf_template.html.twig', ['reclamation' => $reclamation]);

    // Render HTML content as PDF
    $pdf->writeHTML($html);

    // Output the PDF as a response
    return new Response($pdf->Output('reclamation.pdf', 'I'), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="reclamation.pdf"',
    ]);
}



public function exportReclamationsExcel(ReclamationRepository $repo): Response
{
    $reclamations = $repo->findAll();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Date');
    $sheet->setCellValue('C1', 'Description');
    $sheet->setCellValue('D1', 'Type');
    // Add other columns as needed

    $row = 2;
    foreach ($reclamations as $reclamation) {
        $sheet->setCellValue('A' . $row, $reclamation->getId());
        $sheet->setCellValue('B' . $row, $reclamation->getDateReclamation()->format('d/m/Y'));
        $sheet->setCellValue('C' . $row, $reclamation->getDescription());
        $sheet->setCellValue('D' . $row, $reclamation->gettype());
        // Add other cells as needed
        $row++;
    }

    $writer = new Xlsx($spreadsheet);

    $response = new StreamedResponse(function () use ($writer) {
        $writer->save('php://output');
    });

    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Content-Disposition', 'attachment;filename="reclamations.xlsx"');
    $response->headers->set('Cache-Control', 'max-age=0');

    return $response;
}






    
}
