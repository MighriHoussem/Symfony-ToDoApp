<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ToDoListController
 * @package App\Controller
 *
 */
class ToDoListController extends AbstractController
{
    /**
     * @Route("/list", name="to_do_list")
     */
    public function index(): Response
    {
        $rep = $this->getDoctrine()->getRepository(Task::class);
        $tasks = $rep->findBy([],['id'=>'DESC']);
       return $this->render('index.html.twig',[
           'tasks' => $tasks
       ]);
    }

    /**
     * @param Request $request
     * @Route("/create", name="create_task", methods={"POST"})
     * @return RedirectResponse
     */
    public function createToDo(Request $request){
        $title = $request->get("title");
        if(empty($title)){
            return $this->redirectToRoute('to_do_list');
        }
        $em = $this->getDoctrine()->getManager();
        $task = new Task();
        $task->setTitle($title);
        //$task->setStatus(1);
        $em->persist($task);
        $em->flush();
        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @param Request $request
     * @Route("/switch-status/{idTask}", name="switch-status")
     */
    public function switchStatus(Request $request){
        $task = $this->getDoctrine()->getRepository(Task::class)
            ->find($request->get('idTask'));
        $task->setStatus(! $task->getStatus());
        $em = $this->getDoctrine()->getManager();

        $em->persist($task);
        $em->flush();
        return $this->redirectToRoute('to_do_list');
    }

    /**
     * @param Request $request
     * @Route("/delete/{idTask}", name="delete-task")
     * @return RedirectResponse
     */
    public function deleteTask(Request $request)
    {
        $task = $this->getDoctrine()->getRepository(Task::class)
            ->find($request->get('idTask'));
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        return $this->redirectToRoute('to_do_list');
    }
}
