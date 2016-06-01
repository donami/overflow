<?php
namespace donami\Action;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManager;

/**
* @Entity @Table(name="action")
* @HasLifecycleCallbacks()
*/
class Action
{

  /**
  * @Id @GeneratedValue @Column(type="integer")
  * @var int
  */
  protected $id;

  /**
  * @ManyToOne(targetEntity="\donami\Reputation\Reputation", inversedBy="actions")
  * @JoinColumn(name="reputation_id", referencedColumnName="id")
  * @var \donami\Reputation\Reputation
  */
  protected $reputation;

  /**
  * @Column(type="string")
  * @var string
  */
  protected $type;

  /**
   * @OneToOne(targetEntity="\donami\Question\Question")
   * @var \donami\Question\Question
   */
  protected $question;

  /**
   * @OneToOne(targetEntity="\donami\Answer\Answer")
   * @var \donami\Answer\Answer
   */
  protected $answer;

  /**
   * @OneToOne(targetEntity="\donami\Comment\Comment")
   * @var \donami\Comment\Comment
   */
  protected $comment;

  /**
   * @OneToOne(targetEntity="\donami\Point\Point")
   * @JoinColumn(onDelete="CASCADE")
   * @var \donami\Point\Point
   */
  protected $point;

  /**
   * @Column(type="datetime")
   * @var \Datetime
   */
  protected $date_created;

  public function __construct()
  {
    $this->date_created = new \Datetime('now');
  }

  /**
  * Get the value of Id
  *
  * @return int
  */
  public function getId()
  {
    return $this->id;
  }

  /**
  * Set the value of Id
  *
  * @param int id
  *
  * @return self
  */
  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }

  /**
  * Get the value of Reputation
  *
  * @return \donami\Reputation\Reputation
  */
  public function getReputation()
  {
    return $this->reputation;
  }

  /**
  * Set the value of Reputation
  *
  * @param \donami\Reputation\Reputation reputation
  *
  * @return self
  */
  public function setReputation(\donami\Reputation\Reputation $reputation)
  {
    $this->reputation = $reputation;

    return $this;
  }

  /**
  * Get the value of Type
  *
  * @return string
  */
  public function getType()
  {
    return $this->type;
  }

  /**
  * Set the value of Type
  *
  * @param string type
  *
  * @return self
  */
  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }

  public function _toString()
  {
    $link = '#';

    switch ($this->type) {
      case 'write_answer':
        $link = 'question?id=' . $this->answer->getQuestion()->getId() . '#answer-' . $this->answer->getId();
        $content = 'Wrote an answer';
        break;

      case 'write_question':
        $link = 'question?id=' . $this->question->getId();
        $content = 'Asked a question';
        break;

      case 'write_comment':
        $link = 'question?id=' . $this->comment->getAnswer()->getQuestion()->getId() . '#comment-' . $this->comment->getId();
        $content = 'Posted a comment';
        break;

      case 'received_points_up':
      case 'received_points_down':
        if ($this->point->getQuestion()) {
          $question = $this->point->getQuestion();
          $link = 'question?id=' . $question->getId();
        }
        elseif ($this->point->getAnswer()) {
          $answer = $this->point->getAnswer();
          $link = 'question?id=' . $answer->getQuestion()->getId() . '#answer-' . $answer->getId();
        }
        else if ($this->point->getComment()) {
          $comment = $this->point->getComment();
          $link = 'question?id=' . $comment->getAnswer()->getQuestion()->getId() . '#comment-' . $comment->getId();
        }

        if ($this->type == 'received_points_up') {
          $content = 'Gained a point from a post';
        }
        else {
          $content = 'Lost a point from a post';
        }
        break;

      default:
        $content = '';
        return false;
        break;
    }

    return [
      'link' => $link,
      'content' => $content,
    ];
  }

  /**
  * Get the value of Question
  *
  * @return \donami\Question\Question
  */
  public function getQuestion()
  {
    return $this->question;
  }

  /**
  * Set the value of Question
  *
  * @param \donami\Question\Question question
  *
  * @return self
  */
  public function setQuestion(\donami\Question\Question $question)
  {
    $this->question = $question;

    return $this;
  }

  /**
  * Get the value of Answer
  *
  * @return \donami\Answer\Answer
  */
  public function getAnswer()
  {
    return $this->answer;
  }

  /**
  * Set the value of Answer
  *
  * @param \donami\Answer\Answer answer
  *
  * @return self
  */
  public function setAnswer(\donami\Answer\Answer $answer)
  {
    $this->answer = $answer;

    return $this;
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


  /**
  * Get the value of Point
  *
  * @return \donami\Point\Point
  */
  public function getPoint()
  {
    return $this->point;
  }

  /**
  * Set the value of Point
  *
  * @param \donami\Point\Point point
  *
  * @return self
  */
  public function setPoint(\donami\Point\Point $point)
  {
    $this->point = $point;

    return $this;
  }

  /**
  * Get the value of Date Created
  *
  * @return \Datetime
  */
  public function getDateCreated()
  {
    return $this->date_created->format('Y-m-d H:i');
  }

  /**
  * Set the value of Date Created
  *
  * @param \Datetime date_created
  *
  * @return self
  */
  public function setDateCreated(\Datetime $date_created)
  {
    $this->date_created = $date_created;

    return $this;
  }

}
