<?php
namespace donami\Reputation;

use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity @Table(name="reputation")
*/
class Reputation
{

  /**
  * @Id @GeneratedValue @Column(type="integer")
  * @var int
  */
  protected $id;

  /**
  * @OneToOne(targetEntity="\donami\User\User")
  * @var \donami\User\User
  */
  protected $user;

  /**
  * @OneToMany(targetEntity="\donami\Action\Action", mappedBy="reputation", cascade={"persist"})
  * @var \donami\Action\Action
  */
  protected $actions;

  /**
  * @Column(type="integer")
  * @var int
  */
  protected $points = 0;


  public function __construct()
  {
    $this->actions = new ArrayCollection();
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
  * Get the value of User
  *
  * @return \donami\User\User
  */
  public function getUser()
  {
    return $this->user;
  }

  /**
  * Set the value of User
  *
  * @param \donami\User\User user
  *
  * @return self
  */
  public function setUser(\donami\User\User $user)
  {
    $this->user = $user;

    return $this;
  }

  /**
  * Get the Actions
  *
  * @return \donami\Action\Action
  */
  public function getActions()
  {
    return $this->actions;
  }

  /**
  * Set the value of Actions
  *
  * @param \donami\Action\Action actions
  *
  * @return self
  */
  public function setActions(\donami\Action\Action $actions)
  {
    $this->actions = $actions;

    return $this;
  }

  /**
   * Add an action to the collection
   *
   * @param donami\Action\Action $action
   *
   * @return self
   */
  public function addAction(\donami\Action\Action $action)
  {
    $this->actions[] = $action;

    switch ($action->getType()) {
      case 'write_comment':
        $points = 5;
        break;

      case 'write_question':
        $points = 10;
        break;

      case 'write_answer':
        $points = 15;
        break;

      case 'received_points_up':
        $points = 1;
        break;

      case 'received_points_down':
        $points = -1;
        break;

      default:
        $points = 0;
        break;
    }

    $action->setReputation($this);

    $this->incrementPoints($points);

    return $this;
  }

  /**
  * Get the value of Points
  *
  * @return int
  */
  public function getPoints()
  {
    return $this->points;
  }

  /**
  * Set the value of Points
  *
  * @param int points
  *
  * @return self
  */
  public function setPoints($points)
  {
    $this->points = $points;

    return $this;
  }

  /**
   * Increase points
   *
   * @param int points
   *
   * @return void
   */
  public function incrementPoints($points)
  {
    $this->points = $this->points + $points;
  }

}
