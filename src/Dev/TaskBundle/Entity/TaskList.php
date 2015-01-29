<?php

namespace Dev\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TaskList
 * 
 * @ORM\Entity
 * @ORM\Table(name="tasklist")
 * @ORM\HasLifecycleCallbacks()
 */
class TaskList
{
    /**
     * @var collection
     * 
     * @ORM\OneToMany(targetEntity="Task", mappedBy="tasklist")
     */
    protected $tasks; 
   
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     *
     * @assert\NotBlank(message="El campo Nombre es necesario.")
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;    
    
    
    /*
    public function __construct() {
        $this->$tasks = new ArrayCollection();
    }
    */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TaskList
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add tasks
     *
     * @param \Dev\TaskBundle\Entity\Task $tasks
     * @return TaskList
     */
    public function addTask(\Dev\TaskBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \Dev\TaskBundle\Entity\Task $tasks
     */
    public function removeTask(\Dev\TaskBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
