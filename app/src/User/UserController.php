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

      // Fetch users questions
      $this->db->select()
        ->from('questions')
        ->where('user_id = ' . $userId);
      $questions = $this->db->executeFetchAll();

      // Fetch answered questions by this user
      $this->db
        ->select('Q.title AS question_title, Q.id AS question_id')
        ->join('questions AS Q', 'Q.id = QR.question_id')
        ->from('questions_replies AS QR')
        ->where('QR.user_id = ' . $userId . ' && QR.comment_id = 0');

      $answers = $this->db->executeFetchAll();

      // Create the view
      $this->views->add('users/view', [
        'user' => $res,
        'questions' => $questions,
        'answers' => $answers,
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

    /**
     * Get most active users
     *
     * @param  integer $limit
     * @return array
     */
    public function getActiveAction($limit = 10)
    {
      $this->db
        ->select('id, username, posts')
        ->from('users')
        ->orderBy('posts DESC')
        ->limit($limit);

      $res = $this->db->executeFetchAll();

      return $res;
    }

}
