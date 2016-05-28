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

      $question = $this->entityManager->getRepository('\donami\Question\Question')->find($data['question_id']);
      $user = $this->entityManager->getRepository('\donami\User\User')->find($this->auth->id());

      $answer = new \donami\Answer\Answer;
      $answer->setBody($body);

      $answer->addUser($user);
      $answer->assignToQuestion($question);

      $this->entityManager->persist($answer);
      $this->entityManager->flush();

      // Add the points for answering the question
      $action = new \donami\Action\Action;
      $action->setType('write_answer');

      $reputation = $user->getReputation();
      $reputation->addAction($action);

      $this->entityManager->persist($reputation);
      $this->entityManager->flush();

      $this->response->redirect($this->url->create('question?id=' . $data['question_id']));

    }

    public function createCommentAction($data)
    {
      $body = trim($data['reply_comment']);

      if (empty($body)) {
        die("You cannot leave the comment field empty");
      }

      $user = $this->entityManager->getRepository('\donami\User\User')->find($this->auth->id());
      $answer = $this->entityManager->getRepository('\donami\Answer\Answer')->find($data['comment_id']);

      $comment = new \donami\Comment\Comment;
      $comment->setBody($body);

      $comment->addUser($user);
      $comment->assignToAnswer($answer);

      $this->entityManager->persist($comment);
      $this->entityManager->flush();

      // Add points to the user for posting a comment
      $action = new \donami\Action\Action;
      $action->setType('write_comment');

      $reputation = $user->getReputation();
      $reputation->addAction($action);

      $this->entityManager->persist($reputation);
      $this->entityManager->flush();

      $this->response->redirect($this->url->create('question?id=' . $answer->getQuestion()->getId()));
    }

    /**
     * Mark a reply as answer for question
     * @param  int $replyId
     * @param  int $questionId
     * @return void
     */
    public function acceptAnswerAction($replyId, $questionId)
    {
      // Fetch the answer
      if (!$answer = $this->entityManager->getRepository('\donami\Answer\Answer')->find($replyId)) {
        die('Unable to find answer');
      }

      if (!$question = $this->entityManager->getRepository('\donami\Question\Question')->find($questionId)) {
        die('Unable to find question');
      }

      $question->setBestAnswer($answer);
      $question->setBestAnswerUser($answer->getUser()->getId());

      $this->entityManager->persist($question);
      $this->entityManager->flush();

      // Redirect the user
      $this->response->redirect($this->url->create('question?id=' . $questionId));
    }

    /**
     * Remove a comment
     *
     * @param  int $replyId
     * @return boolean
     */
    public function deleteAction($replyId)
    {
      $answer = $this->entityManager->getRepository('\donami\Answer\Answer')->find($replyId);

      // Check if this answer was accepted as the best answer
      if ($answer == $answer->getQuestion()->getBestAnswer()) {
        $answer->getQuestion()->setBestAnswerUser(NULL);

        $this->entityManager->persist($answer);
      }

      $this->entityManager->remove($answer);
      $this->entityManager->flush();

      $this->response->redirect($this->url->create('question?id=' . $answer->getQuestion()->getId()));
    }

}
