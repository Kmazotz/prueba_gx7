<?php

namespace App\Controller;

use App\Entity\Areas;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AreaController extends AbstractController
{

    public iterable $areas;

    public function __construct()
    {
        $this->area = array();
    }

    #[Route('/area', name: 'app_area')]
    public function index(): Response
    {
        return $this->render('area/index.html.twig', [
            'controller_name' => 'AreaController',
        ]);
    }

    #[Route('/area/list', name: 'area_list', methods: 'GET')]
    public function List(ManagerRegistry $doctrine): Response
    {
        try {

            $getAreas = $doctrine
                    ->getRepository(Areas::class)
                    ->findAll();
        
            $this->areas = [];
            
            foreach($getAreas as $area)
            {
                $this->areas[] = [
                    'id' => $area->getId(),
                    'nombre' => $area->getNombre()
                ];
            }
            
            return $this->json([
                'title' => 'Success',
                'status' => true,
                'message' => count($this->areas) > 0 ? 'Success!!' : 'No records found.',
                'data' => $this->areas,
                'path' => 'src/Controller/AreaController.php'
            ]);

        } catch (\Exception $e) {
            
            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/AreaController.php'
            ]);
        }
    }

    #[Route('/area/findbyid/{id}', name: 'area_by_id', methods: 'GET')]
    public function FindById(ManagerRegistry $doctrine, int $id) : Response
    {
        try {
            $findAreaById = $doctrine
                            ->getRepository(Areas::class)
                            ->find($id);

            if($findAreaById)
            {
                return $this->json([
                    'title' => 'Success',
                    'status' => true,
                    'message' => 'Success!!',
                    'data' => $findAreaById,
                    'path' => 'src/Controller/AreaController.php'
                ]);

            }else{

                return $this->json([
                    'title' => 'Success',
                    'status' => true,
                    'message' => 'No records found.',
                    'path' => 'src/Controller/AreaController.php'
                ]);
            }

        } catch (\Exception $e) {

            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/AreaController.php'
            ]);
        }
    }

    #[Route('/area/store', name: 'store_area', methods: 'POST')]
    public function store(ManagerRegistry $doctrine, Request $request) : Response
    {
        try {

            if($request->getContent() !== null)
            {

                $entityManager = $doctrine->getManager();

                $area = new Areas();
                $search = $request->getContent();

                if(isset($search))
                {
                    $data = json_decode($search, true);

                    $area->setNombre($data["nombre"]);

                    $entityManager->persist($area);
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record was stored with the id: {$area->getId()}",
                        'path' => 'src/Controller/AreaController.php'
                    ]);

                }
                else{
                    throw new Exception("Cannot insert null or empty data.");
                }
            }
            else{
                throw new Exception("Cannot insert null or empty data.");
            }

        } catch (\Exception $e) {

            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/AreaController.php'
            ]);
        }
    }

    #[Route('/area/update', name: 'update_area', methods: 'POST')]
    public function update(ManagerRegistry $doctrine, Request $request): Response
    {
        try {

            if($request->getContent() !== null)
            {
                $entityManager = $doctrine->getManager();
                $search = $request->getContent();

                if(isset($search))
                {
                    $data = json_decode($search, true);

                    $area = $entityManager->getRepository(Areas::class)->find($data["id"]);

                    if(!$area)
                    {

                        throw new Exception("No record found for id: {$data["id"]}.");
                    }

                    $area->setNombre($data["nombre"]);
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record with id {$area->getId()} was updated.",
                        'path' => 'src/Controller/AreaController.php'
                    ]);
                }
                else
                {
                    throw new Exception("Cannot insert null or empty data.");
                }

            } 
            else
            {
                throw new Exception("Cannot insert null or empty data.");
            }
            
        } catch (\Exception $e) {

            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/AreaController.php'
            ]);
        }
    }

    #[Route('/area/delete', name: 'delete_area', methods: 'POST')]
    public function delete(ManagerRegistry $doctrine, Request $request): Response
    {
        try 
        {
            if($request->getContent() !== null)
            {
                $entityManager = $doctrine->getManager();
                $search = $request->getContent();

                if(isset($search))
                {
                    $data = json_decode($search, true);

                    $area = $entityManager->getRepository(Areas::class)->find($data["id"]);

                    if(!$area)
                    {

                        throw new Exception("No record found for id {$data["id"]}.");
                    }

                    $entityManager->remove($area);
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record with id {$data["id"]} was delete.",
                        'path' => 'src/Controller/AreaController.php'
                    ]);

                }
                else
                {
                    throw new Exception("Cannot insert null or empty data.");
                }
            }
            else
            {
                throw new Exception("Cannot insert null or empty data.");
            }

        } catch (\Exception $e) {

            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/AreaController.php'
            ]);
        }
    }

}
