<?php

namespace App\Controller;

use App\Entity\Roles;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoleController extends AbstractController
{
    public iterable $roles;

    public function __construct()
    {
        $this->roles = array();
    }


    #[Route('/role', name: 'app_role')]
    public function index(): Response
    {
        return $this->render('role/index.html.twig', [
            'controller_name' => 'RoleController',
        ]);
    }

    #[Route('/role/list', name: 'role_list', methods: 'GET')]
    public function List(ManagerRegistry $doctrine): Response
    {
        try {

            $getRoles = $doctrine
                    ->getRepository(Role::class)
                    ->findAll();
        
            $this->roles = [];
            
            foreach($getRoles as $role)
            {
                $this->roles[] = [
                    'id' => $role->getId(),
                    'nombre' => $role->getNombre()
                ];
            }
            
            return $this->json([
                'title' => 'Success',
                'status' => true,
                'message' => count($this->roles) > 0 ? 'Success!!' : 'No records found.',
                'data' => $this->roles,
                'path' => 'src/Controller/RoleController.php'
            ]);

        } catch (\Exception $e) {
            
            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/RoleController.php'
            ]);
        }
    }

    #[Route('/role/findbyid/{id}', name: 'role_by_id', methods: 'GET')]
    public function FindById(ManagerRegistry $doctrine, int $id) : Response
    {
        try {
            $findRoleById = $doctrine
                            ->getRepository(Roles::class)
                            ->find($id);

            if($findRoleById)
            {
                return $this->json([
                    'title' => 'Success',
                    'status' => true,
                    'message' => 'Success!!',
                    'data' => $findRoleById,
                    'path' => 'src/Controller/RoleController.php'
                ]);

            }else{

                return $this->json([
                    'title' => 'Success',
                    'status' => true,
                    'message' => 'No records found.',
                    'path' => 'src/Controller/RoleController.php'
                ]);
            }

        } catch (\Exception $e) {

            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/RoleController.php'
            ]);
        }
    }

    #[Route('/role/store', name: 'store_role', methods: 'POST')]
    public function store(ManagerRegistry $doctrine, Request $request) : Response
    {
        try {

            if($request->getContent() !== null)
            {

                $entityManager = $doctrine->getManager();

                $role = new Roles();
                $search = $request->getContent();

                if(isset($search))
                {
                    $data = json_decode($search, true);

                    $role->setNombre($data["nombre"]);

                    $entityManager->persist($role);
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record was stored with the id: {$role->getId()}",
                        'path' => 'src/Controller/RoleController.php'
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
                'path' => 'src/Controller/RoleController.php'
            ]);
        }
    }

    #[Route('/role/update', name: 'update_role', methods: 'POST')]
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

                    $role = $entityManager->getRepository(Roles::class)->find($data["id"]);

                    if(!$role)
                    {

                        throw new Exception("No record found for id: {$data["id"]}.");
                    }

                    $role->setNombre($data["nombre"]);
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record with id {$role->getId()} was updated.",
                        'path' => 'src/Controller/RoleController.php'
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
                'path' => 'src/Controller/RoleController.php'
            ]);
        }
    }

    #[Route('/role/delete', name: 'delete_role', methods: 'POST')]
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

                    $role = $entityManager->getRepository(Roles::class)->find($data["id"]);

                    if(!$role)
                    {

                        throw new Exception("No record found for id {$data["id"]}.");
                    }

                    $entityManager->remove($role);
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record with id {$data["id"]} was delete.",
                        'path' => 'src/Controller/RoleController.php'
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
                'path' => 'src/Controller/RoleController.php'
            ]);
        }
    }
}
