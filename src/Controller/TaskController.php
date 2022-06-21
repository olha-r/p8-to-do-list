<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/tasks")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="task_list", methods={"GET"})
     */
    public function list(EntityManagerInterface $entityManager, Security $security): Response
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY') == false) {
            $tasks = $entityManager
                ->getRepository(Task::class)
                ->findBy([
                    'user' => null,
                    'isDone' => false
                ]);
            return $this->render('task/list.html.twig', [
                'tasks' => $tasks
            ]);
        }
        else {
            $myTasks = $this->myList($entityManager);
            return $this->render('task/list.html.twig', [
                'myTasks' =>$myTasks
            ]);
        }

    }

    public function myList(EntityManagerInterface $entityManager): array
    {
        $mytasks = [];
        if ($this->getUser()) {
            $mytasks = $entityManager
                ->getRepository(Task::class)
                ->findBy([
                    'user' => $this->getUser(),
                    'isDone' => false
                ]);
            }
        return $mytasks;
    }

    /**
     * @Route("/create", name="task_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task
                ->setUser($security->getUser());
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/create.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="task_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('TASK_EDIT', $task, "Vous n'êtes pas le propriétaire de cette tâche");

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="task_delete", methods={"POST"})
     */
    public function deleteTask(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('TASK_DELETE', $task, "Vous n'êtes pas le propriétaire de cette tâche");

        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }
        return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/toggle", name="task_toggle")
     */
    public function toggleTask(Task $task, EntityManagerInterface $entityManager)
    {
            $task->toggle(!$task->isDone());
            $entityManager->flush();

        if ($task->isDone()) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
            return $this->redirectToRoute('task_list', [], Response::HTTP_SEE_OTHER);
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme à faire.', $task->getTitle()));
            return $this->redirectToRoute('tasks_done', [], Response::HTTP_SEE_OTHER);
        }

    }

    /**
     * @Route("/done", name="tasks_done")
     */
    public function doneTasks(EntityManagerInterface $entityManager): Response
    {
        $doneTasks = $entityManager
            ->getRepository(Task::class)
            ->findBy(
                ['isDone' => true]
            );

        return $this->render('task/done_tasks_list.html.twig', [
            'doneTasks' => $doneTasks
        ]);

    }
}
