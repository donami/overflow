<?php
namespace donami\Comment;

/**
 * @Entity @Table(name="comments")
 */
class Comment
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
     * @Column(type="string")
     * @var string
     */
    protected $date_created = '';

    /**
     * @Column(type="string")
     * @var string
     */
    protected $date_modified = '';

    /**
     * @Column(type="integer")
     * @var int
     */
    protected $rating = 0;

    /**
     * @ManyToOne(targetEntity="\donami\Answer\Answer", inversedBy="comments")
     * @JoinColumn(name="answer_id", referencedColumnName="id", onDelete="CASCADE")
     * @var \donami\Answer\Answer
     */
    protected $answer;

    /**
     * @OneToMany(targetEntity="\donami\Point\Point", mappedBy="comment")
     * @var \donami\Point\Point[]
     */
    protected $points = null;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $body;


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

    public function addAnswer($answer)
    {
      $this->answer = $answer;
    }

    public function assignToAnswer($answer)
    {
      $this->answer = $answer;
    }

    public function getDateCreated($value='')
    {
      return $this->date_created;
    }

    public function getPoints()
    {
      return $this->points;
    }

    public function getRating()
    {
      return $this->rating;
    }

    public function getAnswer()
    {
      return $this->answer;
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
