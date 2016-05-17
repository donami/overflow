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

    public function pointAction($answerId, $action)
    {
      $answer = $this->entityManager->getRepository('\donami\Answer\Answer')->find($answerId);
      $questionId = $answer->getQuestion()->getId();

      // Handle updating of points
      $this->updatePoint($answer, $action);

      // Redirect the user back
      $this->response->redirect($this->url->create('question?id=' . $questionId));
    }

    /**
     * Handling of points
     * @param  \donami\Answer\Answer $answer
     * @param  string $action        Accepted values: "increase" or "decrease"
     *
     * @return boolean
     */
    public function updatePoint($answer, $action)
    {
      // Get the user
      $user = $this->entityManager->getRepository('\donami\User\User')->find($this->auth->id());

      // Check if user has voted on this answer
      $exists = $this->entityManager->getRepository('\donami\Point\Point')->findOneBy(['user' => $user, 'answer' => $answer]);

      // If user already has voted on answer
      if ($exists) {
        // Return false if user already voted the same way
        // Otherwise "reset" points
        if ($action == $exists->getAction()) {
          return false;
        }

        if ($action == 'increase') {
          $answer->incrementRating();
        }
        else {
          $answer->decrementRating();
        }
        // Remove the vote in order to "reset"
        $this->entityManager->persist($answer);
        $this->entityManager->flush();


        // Remove the vote in order to "reset"
        $this->entityManager->remove($exists);
        $this->entityManager->flush();

        return true;
      }

      $point = new \donami\Point\Point;
      $point->setUser($user);
      $point->setAnswer($answer);
      $point->setAction($action);

      if ($action == 'increase') {
        $answer->incrementRating();
      }
      else {
        $answer->decrementRating();
      }

      $this->entityManager->persist($point);
      $this->entityManager->flush();

      return true;
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
