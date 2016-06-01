<?php
namespace donami\User;

use \donami\Helper\Helper;

/**
 * User controller
 *
 */
class UserController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Get a single user
     *
     * @return void
     */
    public function viewAction($userId)
    {
      $this->theme->setTitle('View user page');

      // Get the user ID from url
      $userId = $this->request->getGet('id');

      // Fetch the user from database
      $user = $this->entityManager->getRepository('\donami\User\User')->find($userId);

      // Fetch answers that has been accepted as best answer
      $bestAnswers = $this->entityManager->getRepository('\donami\Question\Question')->findBy(['best_answer_user' => $userId]);

      // Render the view
      echo $this->twig->render('users/view.twig', [
        'user' => $user,
        'questions' => $user->getQuestions(),
        'answers' => $user->getAnswers(),
        'bestAnswers' => $bestAnswers,
        'actions' => $user->getReputation()->getActions(),
        'rank' => Helper::getUserRank($user->getReputation()->getPoints())
      ]);
    }

    /**
     * List users
     *
     * @return void
     */
    public function listAction()
    {
      $this->theme->setTitle('Display user list');

      // Fetch tags from database
      $users = $this->entityManager->getRepository('\donami\User\User')->findAll();

      // Render the view
      echo $this->twig->render('users/list.twig', [
        'users' => $users,
      ]);
    }

    /**
     * Get most active users
     *
     * @param  integer $limit
     * @return array
     */
    public function getActiveAction($limit = 10)
    {
      return $this->entityManager
                ->getRepository('\donami\User\User')
                ->findBy([], ['posts' => 'DESC'], $limit);
    }

}
