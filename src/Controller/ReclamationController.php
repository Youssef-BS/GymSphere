<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\User;

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
use Knp\Component\Pager\PaginatorInterface;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ReclamationController extends AbstractController
{
    #[Route('/reclamation/add/Client/{user_id}', name: 'add_reclamation_form', methods: ['GET' , 'POST'])]
    public function createReclamation(Request $request, EntityManagerInterface $entityManager , int $user_id  ): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $entityManager->getRepository(User::class)->find($user_id);
    
        if (!$user) {
            throw $this->createNotFoundException('No user found for id '.$user_id);
        }
    
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setIduser($user_id);  // Assuming you have a setUser method in your Reclamation entity
    
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            $this->addFlash('success', 'Réclamation ajoutée avec succès.');
    
            return $this->redirectToRoute('front_reclamation', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('reclamation/ClientView/addReclamation.html.twig', [
            'user_id' => $user_id, 
            'form' => $form->createView(),
            'reclamation' => $reclamation,
        ]);
    }
    

    #[Route('/reclamation', name: 'reclamation')]
    public function reclamation(ReclamationRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $reclamations = $repo->findAll();
    
        $typesStats = [];
        foreach ($reclamations as $reclamation) {
            $type = $reclamation->getType();
            if (!isset($typesStats[$type])) {
                $typesStats[$type] = 1;
            } else {
                $typesStats[$type]++;
            }
        }
    
        $reclamationsPaginated = $paginator->paginate(
            $reclamations, 
            $request->query->getInt('page', 1), 
            3 
        );
    
        // Generate QR Codes
        $qrCodes = [];
        foreach ($reclamations as $reclamation) {
            $qrCode = new QrCode(json_encode([
                'id' => $reclamation->getId(),
                'title' => $reclamation->getTitre(),
                'description' => $reclamation->getDescription(),
                'type' => $reclamation->getType(),
                'date' => $reclamation->getDateReclamation()->format('Y-m-d'),
            ]));
            $writer = new PngWriter();
            $qrCodes[$reclamation->getId()] = $writer->write($qrCode)->getDataUri(); // Store data URI
        }
    
        return $this->render('admin/component/reclamation.html.twig', [
            'reclamations' => $reclamationsPaginated,
            'typesStats' => $typesStats,
            'qrCodes' => $qrCodes, // Pass QR codes to Twig
        ]);
    }
    

    // #[Route('/reclamation/{id}', name: 'reclamation_details')]
    // public function reclamationDetails(Reclamation $reclamation): Response
    // {
    //     return $this->render('reclamation/details.html.twig', [
    //         'reclamation' => $reclamation,
    //     ]);
    // }

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
    #[Route('/reclamation/Client/{user_id}/{id}/delete', name: 'delete_reclamation_client')]
    public function deleteReclamationClient(int $id, ReclamationRepository $repo , int $user_id): RedirectResponse
    {
        $reclamation = $repo->find($id);

        if (!$reclamation) {
            throw $this->createNotFoundException('Reclamation not found');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($reclamation);
        $entityManager->flush();

        // Redirect back to the reclamation list page
        return $this->redirectToRoute('front_reclamation', ['user_id' => $user_id], Response::HTTP_SEE_OTHER);
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
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('YourAppName');
    $pdf->SetAuthor('YourName');
    $pdf->SetTitle('Reclamation PDF');

    // Add a page
    $pdf->AddPage();

    // Set font size for date and time
    $pdf->SetFont('helvetica', '', 10);

    // Show actual date on top left of the page
    $pdf->Cell(60, 10, 'Date: ' . date('Y-m-d H:i:s'), 0, 1, 'L');

    // Set font size for logo
    $pdf->SetFont('helvetica', '', 12);

    // Add your logo on top right
    $logoPath = realpath($this->getParameter('kernel.project_dir')) . '/public/images/gym.png';
    $pdf->Image($logoPath, 150, 10, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false);

    // Set font size for title "Reclamation Details" and move it to the right
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 20, 'Reclamation Details', 0, 1, 'R');

    // Set font size for table headers and adjust position
    $pdf->SetFont('helvetica', 'B', 12);

    // Move down to avoid overlapping with the image
    $pdf->Ln(30);

    // Create a table to display reclamation details
    $html = '<table border="1" style="width: 100%;">
                <tr style="background-color: #ccc;">
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Type</th>
                </tr>
                <tr>
                    <td>' . $reclamation->getId() . '</td>
                    <td>' . $reclamation->getTitre() . '</td>
                    <td>' . $reclamation->getDescription() . '</td>
                    <td>' . $reclamation->getType() . '</td>
                </tr>
            </table>';

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





#[Route('/send-message', name: 'send_message', methods: ['POST'])]
public function sendMessage(Request $request, HttpClientInterface $httpClient): JsonResponse
{
    $content = json_decode($request->getContent(), true);
    $message = $content['message'] ?? '';

    if (!$message) {
        return $this->json(['error' => 'No message provided'], Response::HTTP_BAD_REQUEST);
    }

    try {
        $response = $httpClient->request('POST', 'https://api.openai.com/v1/engines/davinci-codex/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getParameter('env.OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'prompt' => $message,
                'max_tokens' => 150,
            ],
        ]);

        $data = $response->toArray();

        if (isset($data['choices'][0]['text'])) {
            $reply = $data['choices'][0]['text'];
        } else {
            // Log this issue, it means the expected path in the response doesn't exist
            $reply = 'Sorry, I am unable to process that.';
        }
    } catch (\Exception $e) {
        // Handle and log the exception
        $reply = 'There was an error processing your request.';
    }

    return new JsonResponse(['reply' => $reply]);
}




///////////////////// ClientFunctions ///////////////////////////


#[Route('/reclamation/list/Client/{user_id}', name: 'front_reclamation')]
public function reclamationClient(EntityManagerInterface $entityManager, ReclamationRepository $repo, Request $request, int $user_id): Response
{
    $user = $entityManager->getRepository(User::class)->find($user_id);
    $reclamations = $repo->findBy(['iduser' => $user]);

    return $this->render('reclamation/ClientView/HomeReclamation.html.twig', [
        'reclamations' => $reclamations,
        'user_id' => $user_id,
    ]);
}

    
}
