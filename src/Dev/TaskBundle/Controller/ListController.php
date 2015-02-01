<?php

namespace Dev\TaskBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Dev\TaskBundle\Entity\TaskList;
use Dev\TaskBundle\Entity\Task;
use Dev\TaskBundle\Form\TaskListType;
// use Dev\TaskBundle\Form\TaskCompleteType;
// use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * List controller.
 *
 * @Route("/list")
 */
class ListController extends Controller
{
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
     * @Route("/{id}/inline-edit", name="task_inline_edit")
     * @Method("POST")
     * 
     */
    public function inlineTaskEditAction(Request $request)
    {
        $id = $request->request->get('id');
        $name = $request->request->get('value');
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }
        
        $tasks = $entity->getTasks();

               
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
            return $this->redirect($this->generateUrl('list_show', array('id' => $entity->getId())));
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
     * @Route("/{id}", name="list_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TaskList entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
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
     * Deletes a TaskList entity.
     *
     * @Route("/{id}", name="list_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DevTaskBundle:TaskList')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Task entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Lista eliminada.');
        }

        return $this->redirect($this->generateUrl('list'));
    }    
}