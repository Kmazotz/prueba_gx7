<?php

namespace App\Controller;

use App\Entity\Areas;
use App\Entity\Empleado;
use App\Entity\EmpleadoRol;
use App\Entity\Roles;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmpleadoController extends AbstractController
{

    public iterable $empleados;

    public function __construct()
    {
        $this->empleados = array();
    }

    #[Route('/empleado', name: 'app_empleado')]
    public function index(): Response
    {
        return $this->render('empleado/index.html.twig', [
            'controller_name' => 'EmpleadoController',
        ]);
    }

    #[Route('/empleado/list', name: 'empleado_list', methods: 'GET')]
    public function List(ManagerRegistry $doctrine): Response
    {
        try {

            $getEmpleados = $doctrine
                    ->getRepository(Empleado::class)
                    ->findAll();
        
            $this->empleados = [];
            
            foreach($getEmpleados as $empleado)
            {

                $this->empleados[] = [
                    'id' => $empleado->getId(),
                    'nombre' => $empleado->getNombre(),
                    'email' => $empleado->getEmail(),
                    'sexo' => $empleado->getSexo(),
                    'area' => $empleado->getAreaId(),
                    'boletin' => $empleado->getBoletin(),
                    'descripcion' => $empleado->getDescripcion(),
                ];
            }
            
            return $this->json([
                'title' => 'Success',
                'status' => true,
                'message' => count($this->empleados) > 0 ? 'Success!!' : 'No records found.',
                'data' => $this->empleados,
                'path' => 'src/Controller/EmpleadoController.php'
            ]);

        } catch (\Exception $e) {
            
            return $this->json([
                'title' => 'Error',
                'status' => false,
                'message' => "An exception occurred: {$e->getMessage()}",
                'path' => 'src/Controller/EmpleadoController.php'
            ]);
        }
    }

    #[Route('/empleado/findbyid/{id}', name: 'empleado_by_id', methods: 'GET')]
    public function FindById(ManagerRegistry $doctrine, int $id) : Response
    {
        try {
            $findEmpleadoById = $doctrine
                            ->getRepository(Empleado::class)
                            ->find($id);

            if($findEmpleadoById)
            {
                $this->empleados[] = [
                    'id' => $findEmpleadoById->getId(),
                    'nombre' => $findEmpleadoById->getNombre(),
                    'email' => $findEmpleadoById->getEmail(),
                    'sexo' => $findEmpleadoById->getSexo(),
                    'area' => $findEmpleadoById->getAreaId(),
                    'boletin' => $findEmpleadoById->getBoletin(),
                    'descripcion' => $findEmpleadoById->getDescripcion(),
                ];

                return $this->json([
                    'title' => 'Success',
                    'status' => true,
                    'message' => 'Success!!',
                    'data' => $this->empleados,
                    'path' => 'src/Controller/EmpleadoController.php'
                ]);

            }else{

                return $this->json([
                    'title' => 'Success',
                    'status' => true,
                    'message' => 'No records found.',
                    'path' => 'src/Controller/EmpleadoController.php'
                ]);
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

    #[Route('/empleado/store', name: 'store_empleado', methods: 'POST')]
    public function store(ManagerRegistry $doctrine, Request $request) : Response
    {
        try {

            if($request->getContent() !== null)
            {

                $entityManager = $doctrine->getManager();

                $empleado = new Empleado();
                $search = $request->getContent();

                if(isset($search))
                {
                    $sexo = array("F", "M");

                    $data = json_decode($search, true);

                    $empleado->setNombre($data["nombre"]);
                    $empleado->setEmail($data["email"]);

                    if(!in_array(strtoupper($data["sexo"]), $sexo, true)){

                        throw new Exception("Only <F> or <M> value is allowed.");
                    }

                    $empleado->setSexo(strtoupper($data["sexo"]));

                    $findArea = $doctrine
                                ->getRepository(Areas::class)
                                ->find($data["area_id"]);

                    if(!$findArea)
                    {
                        throw new Exception("No record found for id: {$data["area_id"]}.");
                    }

                    $empleado->setAreaId($findArea);
                    $empleado->setBoletin($data["boletin"]);
                    $empleado->setDescripcion($data["descripcion"]);

                    if(count($data["roles"]) > 0){

                        $entityManager->persist($empleado);
                        $entityManager->flush();

                        foreach($data["roles"] as $role){

                            $empleadoRol = new EmpleadoRol();
                            $entityManager = $doctrine->getManager();
                            
                            $findEmpleado = $doctrine
                                ->getRepository(Empleado::class)
                                ->find($empleado->getId());

                            if(!$findEmpleado)
                            {
                                throw new Exception("No record found for id: {$empleado->getId()}.");
                            }
                            
                            $empleadoRol->setEmpleado($findEmpleado);

                            $findRole = $doctrine
                                ->getRepository(Roles::class)
                                ->find($role);

                            if(!$findRole)
                            {
                                throw new Exception("No record found for id: {$role}.");
                            }

                            $empleadoRol->setRole($findRole);

                            $entityManager->persist($empleadoRol);
                            $entityManager->flush();
                        }

                    }else{

                        return $this->json([
                            'title' => 'Error',
                            'status' => true,
                            'message' => "Roles is required",
                            'path' => 'src/Controller/EmpleadoController.php'
                        ]);
                    }

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record was stored with the id: {$empleado->getId()}",
                        'path' => 'src/Controller/EmpleadoController.php'
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
                'path' => 'src/Controller/EmpleadoController.php'
            ]);
        }
    }

    #[Route('/empleado/update', name: 'empleado_area', methods: 'POST')]
    public function update(ManagerRegistry $doctrine, Request $request): Response
    {
        try {

            if($request->getContent() !== null)
            {
                $entityManager = $doctrine->getManager();
                $search = $request->getContent();

                if(isset($search))
                {

                    $sexo = array("F", "M");

                    $data = json_decode($search, true);

                    $empleado = $entityManager->getRepository(Empleado::class)->find($data["id"]);

                    if(!$empleado)
                    {

                        throw new Exception("No record found for id: {$data["id"]}.");
                    }

                    $empleado->setNombre($data["nombre"]);
                    $empleado->setEmail($data["email"]);

                    if(!in_array(strtoupper($data["sexo"]), $sexo, true)){

                        throw new Exception("Only <F> or <M> value is allowed.");
                    }

                    $empleado->setSexo(strtoupper($data["sexo"]));

                    $findArea = $doctrine
                                ->getRepository(Areas::class)
                                ->find($data["area_id"]);

                    if(!$findArea)
                    {
                        throw new Exception("No record found for id: {$data["area_id"]}.");
                    }

                    $empleado->setAreaId($findArea);
                    $empleado->setBoletin($data["boletin"]);
                    $empleado->setDescripcion($data["descripcion"]);
                    $entityManager->flush();

                    return $this->json([
                        'title' => 'Success',
                        'status' => true,
                        'message' => "The record with id {$empleado->getId()} was updated.",
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

    #[Route('/empleado/delete', name: 'delete_empleado', methods: 'POST')]
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

                    $empleado = $entityManager->getRepository(Empleado::class)->find($data["id"]);

                    if(!$empleado)
                    {

                        throw new Exception("No record found for id {$data["id"]}.");
                    }

                    $entityManager->remove($empleado);
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
