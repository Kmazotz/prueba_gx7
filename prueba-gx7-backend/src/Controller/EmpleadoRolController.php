<?php

namespace App\Controller;

use App\Entity\Empleado;
use App\Entity\EmpleadoRol;
use App\Entity\Roles;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmpleadoRolController extends AbstractController
{

    public iterable $empleadoRoles;

    public function __construct()
    {
        $this->empleadoRoles = array();
    }

    #[Route('/empleado/rol', name: 'app_empleado_rol')]
    public function index(): Response
    {
        return $this->render('empleado_rol/index.html.twig', [
            'controller_name' => 'EmpleadoRolController',
        ]);
    }

    #[Route('/empleado/rol/list', name: 'empleado_role_list', methods: 'GET')]
    public function List(ManagerRegistry $doctrine): Response
    {
        try {

            $getEmpleadoRoles = $doctrine
                    ->getRepository(EmpleadoRol::class)
                    ->findAll();
        
            $this->empleadoRoles = [];
            
            foreach($getEmpleadoRoles as $empleadoRole)
            {
                $this->empleadoRoles[] = [
                    'id' => $empleadoRole->getId(),
                    'empleado' => $empleadoRole->getEmpleado(),
                    'role' => $empleadoRole->getRole()
                ];
            }
            
            return $this->json([
                'title' => 'Success',
                'status' => true,
                'message' => count($this->empleadoRoles) > 0 ? 'Success!!' : 'No records found.',
                'data' => $this->empleadoRoles,
                'path' => 'src/Controller/EmpleadoRolController.php'
            ]);

        } catch (\Exception $e) {
            
            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/EmpleadoRolController.php'
            ]);
        }
    }

    #[Route('/empleado/rol/findbyid/{id}', name: 'empleado_role_by_id', methods: 'GET')]
    public function FindById(ManagerRegistry $doctrine, int $id) : Response
    {
        try {
            $findEmpleadoRoleById = $doctrine
                            ->getRepository(EmpleadoRol::class)
                            ->find($id);

            if($findEmpleadoRoleById)
            {
                $this->empleadoRoles[] = [
                    'id' => $findEmpleadoRoleById->getId(),
                    'empleado' => $findEmpleadoRoleById->getEmpleado(),
                    'role' => $findEmpleadoRoleById->getRole()
                ];

                return $this->json([
                    'title' => 'Success',
                    'status' => true,
                    'message' => 'Success!!',
                    'data' => $this->empleadoRoles,
                    'path' => 'src/Controller/EmpleadoRolController.php'
                ]);

            }else{

                return $this->json([
                    'title' => 'Success',
                    'status' => true,
                    'message' => 'No records found.',
                    'path' => 'src/Controller/EmpleadoRolController.php'
                ]);
            }

        } catch (\Exception $e) {

            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/EmpleadoRolController.php'
            ]);
        }
    }

    #[Route('/empleado/rol/store', name: 'store_empleado_rol', methods: 'POST')]
    public function store(ManagerRegistry $doctrine, Request $request) : Response
    {
        try {

            if($request->getContent() !== null)
            {

                $entityManager = $doctrine->getManager();

                $empleadoRol = new EmpleadoRol();
                $search = $request->getContent();

                if(isset($search))
                {
                    $data = json_decode($search, true);

                    $findEmpleado = $doctrine
                                ->getRepository(Empleado::class)
                                ->find($data["empleado"]);

                    if(!$findEmpleado)
                    {
                        throw new Exception("No record found for id: {$data["empleado"]}.");
                    }

                    $empleadoRol->setEmpleado($findEmpleado);

                    $findRole = $doctrine
                                ->getRepository(Roles::class)
                                ->find($data["role"]);

                    if(!$findRole)
                    {
                        throw new Exception("No record found for id: {$data["role"]}.");
                    }

                    $empleadoRol->setRole($findRole);

                    $entityManager->persist($empleadoRol);
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record was stored with the id: {$empleadoRol->getId()}",
                        'path' => 'src/Controller/EmpleadoRolController.php'
                    ]);

                }
                else
                {
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
                'path' => 'src/Controller/EmpleadoRolController.php'
            ]);
        }
    }

    #[Route('/empleado/rol/update', name: 'update_empleado_rol', methods: 'POST')]
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

                    $empleadoRol = $doctrine
                                ->getRepository(EmpleadoRol::class)
                                ->find($data["empleado_rol"]);

                    if(!$empleadoRol)
                    {
                        throw new Exception("No record found for id: {$data["empleado_rol"]}.");
                    }

                    $findEmpleado = $doctrine
                                ->getRepository(Empleado::class)
                                ->find($data["empleado"]);

                    if(!$findEmpleado)
                    {
                        throw new Exception("No record found for id: {$data["empleado"]}.");
                    }

                    $empleadoRol->setEmpleado($findEmpleado);

                    $findRole = $doctrine
                                ->getRepository(Roles::class)
                                ->find($data["role"]);

                    if(!$findRole)
                    {
                        throw new Exception("No record found for id: {$data["role"]}.");
                    }

                    $empleadoRol->setRole($findRole);
                    
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record with id {$empleadoRol->getId()} was updated.",
                        'path' => 'src/Controller/EmpleadoRolController.php'
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
                'path' => 'src/Controller/EmpleadoRolController.php'
            ]);
        }
    }

    #[Route('/empleado/rol/delete', name: 'delete_empleado_rol', methods: 'POST')]
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

                    $empleadoRol = $entityManager->getRepository(EmpleadoRol::class)->find($data["id"]);

                    if(!$empleadoRol)
                    {

                        throw new Exception("No record found for id {$data["id"]}.");
                    }

                    $entityManager->remove($empleadoRol);
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record with id {$data["id"]} was delete.",
                        'path' => 'src/Controller/EmpleadoController.php'
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
                'path' => 'src/Controller/EmpleadoController.php'
            ]);
        }
    }
}
