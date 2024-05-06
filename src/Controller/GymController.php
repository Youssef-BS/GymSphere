<?php

namespace App\Controller;

use App\Entity\Gym;
use App\Controller\event ;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GymRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Controller\preventDefault;
use App\Controller\alert ;

class GymController extends AbstractController
{
   // #[Route('/gym', name: 'app_gym')]
    //public function index(): Response
    //{
     //   return $this->render('gym/index.html.twig', [
       //     'controller_name' => 'GymController',
        //]);
    //}

    // #[Route('/gym/adress', name: 'filter_gyms_by_address')]
    // public function filterGymsByAddress(Request $request, GymRepository $gymRepository): Response
    // {
    //     // Get the address from the form submission
    //     $address = $request->query->get('address');
    
    //     // Query gyms by address
    //     $gyms = $gymRepository->findByAdresse($address);
    
    //     // Render the gyms template with filtered gyms
    //     return $this->render('gyms.html.twig', [
    //         'gyms' => $gyms,
    //     ]);
    // }
    
    #[Route('/gyms', name: 'gyms')]
    public function Gym(Request $req, GymRepository $repo) : Response{
        $address = $req->query->get('address');
        if ($address) {
            $list = $repo->findByAdresse($address);
        }else {
            # code...
            $list = $repo->findAll();
        }
    return $this->render('admin/component/gyms.html.twig',[
        'gyms' => $list
    ]);
    }

    #[Route('/addGym' , name:'add_gym')]
    public function addGym() : Response{
        return $this->render('admin/component/addGym.html.twig');
    }
  
    #[Route('/addGymform', name: 'add_gymform')]
    public function addGymForm(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
    
            $nomgym = $request->request->get('nomgym');
            $adresse = $request->request->get('adresse');
            $imageFile = $request->files->get('photogym');
            if ($imageFile) {
                $imageName = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $imageName
                    );
                } catch (FileException $e) {
               
                }
            }
            
            

            $gym = new Gym();
            $gym->setNomgym($nomgym);
            $gym->setAdresse($adresse);
            if (isset($imageName)) {
                $gym->setPhotogym($imageName);
            }
            
            
            $entityManager->persist($gym);
            $entityManager->flush();
            $this->addFlash('success', 'Gym added successfully.');
            return $this->redirectToRoute('gyms');
        }
    
        return $this->render('gyms');
    }
    

    #[Route('/editGym/{id}', name: 'edit_gym')]
    public function editGym(int $id, GymRepository $repo): Response
    {
        $gym = $repo->find($id);
        
        if (!$gym) {
            throw $this->createNotFoundException('Gym not found');
        }
        
        return $this->render('admin/component/editGym.html.twig', [
            'gym' => $gym,
        ]);
    }
    
    #[Route('/Gym/{id}', name: 'delete_Gym')]
    public function deleteGym(int $id, GymRepository $repo): RedirectResponse
    {
        $Gym = $repo->find($id);
    
        if (!$Gym) {
            throw $this->createNotFoundException('Gym not found');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($Gym);
        $entityManager->flush();
    
        // Redirect back to the Gym list page
        return $this->redirectToRoute('gyms');
    }


    #[Route('/updateGym/{id}', name: 'update_gym')]
    public function update(int $id, Request $request, GymRepository $repo): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $gym = $repo->find($id);
        if (!$gym) {
            throw $this->createNotFoundException('Gym not found for id ' . $id);
        }
        if ($request->isMethod('POST')) {
            $gym->setNomgym($request->request->get('nomgym'));
            $gym->setAdresse($request->request->get('adresse'));
            $entityManager->flush();
            $this->addFlash('success', 'Gym updated successfully.');
            return $this->redirectToRoute('gyms');
        }

        return $this->redirectToRoute('gyms');   
    }

    #[Route('/getGym/{id}', name: 'get_gym')]
    public function getGym(int $id, GymRepository $repo): Response
    {
        $gym = $repo->find($id);

        if (!$gym) {
            throw new NotFoundHttpException('Gym not found');
        }

        return $this->json($gym);
    }




 
   

    

}
