<?php

namespace Dev\TaskBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Dev\TaskBundle\Entity\Task;
use Dev\TaskBundle\Form\TaskType;
use Dev\TaskBundle\Form\TaskCompleteType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * Task controller.
 *
 * @Route("/task")
 */
class TaskController extends Controller
{

    /**
     * Lists all Task entities.
     *
     * @Route("/", name="task")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        // $em = $this->getDoctrine()->getManager();
        // $entities = $em->getRepository('DevTaskBundle:Task')->findAll();

        $repository = $this->getDoctrine()->getRepository('DevTaskBundle:Task');

        $query = $repository->createQueryBuilder('t')
            ->select('t.id','t.task','t.complete','t.created')
            ->orderBy('t.created','DESC')
            ->getQuery();

        $entities = $query->getResult();
        $entity = new Task();
        //$form   = $this->createCreateForm($entity);
        $form   = $this->createForm(new TaskType(), $entity);
        return array(
            'entities' => $entities,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Task entity.
     *
     * @Route("/", name="task_create")
     * @Method("POST")
     * @Template("DevTaskBundle:Task:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Task();
        $form = $this->createCreateForm($entity);
        //$form = $this->createForm(new TaskType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Tarea creada.');
            return $this->redirect($this->generateUrl('task_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Task entity.
     *
     * @param Task $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('task_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crea'));

        return $form;
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @Route("/new", name="task_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Task();
        $form   = $this->createCreateForm($entity);
        //$form   = $this->createForm(new TaskType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Task entity.
     *
     * @Route("/{id}", name="task_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit", name="task_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        //$editForm = $this->createEditForm($entity);
        $editForm = $this->createForm(new TaskType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Task entity.
     *
     * @param Task $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Task $entity)
    {
        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('task_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualiza'));

        return $form;
    }

    /**
     * Edits an existing Task entity.
     *
     * @Route("/{id}", name="task_update")
     * @Method("PUT")
     * @Template("DevTaskBundle:Task:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TaskType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Tarea guardada.');
            return $this->redirect($this->generateUrl('task'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
    /**
     * Deletes a Task entity.
     *
     * @Route("/{id}", name="task_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DevTaskBundle:Task')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Task entity.');
            }

            $em->remove($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('notice', 'Tarea eliminada.');
        }

        return $this->redirect($this->generateUrl('task'));
    }

    /**
     * Creates a form to delete a Task entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }


    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit-complete", name="task_edit_complete")
     * @Method("GET")
     * @Template("DevTaskBundle:Task:complete.html.twig")
     */     
    public function editCompleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createForm(new TaskCompleteType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    
    /**
     * Displays a inline form to edit an existing Task entity.
     *
     * @Route("/inline-edit", name="task_inline_edit")
     * @Method("POST")
     * 
     */
    // public function inlineEditAction(Request $request)
    // {
    //     $id = $request->request->get('id');
    //     $descripcion = $request->request->get('value');
        
    //     $em = $this->getDoctrine()->getManager();
    //     $entity = $em->getRepository('DevTaskBundle:Task')->find($id);

    //     if (!$entity) {
    //         throw $this->createNotFoundException('Unable to find Task entity.');
    //     }
        
    //     $entity->setTask($descripcion);        
    //     $em->persist($entity);
    //     $em->flush();
        
    //     return new Response($descripcion);
    // } 
    

    /**
     * Edits an existing Task entity.
     *
     * @Route("/{id}/update-complete", name="task_update_complete")
     * @Method("PUT")
     * @Template("DevTaskBundle:Task:complete.html.twig")
     */
    public function updateCompleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createForm(new TaskCompleteType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                $json = json_encode(array(
                    'id' => $entity->getId(),
                    'complete' => $entity->getComplete(),
                ));

                $response = new Response($json);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }
            $this->get('session')->getFlashBag()->add('notice', 'Tarea actualizada.');
            return $this->redirect($this->generateUrl('task'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }


    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit-task", name="task_edit_task")
     * @Method("GET")
     * @Template("DevTaskBundle:Task:complete.html.twig")
     */     
    public function editTaskAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DevTaskBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm = $this->createForm(new TaskCompleteType(), $entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
}