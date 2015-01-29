<?php

namespace Dev\TaskBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Dev\TaskBundle\Entity\TaskList;
// use Dev\TaskBundle\Form\TaskListType;

/**
 * Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
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
     * @Route("/inline-create")
     * @Method("POST")
     */
    public function createListAction(Request $request)
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
}
