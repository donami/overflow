<div id="question-container">

  <div class="question">

    <div class="main">
      <div style="float: right">
        Points: <?php echo $question->getRating() ?>
        <?php if ($authed): ?>
        <a href="<?php echo $this->url->create('point/increase?type=question&amp;id=' . $question->getId()) ?>"><i class="fa fa-thumbs-up"></i></a>
        <a href="<?php echo $this->url->create('point/decrease?type=question&amp;id=' . $question->getId()) ?>"><i class="fa fa-thumbs-down"></i></a>
        <?php endif; ?>
      </div>

      <h1 class="title"><?php echo htmlspecialchars($question->getTitle()) ?></h1>
      <div>
        <p><?php echo $this->textFilter->doFilter(htmlspecialchars($question->getBody()), 'shortcode, markdown'); ?></p>
      </div>

    </div>

    <div class="author">

      <div class="left">

        <a href="<?php echo $this->url->create('user?id=' . $question->getUser()->getId())?>"><img class="profile" src="http://cdn.devilsworkshop.org/files/2013/01/enlarged-facebook-profile-picture.jpg" alt="Profile picture"></a>

      </div>

      <div class="right">
        <span class="date-asked"><?php echo $question->getDateCreated() ?></span>

        <a class="username" href="<?php echo $this->url->create('user?id=' . $question->getUser()->getId())?>"><?php echo $question->getUser()->getUsername() ?></a>

        <ul>
          <li><i class="fa fa-star fa-lg fa-fw"></i> &nbsp; Points: 10</li>
          <li><i class="fa fa-question fa-lg fa-fw"></i> &nbsp; Questions: <?php echo $question->getUser()->getQuestions()->count() ?> </li>
          <li><i class="fa fa-lightbulb-o fa-lg fa-fw"></i> &nbsp; Answers: <?php echo $question->getUser()->getAnswers()->count() ?> </li>
          <li><i class="fa fa-comments fa-lg fa-fw"></i> &nbsp; Comments: <?php echo $question->getUser()->getComments()->count() ?> </li>
        </ul>

        <?php if ($admin): ?>
          <div class="actions">
            <a class="btn btn-accept" href="<?php echo $this->url->create('question/edit?id=' . $question->getId())?>">Edit</a>
            <a class="btn btn-warning" href="<?php echo $this->url->create('question/delete?id=' . $question->getId())?>">Delete</a>
          </div>
        <?php endif ?>

      </div>

    </div>



    <div class="tags">
      <h6>Tags:</h6>
      <?php if ($tags->count() <= 0): ?>

        <p>No tags</p>

      <?php else: ?>

        <div class="tags">

        <?php foreach ($tags as $tag): ?>

            <a class="tag" href="<?php echo $this->url->create('tag?tag=' . $tag->getTitle()) ?>"><?php echo $tag->getTitle() ?></a>

        <?php endforeach; ?>

        </div>

      <?php endif; ?>

    </div>


  </div>

  <h3>Answers</h3>
  <a href="<?php echo $this->url->create('question?id=' . $question->getId() . '&amp;sort=rating')?>">Sort by rating</a> <a href="<?php echo $this->url->create('question?id=' . $question->getId())?>">Sort by date</a>
  <?php if ($answers->count() <= 0): ?>

    <p>No replies yet</p>

  <?php else: ?>

    <?php foreach ($answers as $answer): ?>

      <div class="comment <?php echo ($question->getBestAnswer() == $answer) ? "accepted-comment" : ""?>">

        <div class="parent-comment">

          <div class="left">

            <div class="image">
              <a href="<?php echo $this->url->create('user?id=' . $answer->getUser()->getId())?>"><img class="profile" src="http://cdn.devilsworkshop.org/files/2013/01/enlarged-facebook-profile-picture.jpg" alt="Profile picture"></a>
            </div>

            <div>
              Points: <?php echo $answer->getRating() ?>
              <?php if ($authed): ?>
                <a href="<?php echo $this->url->create('point/increase?type=answer&amp;id=' . $answer->getId()) ?>"><i class="fa fa-thumbs-up"></i></a>
                <a href="<?php echo $this->url->create('point/decrease?type=answer&amp;id=' . $answer->getId()) ?>"><i class="fa fa-thumbs-down"></i></a>
              <?php endif; ?>
            </div>

          </div>

          <div class="right">

            <div class="top">

              <div class="author">
                <?php if ($question->getBestAnswer() == $answer): ?>
                  <div class="accepted-badge">
                    <img src="http://icons.iconarchive.com/icons/bokehlicia/captiva/256/checkbox-icon.png" alt="Best answer" title="This answer has been accepted as the best answer"/>
                  </div>
                <?php endif; ?>

                <a class="user-link" href="<?php echo $this->url->create('user?id=' . $answer->getUser()->getId())?>">
                  <?php echo $answer->getUser()->getUsername() ?>
                </a>

              </div>

              <span class="meta"><?php echo $answer->getDateCreated() ?></span>

            </div>

            <div class="post"><?php echo $this->textFilter->doFilter(htmlspecialchars($answer->getBody()), 'shortcode, markdown'); ?></div>

            <?php if ($owner): ?>

              <div class="actions">

                <?php if (!$owner): ?>

                  <a class="btn btn-accept" href="<?php echo $this->url->create('reply/accept?replyID=' . $answer->getId()) ?>&amp;questionID=<?php echo $question->getId()?>">
                    Accept as answer
                  </a>

                <?php endif; ?>

                <?php if ($admin): ?>
                <a class="btn btn-warning" href="<?php echo $this->url->create('reply/delete?replyID=' . $answer->getId()) ?>">
                  Remove answer
                </a>
                <?php endif; ?>
              </div>

            <?php endif; ?>

          </div>

        </div>

        <div class="child-comments">
          <?php if ($answer->getComments()->count() > 0): ?>

            <h3>Replies to this answer:</h3>

            <?php foreach ($answer->getComments() as $comment): ?>

              <div class="child-comment">

                <div class="left">

                  <div class="image">
                    <a href="<?php echo $this->url->create('user?id=' . $comment->getUser()->getId())?>"><img class="profile" src="http://cdn.devilsworkshop.org/files/2013/01/enlarged-facebook-profile-picture.jpg" alt="Profile picture"></a>
                  </div>

                  <a href="<?php echo $this->url->create('user?id=' . $comment->getUser()->getId())?>">
                    <?php echo $comment->getUser()->getUsername() ?>
                  </a>

                  <div>
                    Points: <?php echo $comment->getRating() ?>
                    <?php if ($authed): ?>
                      <a href="<?php echo $this->url->create('point/increase?type=comment&amp;id=' . $comment->getId()) ?>"><i class="fa fa-thumbs-up"></i></a>
                      <a href="<?php echo $this->url->create('point/decrease?type=comment&amp;id=' . $comment->getId()) ?>"><i class="fa fa-thumbs-down"></i></a>
                    <?php endif; ?>
                  </div>

                </div>

                <div class="right">
                  <div class="top">
                    <a class="user-link" href="<?php echo $this->url->create('user?id=' . $comment->getUser()->getId())?>">
                      <?php echo $comment->getUser()->getUsername() ?>
                    </a>
                    <span class="meta"><?php echo $comment->getDateCreated() ?></span>
                  </div>
                  <div class="post">
                    <?php echo $this->textFilter->doFilter(htmlspecialchars($comment->getBody()), 'shortcode, markdown'); ?>
                  </div>
                </div>

              </div>

            <?php endforeach; ?>

          <?php endif; ?>
        </div>

        <?php if ($authed): ?>

          <div class="add-comment">
            <h3>Reply to this comment</h3>

            <div>
              <form action="comment/create" method="POST">
                <div>
                  <textarea name="reply_comment" rows="8" cols="40"></textarea>
                  <input type="hidden" name="comment_id" value="<?php echo $answer->getId() ?>">
                  <input type="hidden" name="question_id" value="<?php echo $question->getId() ?>">
                </div>
                <button type="submit">Reply</button>
              </form>
            </div>

          </div>

        <?php endif; ?>

      </div>

    <?php endforeach; ?>

  <?php endif; ?>

  <div class="form-add-answer">
    <?php if ($authed): ?>

      <form action="reply/create" method="POST">
        <h3>Answer the question</h3>
        <p>Do you know the answer? Help by submitting your thoughts below</p>
        <div>
          <textarea name="reply_comment" cols="30" rows="10"></textarea>
          <input type="hidden" name="question_id" value="<?php echo $question->getId() ?>">
        </div>
        <div>
          <button type="submit">Reply</button>
        </div>
      </form>

    <?php else: ?>

      <div class="not-logged-in">
        <h2>You need to be logged in to answer</h2>
        <p>
          In order to submit an answer you need to be logged in. <br>
          You can sign up for an account <a href="<?php echo $this->url->create('register')?>">here.</a> <br><br>
          If you already are a member you can sign in <a href="<?php echo $this->url->create('login')?>">here.</a>
        </p>
      </div>

    <?php endif ?>
  </div>

</div>
