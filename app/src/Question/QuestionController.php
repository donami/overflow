<?php
namespace donami\Question;
/**
 * Question controller
 *
 */
class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Get a single question
     *
     * @return void
     */
    public function viewAction($questionId)
    {
      $this->theme->setTitle('View question');

      // Fetch the question
      $this->db
        ->select('Q.id, Q.title, Q.body, Q.date_created, Q.user_id, Q.answered_id, U.username, U.posts, U.questions, U.answers')
        ->from('questions AS Q')
        ->join('users AS U', 'Q.user_id = U.id')
        ->where('Q.id = ' . $questionId);

      $this->db->execute();
      $res = $this->db->fetchOne();

      if (!$res) {
        die('Unable to find the question');
      }

      // Fetch the replies
      $this->db
        ->select('QR.*, U.username, QR.date_created')
        ->from('questions_replies AS QR')
        ->join('users AS U', 'QR.user_id = U.id')
        ->where('question_id = ' . $questionId);

      $replies = $this->db->executeFetchAll();

      $replies = $this->formatReplies($replies);

      // Fetch tags
      $this->db
        ->select('T.id AS tagId, T.title as title')
        ->from('questions AS Q')
        ->leftJoin('questions_tags AS QT', 'Q.id = QT.question_id')
        ->leftJoin('tags AS T', 'T.id = QT.tag_id')
        ->where('QT.question_id = ' . $questionId);

      $tags = $this->db->executeFetchAll();


      $authed = false;
      $owner = false;
      $admin = $this->auth->isAdmin();

      if ($this->auth->isAuthed()) {
        $authed = true;

        if ($this->auth->id() == $res->user_id) {
          $owner = true;
        }
      }

      $this->views->add('questions/view', [
        'question'  => $res,
        'replies'   => $replies,
        'tags'      => $tags,
        'owner'     => $owner,
        'authed'    => $authed,
        'admin'     => $admin,
      ]);
    }

    /**
     * Generate the replies to comments
     * @param  array  $data All the replies
     *
     * @return array       Formated array of all replies that has a parent comment
     */
    private function formatReplies($data = array())
    {
      // Convert the data to array
      $data = json_decode(json_encode($data), True);

      $comments = array();

      foreach ($data as $key => $value) {
        if ($value['comment_id'] === NULL || $value['comment_id'] == 0) {
          $comments[$value['id']]['main'] = $value;
        }
        else {
          // if (isset($comments[$value['comment_id']]))
            $comments[$value['comment_id']]['replies'][] = $value;
        }
      }

      return $comments;
    }


    /**
     * List questions
     *
     * @return void
     */
    public function listAction()
    {
      $this->theme->setTitle('Questions');

      $this->db->select()->from('questions');
      $res = $this->db->executeFetchAll();

      $this->views->add('questions/index', [
        'questions' => $res,
        'authed' => $this->auth->isAuthed(),
      ]);
    }

    /**
     * Get most recent Questions
     *
     * @param  integer $limit  How many rows to fetch
     * @return array
     */
    public function getRecentAction($limit = 10)
    {
      $this->db
        ->select()
        ->from('questions')
        ->limit($limit)
        ->orderBy('date_created DESC');

      $res = $this->db->executeFetchAll();

      return $res;
    }

    /**
     * View action for creating a question
     *
     * @return void
     */
    public function createAction()
    {
      // Make sure that the user is authed
      if (!$this->auth->isAuthed()) {
        $this->response->redirect($this->url->create(''));
      }

      $this->theme->setTitle('Ask a question');

      if (!empty($_POST)) {
          $tags = $this->getTagsFromString($_POST['tags']);

          $this->insert($_POST, $tags);
      }

      $this->views->add('questions/create', []);
    }


    /**
     * Create an array of tags from a string
     *
     * @param  string $string
     * @return array
     */
    private function getTagsFromString($string)
    {
      // Make sure there are no spaces
      $string = str_replace(", ", ",", $string);

      // Pick out the tags in to an array
      $tags = explode(",", $string);

      return $tags;
    }

    /**
     * Save new question
     * @param  array $data
     * @return void
     */
    private function insert($data, $tags)
    {
      // Make sure that the user is authed
      if (!$this->auth->isAuthed()) {
        die('User not authed');
      }

      $title = trim($data['title']);
      $body = trim($data['body']);

      // Make sure that title is defined
      if (empty($title)) {
        die("You cannot leave title empty");
      }

      // Make sure that body is defined
      if (empty($body)) {
        die("You cannot leave the question field empty");
      }

      // Query for inserting question data
      $this->db->insert(
          'questions',
          [
              'user_id'  => $this->auth->id(),
              'title' => $data['title'],
              'body' => $data['body']
          ]
      );

      // If query was succesfull
      if ($this->db->execute()) {
        // The new questions id
        $questionId = $this->db->lastInsertId();

        // Loop through the tags
        foreach ($tags as $tag) {

          // Check if tag already exists. If it does the id
          // will be fetched otherwise create a new row
          $check = $this->db->select('id')->from('tags')->where('title = "' . $tag . '"')->limit(1);
          $this->db->execute();
          $exists = $this->db->fetchOne();

          // If the tag already exists
          if ($exists) {
            $tagId = $exists->id;
          }
          else {
            // Create the tag incase it doesnt exist
            $this->db->insert('tags', ['title' => $tag]);
            $this->db->execute();

            $tagId = $this->db->lastInsertId();
          }

          $this->db->insert(
            'questions_tags',
            [
              'tag_id' => $tagId,
              'question_id' => $questionId,
            ]
          );

          $this->db->execute();

        }

      }

      // Redirect to the created question
      $this->response->redirect($this->url->create('question?id=' . $questionId));
    }

    /**
     * The delete action
     *
     * @param  int $questionId
     * @return void
     */
    public function deleteAction($questionId) {
      // If the question was deleted relocate the user
      if ($this->delete($questionId)) {
        $this->response->redirect($this->url->create('questions'));
      }
    }

    /**
     * Remove from Database
     *
     * @param  int $questionId
     * @return boolean
     */
    public function delete($questionId)
    {
      $this->db->delete('questions', 'id = ' . $questionId);

      return $this->db->execute();
    }

}
