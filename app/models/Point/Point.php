<?php
namespace donami\Point;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name="points")
 */
class Point
{
    /**
     * @Id @GeneratedValue @Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="\donami\User\User")
     * @var string
     */
    protected $user = null;

    /**
     * @ManyToOne(targetEntity="\donami\Answer\Answer")
     * @JoinColumn(onDelete="CASCADE")
     * @var \donami\Answers\Answers[]
     */
    protected $answer = null;

    /**
     * @ManyToOne(targetEntity="\donami\Comment\Comment")
     * @JoinColumn(onDelete="CASCADE")
     * @var \donami\Comment\Comment
     */
    protected $comment = null;

    /**
     * @Column(type="string")
     * @var string
     */
    protected $action = null;

    public function __construct()
    {
      // $this->answers = new ArrayCollection();
    }


    public function getId()
    {
      return $this->id;
    }

    public function setUser($user)
    {
      $this->user = $user;
    }

    public function getUser()
    {
      return $this->user;
    }

    public function setAnswer($answer)
    {
      $this->answer = $answer;
    }

    public function getAnswers($answer)
    {
      return $this->answer;
    }

    public function setAction($action)
    {
      $this->action = $action;
    }

    public function getAction()
    {
      return $this->action;
    }



    /**
     * Get the value of Comment
     *
     * @return \donami\Comment\Comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of Comment
     *
     * @param \donami\Comment\Comment comment
     *
     * @return self
     */
    public function setComment(\donami\Comment\Comment $comment)
    {
        $this->comment = $comment;

        return $this;
    }

}
