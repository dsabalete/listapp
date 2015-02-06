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
 * @Route("/item")
 */
class ItemController extends Controller
{
    /**
     * @Route("/{id}", name="item_detail")
     * @Method("GET")
     * @Template()
     */
    public function detailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('DevTaskBundle:Task')->find($id);
        
        //$deleteForm = $this->createDeleteForm($id);
        if (!$entity->getComplete()) {
            $completado = "No";
        } else {
            $completado = "Si";
        }

        return array(
            'entity'      => $entity,
            'completado'  => $completado
            //'delete_form' => $deleteForm->createView(),
        );
    }
}