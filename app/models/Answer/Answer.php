<?php
namespace donami\Answer;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="answers")
 */
class Answer
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="\donami\User\User")
     * @var \donami\User\User
     */
    protected $user;

    /**
     * @Column(type="datetime")
     * @var \Datetime
     */
    protected $date_created;

    /**
     * @Column(type="datetime", nullable=true)
     * @var \Datetime
     */
    protected $date_modified;

    /**
     * @ManyToOne(targetEntity="\donami\Question\Question", inversedBy="answers")
     * @JoinColumn(name="question_id", referencedColumnName="id", onDelete="CASCADE")
     * @var \donami\Question\Question
     */
    protected $question;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $body;

    /**
     * @OneToMany(targetEntity="\donami\Comment\Comment", mappedBy="answer")
     * @var \donami\Comment\Comment[]
     */
    protected $comments = null;

    /**
     * @OneToMany(targetEntity="\donami\Point\Point", mappedBy="answer")
     * @var \donami\Point\Point[]
     */
    protected $points = null;

    /**
     * @Column(type="integer")
     * @var int
     */
    protected $rating = 0;

    public function __construct()
    {
      $this->date_created = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function addUser($user)
    {
      $this->user = $user;
    }

    public function getUser()
    {
      return $this->user;
    }

    public function addQuestion($question)
    {
      $this->question = $question;
    }

    public function assignToQuestion($question)
    {
      $this->question = $question;
    }

    public function getDateCreated($value='')
    {
      return $this->date_created->format('Y-m-d H:i');
    }

    public function getPoints()
    {
      return $this->points;
    }

    public function getComments()
    {
      return $this->comments;
    }

    public function getQuestion() {
      return $this->question;
    }

    public function getRating()
    {
      return $this->rating;
    }

    public function setRating($rating)
    {
      $this->rating = $rating;
    }

    public function incrementRating()
    {
      $this->rating = $this->rating + 1;
    }

    public function decrementRating()
    {
      $this->rating = $this->rating - 1;
    }

}
