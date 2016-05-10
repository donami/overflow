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
      $this->db->insert(
          'questions_replies',
          [
              'user_id'  => 1,
              'question_id' => $data['question_id'],
              'comment_id' => $data['comment_id'],
              'body' => $data['reply_comment'],
          ]
      );

      $this->db->execute();
      
      $this->response->redirect($this->url->create('question?id=' . $data['question_id']));
    }

}
