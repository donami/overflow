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
    switch ($this->type) {
      case 'write_answer':
        $content = 'Wrote an answer';
        break;

      case 'write_question':
        $content = 'Asked a question';
        break;

      case 'write_comment':
        $content = 'Posted a comment';
        break;

      case 'received_points_up':
        $content = 'Got a point from a post';
        break;

      case 'received_points_down':
        $content = 'Lost a point from a post';
        break;

      default:
        $content = '';
        return false;
        break;
    }

    return '<span>' . $content . '</span>';
  }

}
