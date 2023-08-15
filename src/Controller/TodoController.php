<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
class TodoController extends AbstractController
{
    /**
     * @Route("/todo/add", name="app_todo_add")
     */
    public function add(ManagerRegistry $doctrine, Request $req): Response
    {
        $message = "";
        
        $task = new Task();
        $form = $this->createForm(TaskFormType::class, $task);
        // $task -> setTask('Write a blog post');
        // $task -> setDueDate(new \DateTime('tomorrow'));
        // $formBuilder = $this->createFormBuilder($task);
        // $formBuilder -> add('task', TextType::class);
        // $formBuilder -> add('dueDate', DateType::class);
        // $formBuilder -> add('save', SubmitType::class, ['label' => 'Create Task']);
        // $form = $formBuilder -> getForm();
        $form->handleRequest ($req) ;
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData() ;
            $entityManager = $doctrine->getManager();
            $entityManager->persist($task); //lưu vào cơ sở dữ liệu
            $entityManager->flush();
            $message = "A new task has been added!";
        }
        return $this -> renderForm('todo/add.html.twig', [
            'form' => $form,
            'message' => $message
        ]);
    }

    /**
     * @Route("/todo/edit/{id}", name="app_todo_edit")
     */
    public function edit(ManagerRegistry $doctrine, Request $req, int $id): Response
    {
        $message = "";
        $entityManager = $doctrine->getManager();
        $taskRepo = $entityManager->getRepository(Task::class);
        $task = $taskRepo->find($id);
        $form = $this->createForm(TaskFormType::class, $task);
        $form->handleRequest ($req) ;
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); //
            $message = "Edit task successfully!";
        }
        return $this -> renderForm('todo/add.html.twig', [
            'form_title' => 'Edit task',
            'form' => $form,
            'message' => $message
        ]);
    }

    /**
     * @Route("/todo/delete/{id}", name="app_todo_delete")
     */
    public function delete(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $taskRepo = $entityManager->getRepository(Task::class);
        $task = $taskRepo->find($id);
        $entityManager->remove($task);
        $entityManager->flush(); //
        return $this -> redirect('app_todo');
    }
    
    /**
     * @Route("/todo", name="app_todo")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $taskRepo = $doctrine->getRepository(Task::class);
        $tasks = $taskRepo->findAll();
        
        return $this->render('todo/index.html.twig', [
            'tasks' => $tasks
        ]);
    }
}