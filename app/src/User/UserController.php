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
      $this->db->select()->from('users')->where('id = ' . $userId);
      $this->db->execute();
      $res = $this->db->fetchOne();

      // Fetch users questions_tags
      $this->db->select()
        ->from('questions')
        ->where('user_id = ' . $userId);
      $questions = $this->db->executeFetchAll();

      // Create the view
      $this->views->add('users/view', [
        'user' => $res,
        'questions' => $questions,
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
      $this->db->select()->from('users');
      $res = $this->db->executeFetchAll();

      // Create the view
      $this->views->add('users/list', [
        'users' => $res,
      ]);
    }

}
