<?php
namespace donami\Point;
/**
 * Point controller
 *
 */
class PointController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * The type of entity to update points on
     * @var string
     */
    private $type;

    /**
     * The repository for the entity
     * @var object
     */
    private $repository;

    /**
     * The parent
     * @var object
     */
    private $parent;

    /**
     * The user
     * @var \donami\User\User
     */
    private $user;

    /**
     * The foreign key to use
     * @var string
     */
    private $joinColumn;

    /**
     * Increase or decrease
     * @var string
     */
    private $action;

    /**
     * Increase points
     *
     * @param  string $type
     * @param  int $id
     * @return void
     */
    public function increaseAction($type, $id)
    {
      $this->setType($type);
      $this->setParentFromId($id);
      $this->setAction('increase');

      $this->updatePoints();

      $this->response->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Decrease points
     *
     * @param  string $type
     * @param  int $id
     * @return void
     */
    public function decreaseAction($type, $id)
    {
      $this->setType($type);
      $this->setParentFromId($id);
      $this->setAction('decrease');

      $this->updatePoints();

      $this->response->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Updating the points
     *
     * @return boolean
     */
    public function updatePoints()
    {
      // If user already has voted on answer
      if ($exists = $this->checkIfExists()) {
        // Return false if user already voted the same way
        // Otherwise "reset" points
        if ($this->action == $exists->getAction()) {
          return false;
        }

        if ($this->action == 'increase') {
          $this->parent->incrementRating();
        }
        else {
          $this->parent->decrementRating();
        }
        // Remove the vote in order to "reset"
        $this->entityManager->persist($this->parent);
        $this->entityManager->flush();


        // Remove the vote in order to "reset"
        $this->entityManager->remove($exists);
        $this->entityManager->flush();

        return true;
      }

      $point = new \donami\Point\Point;
      $point->setUser($this->user);

      if ($this->type == 'comment') {
        $point->setComment($this->parent);
      }
      elseif ($this->type == 'answer') {
        $point->setAnswer($this->parent);
      }
      elseif ($this->type == 'question') {
        $point->setQuestion($this->parent);
      }
      else {
        die('Type not set');
      }

      $point->setAction($this->action);

      if ($this->action == 'increase') {
        $this->parent->incrementRating();
      }
      else {
        $this->parent->decrementRating();
      }

      $this->entityManager->persist($point);
      $this->entityManager->flush();

      return true;

    }


    /**
     * Get the value of The type of entity to update points on
     *
     * @return string
     */
    public function getType()
    {
      return $this->type;
    }

    /**
     * Set the value of The type of entity to update points on
     *
     * @param string type
     *
     * @return self
     */
    public function setType($type)
    {
      switch ($type) {
        case 'comment':
          $this->repository = $this->entityManager->getRepository('\donami\Comment\Comment');
          $this->joinColumn = 'comment';
          break;

        case 'question':
          $this->repository = $this->entityManager->getRepository('\donami\Question\Question');
          $this->joinColumn = 'question';
          break;

        case 'answer':
          $this->repository = $this->entityManager->getRepository('\donami\Answer\Answer');
          $this->joinColumn = 'answer';
          break;

        default:
          die('Invalid type');
          break;
      }

      $this->type = $type;

      return $this;
    }

    /**
     * Check if the user already has voted on the entity
     *
     * @return mixed
     */
    public function checkIfExists()
    {
      // Get the user
      $this->user = $this->entityManager->getRepository('\donami\User\User')->find($this->auth->id());

      return $this->entityManager->getRepository('\donami\Point\Point')->findOneBy(['user' => $this->user, $this->joinColumn => $this->parent]);
    }


    /**
     * Get the value of The parent
     *
     * @return object
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set the value of The parent
     *
     * @param int id
     *
     * @return object
     */
    public function setParentFromId($id)
    {
        $this->parent = $this->repository->find($id);

        return $this->parent;
    }

    /**
     * Get the value of Increase or decrease
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set the value of Increase or decrease
     *
     * @param string action
     *
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

}
