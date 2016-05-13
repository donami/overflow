<?php
namespace donami\Reply;

/**
 * Reply controller
 *
 */
class ReplyController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Save new reply
     * @param  array $data
     * @return void
     */
    public function createAction($data)
    {
      $body = trim($data['reply_comment']);

      if (empty($body)) {
        die("You cannot leave the comment field empty");
      }

      if (!isset($data['comment_id'])) {
        $this->db->insert(
            'questions_replies',
            [
                'user_id'  => $this->auth->id(),
                'question_id' => (int)$data['question_id'],
                'body' => $data['reply_comment'],
            ]
        );
      }
      else {
        $this->db->insert(
            'questions_replies',
            [
                'user_id'  => $this->auth->id(),
                'question_id' => (int)$data['question_id'],
                'comment_id' => (int)$data['comment_id'],
                'body' => $data['reply_comment'],
            ]
        );
      }

      $this->db->execute();

      $this->response->redirect($this->url->create('question?id=' . $data['question_id']));
    }

    /**
     * Mark a reply as answer for question
     * @param  int $replyId
     * @return void
     */
    public function acceptAnswerAction($replyId, $questionId)
    {
      // Fetch the reply data in order to get the user id
      $this->db
        ->select('user_id')
        ->from('questions_replies')
        ->where('id = ' . $replyId)
        ->limit(1);

      $this->db->execute();
      $reply = $this->db->fetchOne();

      if (!$reply) {
        die('Unable to find the reply');
      }

      // Update the question
      $this->db->update(
        'questions',
        [
          'user_answered_id' => $reply->user_id,
          'answered_id' => $replyId,
        ],
        'id = ' . $questionId
      );

      $this->db->execute();

      // Redirect the user
      $this->response->redirect($this->url->create('question?id=' . $questionId));
    }

    public function pointAction($replyId, $action)
    {
      $userId = $this->auth->id();

      $this->db->select('points, question_id')->from('questions_replies');
      $this->db->execute();

      $reply = $this->db->fetchOne();

      $points = ($action == 'increase') ? (int)$reply->points + 1 : (int)$reply->points - 1;

      // Check if user already has voted
      $this->db->select('id, action')->from('questions_points')->where('reply_id = ' . $replyId . ' && user_id = ' . $userId);
      $this->db->execute();
      $check = $this->db->fetchOne();

      // If the user has already voted he should
      // not be able to to the same action again
      if (!empty($check)) {
        if ($check->action === 'increase') {
          if ($action == 'increase') {
            die('Already voted');
          }
          else {
            $this->db->delete('questions_points', 'id = ' . $check->id);
            $this->db->execute();
          }
        }
        if ($check->action === 'decrease') {
          if ($action == 'decrease') {
            die('Already voted');
          }
          else {
            $this->db->delete('questions_points', 'id = ' . $check->id);
            $this->db->execute();
          }
        }

      }

      $this->db->update(
        'questions_replies',
        [
          'points' => $points
        ],
        'id = ' . $replyId
      );

      $this->db->execute();

      // Only insert to database if it wasn't a "reset" of the points
      if (empty($check)) {
        $this->db->insert(
          'questions_points',
          [
            'user_id' => $userId,
            'reply_id' => $replyId,
            'action' => $action,
          ]
        );
        $this->db->execute();
      }

      $this->response->redirect($this->url->create('question?id=' . $reply->question_id));
    }

    /**
     * Remove a comment
     *
     * @param  int $replyId
     * @return boolean
     */
    public function deleteAction($replyId)
    {
      $this->db->delete('questions_replies', 'id = ' . $replyId);

      return $this->db->execute();
    }

}
