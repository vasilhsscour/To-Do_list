<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="to_do_list")
     */
    public function index(): Response
    {
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findBy([], ['id'=>'DESC']);

        return $this->render('index.html.twig', array(
            'tasks' => $tasks
        ));
    }

    /**
     * @Route("/create", name="createTask", methods={"POST"})
     */
    public function createTask(Request $request): Response
    {
        $title = trim($request->request->get('title'));
        
        if (empty($title)) {
            return $this->redirectToRoute('to_do_list');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task;
        $task->setTitle($title);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/switchStatus/{id}", name="switchStatus")
     */
    public function switchStatus($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $task = $entityManager->getRepository(Task::class)->find($id);

        $task->setStatus(! $task->getStatus());

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @Route("/delete/{id}", name="deleteTask")
     */
    public function deleteTask(Task $task): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('to_do_list');
    }
}
