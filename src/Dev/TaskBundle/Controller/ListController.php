<?php

namespace Dev\TaskBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Dev\TaskBundle\Entity\TaskList;
use Dev\TaskBundle\Entity\Task;
use Dev\TaskBundle\Form\TaskListType;
// use Dev\TaskBundle\Form\TaskCompleteType;
//use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * List controller.
 *
 * @Route("/list")
 */
class ListController extends Controller
{

    /**
     * @Route(".json", name="json_list")
     * @Method("GET")
     */
    public function jsonListAction() 
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('DevTaskBundle:TaskList')->findAll();

        $pila = array();
        foreach ($entities as $entity) {
            array_push($pila, array('name' => $entity->getName()));
        }
        $response = new JsonResponse($pila);
        return $response;
    }

    /**
     * @Route("/search", name="search")
     * @Method("GET")
     * @Template()
     */
    public function searchAction()
    {
        return array();
    }


    /**
     * @Route("/search", name="search_term")
     * @Method("POST")
     */
    public function searchTermAction(Request $request)
    {
        $palabra = $request->request->get('palabra');

        // TODO pendiente de implementar
        // 
        // 
        // 
        return new Response($palabra);
    }


    /**
     * @Route("/", name="list")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('DevTaskBundle:TaskList')->findAll();
        
        return array(
            'entities' => $entities,
            //'form'   => $form->createView(),
        );
    }

    /**
     * Displays a inline form to edit an existing Task entity.
     *
     * @Route("/inline-edit", name="list_inline_edit")
     * @Method("POST")
     * 
     */
    public function inlineEditAction(Request $request)
    {
        $id = $request->request->get('id');
        $name = $request->request->get('value');
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }
        
        $entity->setName($name);        
        $em->persist($entity);
        $em->flush();
        
        return new Response($name);
    }

    /**
     * Edita el nombre de una tarea de una lista
     *
     * @Route("/{list_id}/item-update", name="task_inline_edit")
     * @Method("POST")
     * 
     */
    public function inlineTaskEditAction(Request $request)
    {
        $id = $request->request->get('id');
        $name = $request->request->get('value');
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }
        
        $entity->setTask($name);
               
        $em->persist($entity);
        $em->flush();
        
        return new Response($name);
    }
    
    /**
     * Displays a inline form to edit an existing Task entity.
     *
     * @Route("/inline-create", name="list_inline_create")
     * @Method("POST")
     * 
     */
    public function inlineCreateAction(Request $request)
    {
        $name = $request->request->get('value');
        $em = $this->getDoctrine()->getManager();
        $entity = new TaskList();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }
        $entity->setName($name); 
        $entity->setCreated(new \DateTime("now"));      
        $em->persist($entity);
        $em->flush();
        return new Response($name);
    }    
    
    /**
     * Creates a new TaskList entity.
     *
     * @Route("/create", name="list_create")
     * @Method("GET")
     * @Template("DevTaskBundle:List:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TaskList();
        $form = $this->createEditForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Tarea creada.');
            return $this->redirect($this->generateUrl('list_detail', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }    
    
    /**
     * Creates a form to edit a TaskList entity.
     *
     * @param TaskList $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(TaskList $entity)
    {
        $form = $this->createForm(new TaskListType(), $entity, array(
            'action' => $this->generateUrl('list_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualiza'));

        return $form;
    }
    
    /**
     * Edits an existing TaskList entity.
     *
     * @Route("/{id}", name="list_update")
     * @Method("PUT")
     * @Template("DevTaskBundle:List:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TaskList entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TaskListType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Lista guardada.');
            return $this->redirect($this->generateUrl('list'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    } 
    
    /**
     * @Route("/{id}", name="list_detail")
     * @Method("GET")
     * @Template()
     */
    public function detailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TaskList entity.');
        }
        
        $size = count($entity->getTasks());

        return array(
            'entity'    => $entity,
            'size'      => $size,
        );
    }    
    
 
    
    /**
     * @Route("/{id}/items", name="list_items")
     * @Method("GET")
     * @Template()
     */
    public function itemsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TaskList entity.');
        }

        $items = $entity->getTasks();
        //$deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'    => $entity,
            'items'     => $items,
          //  'delete_form' => $deleteForm->createView(),
        );
    }



    /**
     * @Route("/{id}/item-create", name="list_item_create")
     * @Method("POST")
     */
    public function itemCreateAction(Request $request, $id)
    {        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }
        

        $name = $request->request->get('value');

        $task = new Task();
        $task->setTasklist($entity);
        $task->setTask($name);
        $task->setCreated(new \DateTime("now"));

        $em->persist($task);
        $em->flush();
        
        return new Response($name);
    }     
    
    /**
     * Displays a form to edit an existing TaskList entity.
     *
     * @Route("/{id}/edit", name="list_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TaskList entity.');
        }

        $editForm = $this->createForm(new TaskListType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * Creates a form to delete a TaskList entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('list_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }
    
    /**
     * Deletes a Tasklist entity.
     *
     * @Route("/{id}/delete", name="list_delete")
     * @Method("GET")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        // Obtenemos la Tasklist
        $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TaskList entity.');
        }

        // Obtenemos las tareas relacionadas
        $tasks = $entity->getTasks();
        
        // Borramos las tareas de la lista
        foreach($tasks as $task) {
            $em->remove($task);
        }
        
        // Borramos la lista
        $em->remove($entity);
        
        $em->flush();
    
        return $this->redirect($this->generateUrl('list'));
    }        
}