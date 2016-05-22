<?php
namespace donami\Tag;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="tags")
 */
class Tag
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $title = '';

    /**
     * @ManyToMany(targetEntity="\donami\Question\Question", mappedBy="tags")
     * @var \donami\Question\Question[]
     */
    protected $questions;

    public function __construct()
    {
      $this->questions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function addQuestion($question)
    {
      $this->questions[] = $question;
    }

    public function getQuestions()
    {
      return $this->questions;
    }

}
