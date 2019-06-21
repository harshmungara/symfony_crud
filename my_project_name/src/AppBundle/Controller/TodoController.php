<?php

namespace AppBundle\Controller;

use AppBundle\Entity\todo;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class TodoController extends Controller
{
    /**
     * @Route("/todo", name="todo")
     */
    public function indexAction(Request $request)
    {
        $todos = $this->getDoctrine()
        ->getRepository('AppBundle:todo')
        ->findAll();
        // replace this example code with whatever you need
        return $this->render('todo/index.html.twig', array('todos' => $todos ));
    }

    /**
     * @Route("/todo/create", name="todo_create")
     */
    public function createAction(Request $request)
    {
        $todo = new Todo;

        $form = $this->createFormBuilder($todo)
                ->add('name', TextType::class,array('attr' => array('class' => 'form-control' , 'style' => 'margin-bottom:15px')))
                ->add('description', TextareaType::class,array('attr' => array('class' => 'form-control' , 'style' => 'margin-bottom:15px')))
                ->add('priority', ChoiceType::class,array('choices' => array('Low' => 'Low' , 'Normal' => 'Normal' , 'High' => 'High'),'attr' => array('class' => 'form-control' , 'style' => 'margin-bottom:15px')))
                ->add('due_date', DateTimeType::class,array('attr' => array('class' => 'formcontrol' , 'style' => 'margin-bottom:15px')))
                ->add('create_date', TextType::class,array('attr' => array('class' => 'form-control' , 'style' => 'margin-bottom:15px')))

                ->add('save', SubmitType::class,array('label' => 'Create Todo','attr' => array('class' => 'btn btn-primary' , 'style' => 'margin-bottom:15px')))
                ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    //get data
                    $name = $form['name']->getData();
                    $description = $form['description']->getData();
                    $priority = $form['priority']->getData();
                    $due_date = $form['due_date']->getData();
                    $create_date = $form['create_date']->getData();
                 //   $now = new\DateTime('now');

                    $todo->setName($name);
                    $todo->setDescription($description);
                    $todo->setPriority($priority);
                    $todo->setDueDate($due_date);
                    $todo->setCreateDate($create_date);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($todo);
                    $em->flush();

                    $this->addFlash(
                        'notice',
                        'Todo Added'
                    ) ;

                    return $this->redirectToRoute('todo');
                }
        // replace this example code with whatever you need
        return $this->render('todo/create.html.twig', array(
            'form' => $form ->createView()
        ));
    }

    /**
     * @Route("/todo/edit/{id}", name="todo_edit")
     */
    public function editAction($id,Request $request)
    {
        
         $todo = $this->getDoctrine()
        ->getRepository('AppBundle:todo')
        ->find($id);

        $todo->setName($todo->getName());
        $todo->setDescription($todo->getDescription());
        $todo->setPriority($todo->getPriority());
        $todo->setDueDate($todo->getDueDate());
        $todo->setCreateDate($todo->getCreateDate());

        $form = $this->createFormBuilder($todo)
                ->add('name', TextType::class,array('attr' => array('class' => 'form-control' , 'style' => 'margin-bottom:15px')))
                ->add('description', TextareaType::class,array('attr' => array('class' => 'form-control' , 'style' => 'margin-bottom:15px')))
                ->add('priority', ChoiceType::class,array('choices' => array('Low' => 'Low' , 'Normal' => 'Normal' , 'High' => 'High'),'attr' => array('class' => 'form-control' , 'style' => 'margin-bottom:15px')))
                ->add('due_date', DateTimeType::class,array('attr' => array('class' => 'formcontrol' , 'style' => 'margin-bottom:15px')))
                ->add('create_date', TextType::class,array('attr' => array('class' => 'form-control' , 'style' => 'margin-bottom:15px')))

                ->add('save', SubmitType::class,array('label' => 'Edit Todo','attr' => array('class' => 'btn btn-primary' , 'style' => 'margin-bottom:15px')))
                ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                    //get data
                    $name = $form['name']->getData();
                    $description = $form['description']->getData();
                    $priority = $form['priority']->getData();
                    $due_date = $form['due_date']->getData();
                    $create_date = $form['create_date']->getData();
                 //   $now = new\DateTime('now');

                    $em = $this->getDoctrine()->getManager();
                    $todo = $em->getRepository('AppBundle:todo')->find($id);

                    $todo->setName($name);
                    $todo->setDescription($description);
                    $todo->setPriority($priority);
                    $todo->setDueDate($due_date);
                    $todo->setCreateDate($create_date);

                    
                    $em->flush();

                    $this->addFlash(
                        'notice',
                        'Todo Updated'
                    ) ;

                    return $this->redirectToRoute('todo');
                }
        
        return $this->render('todo/edit.html.twig', array(
            'todo' => $todo,
            'form' => $form->createView()

    ));
   
     return $this->render('todo/edit.html.twig');
    }

    /**
     * @Route("/todo/detail/{id}", name="todo_detail")
     */
    public function detailAction($id)
    {
        
        $todo = $this->getDoctrine()
        ->getRepository('AppBundle:todo')
        ->find($id);
        // replace this example code with whatever you need
        return $this->render('todo/detail.html.twig', array('todo' => $todo ));
    }
    

    /**
     * @Route("/todo/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id)
    {
        
        $em = $this->getDoctrine()->getManager();
        $todo = $em->getRepository('AppBundle:todo')->find($id);

        $em->remove($todo);
        $em->flush();


        $this->addFlash(
               'alert',
               'Todo Removed'
             ) ;

        return $this->redirectToRoute('todo');

    }
}