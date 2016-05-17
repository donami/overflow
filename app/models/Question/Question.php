<?php
namespace donami\Question;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="questions")
 */
class Question
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @Column(type="datetime")
     * @var \Datetime
     */
    protected $date_created;

    /**
     * @Column(type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $date_modified;

    /**
    * @ManyToOne(targetEntity="\donami\User\User")
    * @var \donami\User\User
    */
    protected $user;

    /**
     * @OneToOne(targetEntity="\donami\Answer\Answer")
     * @JoinColumn(onDelete="SET NULL")
     * @var int
     */
    protected $best_answer = null;

    /**
     * @Column(type="integer")
     * @var int
     */
    protected $best_answer_user = null;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $title;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $body;

    /**
     * @ManyToMany(targetEntity="\donami\Tag\Tag", inversedBy="tags")
     * @var Tag[]
     */
    protected $tags = null;

    /**
     * @OneToMany(targetEntity="\donami\Answer\Answer", mappedBy="question")
     * @var Answer[]
     */
    protected $answers = null;


    public function __construct()
    {
      $this->date_created = new \DateTime('now');
      $this->tags = new ArrayCollection();
      $this->answers = new ArrayCollection();

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

    public function getUser()
    {
      return $this->user;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getDateCreated()
    {
        return $this->date_created->format('Y:m-d H:i');
    }

    public function getTags()
    {
      return $this->tags;
    }

    public function addTag($tag)
    {
      $tag->addQuestion($this);
      $this->tags[] = $tag;
    }

    public function addUser($user)
    {
      $this->user = $user;
    }

    public function addAnswer($answer)
    {
      $this->answers[] = $answer;
    }

    public function getAnswers()
    {
      return $this->answers;
    }

    public function getBestAnswer()
    {
      return $this->best_answer;
    }

    public function setBestAnswer($answer)
    {
      $this->best_answer = $answer;
    }

    public function getBestAnswerUser()
    {
      return $this->best_answer_user;
    }

    public function setBestAnswerUser($best_answer_user)
    {
      $this->best_answer_user = $best_answer_user;
    }

}
