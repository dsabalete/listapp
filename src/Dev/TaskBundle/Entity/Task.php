<?php

namespace Dev\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity 
 * @ORM\Table(name="task")
 */
class Task
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $task;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $complete;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\ManyToOne(targetEntity="TaskList", inversedBy="tasks")
     * @ORM\JoinColumn(name="tasklist_id", referencedColumnName="id")
     */
    protected $tasklist;
    

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
     * Set task
     *
     * @param string $task
     * @return Task
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string 
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set complete
     *
     * @param boolean $complete
     * @return Task
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;

        return $this;
    }

    /**
     * Get complete
     *
     * @return boolean 
     */
    public function getComplete()
    {
        return $this->complete;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Task
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set tasklist
     *
     * @param \Dev\TaskBundle\Entity\TaskList $tasklist
     * @return Task
     */
    public function setTasklist(\Dev\TaskBundle\Entity\TaskList $tasklist = null)
    {
        $this->tasklist = $tasklist;

        return $this;
    }

    /**
     * Get tasklist
     *
     * @return \Dev\TaskBundle\Entity\TaskList 
     */
    public function getTasklist()
    {
        return $this->tasklist;
    }
}
