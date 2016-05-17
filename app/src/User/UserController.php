<?php
namespace donami\User;
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

      // Create the view
      $this->views->add('users/view', [
        'user' => $user,
        'questions' => $user->getQuestions(),
        'answers' => $user->getAnswers(),
        'bestAnswers' => $bestAnswers,
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

      // Create the view
      $this->views->add('users/list', [
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
