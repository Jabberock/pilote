<?php

namespace Pilote\TaskerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pilote\TaskerBundle\Entity\TaskRepository")
 */
class Task
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="StartDate", type="date", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="EndDate", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="progress", type="integer")
     */
    private $progress;

    /**
     * @var boolean
     *
     * @ORM\Column(name="progressActivated", type="boolean")
     */
    private $progressActivated;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pilote\TaskerBundle\Entity\TList", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tList;
    
    /**
     * @ORM\ManyToOne(targetEntity="Pilote\UserBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=true)
     */
    private $creator;

    /**
     * @ORM\OneToMany(targetEntity="Pilote\TaskerBundle\Entity\HasCommented", mappedBy="task", cascade="remove")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="Pilote\TaskerBundle\Entity\CheckList", mappedBy="task", cascade="remove")
     */
    private $checkLists;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity="Pilote\TaskerBundle\Entity\DependencyLink", mappedBy="source", cascade="remove")
     */
    private $sourceLinks;

    /**
     * @ORM\OneToMany(targetEntity="Pilote\TaskerBundle\Entity\DependencyLink", mappedBy="target", cascade="remove")
     */
    private $targetLinks;

    /**
     * @ORM\OneToOne(targetEntity="Pilote\TaskerBundle\Entity\Document")
     * @ORM\JoinColumn(name="document_id", referencedColumnName="id")
     */
    private $document;
    

    /**
     * Constructeur de la classe Task.
     */
    public function __construct()
    {
        $this->name = "Nouvelle tâche";
        $this->content = "";
        $this->position = 0;
        $this->progress = 0;
        $this->progressActivated = false;
    }

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
     * @return Task
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
     * Set content
     *
     * @param string $content
     * @return Task
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Task
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return Task
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set progress
     *
     * @param int $progress
     * @return Task
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return int 
     */
    public function getProgress()
    {
        if (!$this->progressActivated) {
            return 0;
        }
        return $this->progress;
    }

    /**
     * Set progressActivated
     *
     * @param boolean $progressActivated
     * @return Task
     */
    public function setProgressActivated($progressActivated)
    {
        $this->progressActivated = $progressActivated;

        return $this;
    }

    /**
     * Get progressActivated
     *
     * @return boolean 
     */
    public function isProgressActivated()
    {
        return $this->progressActivated;
    }

    /**
     * Set document
     *
     * @param boolean $document
     * @return Task
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return Document 
     */
    public function getDocument()
    {
        return $this->document;
    }


    /**
     * Set tList
     *
     * @param \Pilote\TaskerBundle\Entity\TList $tList
     * @return Task
     */
    public function setTList(\Pilote\TaskerBundle\Entity\TList $tList)
    {
        $this->tList = $tList;

        return $this;
    }

    /**
     * Get tList
     *
     * @return \Pilote\TaskerBundle\Entity\TList 
     */
    public function getTList()
    {
        return $this->tList;
    }


    /**
     * Set tList
     *
     * @param \Pilote\UserBundle\Entity\User $creator
     * @return Task
     */
    public function setCreator(\Pilote\UserBundle\Entity\User $user = NULL)
    {
        $this->creator = $user;

        return $this;
    }

    /**
     * Get creator
     *
     * @return \Pilote\UserBundle\Entity\User 
     */
    public function getCreator()
    {
        return $this->creator;
    }


    public function addComment(\Pilote\TaskerBundle\Entity\HasCommented $comment)
    {
        $this->comments[] = $comment;
        $comment->setTask($this); 
        return $this;
    }

    public function removeComment(\Pilote\TaskerBundle\Entity\HasCommented $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }


    public function addCheckList(\Pilote\TaskerBundle\Entity\CheckList $checkList)
    {
        $this->checkLists[] = $checkList;
        $checkList->setTask($this); 
        return $this;
    }

    public function removeCheckList(\Pilote\TaskerBundle\Entity\CheckList $checkList)
    {
        $this->checkLists->removeElement($checkList);
    }

    /**
     * Get checkLists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCheckLists()
    {
        return $this->checkLists;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Task
     */
    public function setPosition ($position)
    {
        $this->position = $position;

        return $this;
    }


    public function addSourceLink(\Pilote\TaskerBundle\Entity\DependencyLink $sourceLink)
    {
        $this->sourceLinks[] = $sourceLink;
    }

    public function removeSourceLink(\Pilote\TaskerBundle\Entity\DependencyLink $sourceLink)
    {
        $this->sourceLinks->removeElement($sourceLink);
    }
    /**
     * Get sourceLinks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSourceLinks()
    {
        return $this->sourceLinks;
    }


    public function addTargetLink(\Pilote\TaskerBundle\Entity\DependencyLink $targetLink)
    {
        $this->targetLinks[] = $targetLink;
    }

    public function removeTargetLink(\Pilote\TaskerBundle\Entity\DependencyLink $targetLink)
    {
        $this->targetLinks->removeElement($targetLink);
    }
    /**
     * Get targetLinks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTargetLinks()
    {
        return $this->targetLinks;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    public function __toString() {
        return $this->getName();
    }
}