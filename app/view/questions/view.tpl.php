<div id="question-container">

  <div class="question">

    <div class="main">

      <h1 class="title"><?php echo htmlspecialchars($question->title) ?></h1>
      <div>
        <p><?php echo $this->textFilter->doFilter(htmlspecialchars($question->body), 'shortcode, markdown'); ?></p>
      </div>

    </div>

    <div class="author">

      <div class="left">

        <a href="<?php echo $this->url->create('user?id=' . $question->user_id)?>"><img class="profile" src="http://cdn.devilsworkshop.org/files/2013/01/enlarged-facebook-profile-picture.jpg" alt="Profile picture"></a>

      </div>

      <div class="right">
        <span class="date-asked"><?php echo $question->date_created ?></span>

        <a class="username" href="<?php echo $this->url->create('user?id=' . $question->user_id)?>"><?php echo $question->username ?></a>

        <ul>
          <li><i class="fa fa-star fa-lg fa-fw"></i> &nbsp; Points: 10</li>
          <li><i class="fa fa-question fa-lg fa-fw"></i> &nbsp; Questions: <?php echo $question->posts ?> </li>
          <li><i class="fa fa-lightbulb-o fa-lg fa-fw"></i> &nbsp; Answers: <?php echo $question->answers ?> </li>
          <li><i class="fa fa-comments fa-lg fa-fw"></i> &nbsp; Comments: <?php echo $question->questions ?> </li>
        </ul>

        <?php if ($admin): ?>
          <div class="actions">
            <a class="btn btn-accept" href="<?php echo $this->url->create('question/edit?id=' . $question->id)?>">Edit</a>
            <a class="btn btn-warning" href="<?php echo $this->url->create('question/delete?id=' . $question->id)?>">Delete</a>
          </div>
        <?php endif ?>

      </div>

    </div>



    <div class="tags">
      <h6>Tags:</h6>

      <?php if (empty($tags)): ?>

        <p>No tags</p>

      <?php else: ?>

        <div class="tags">

        <?php foreach ($tags as $tag): ?>

            <a class="tag" href="<?php echo $this->url->create('tag?id=' . $tag->tagId) ?>"><?php echo $tag->title ?></a>

        <?php endforeach; ?>

        </div>

      <?php endif; ?>

    </div>


  </div>

  <h3>Answers</h3>
  <?php if (empty($replies)): ?>

    <p>No replies yet</p>

  <?php else: ?>

    <?php foreach ($replies as $reply): ?>

      <div class="comment <?php echo ($question->answered_id == $reply['main']['id']) ? "accepted-comment" : ""?>">

        <div class="parent-comment">

          <div class="left">

            <div class="image">
              <a href="<?php echo $this->url->create('user?id=' . $reply['main']['user_id'])?>"><img class="profile" src="http://cdn.devilsworkshop.org/files/2013/01/enlarged-facebook-profile-picture.jpg" alt="Profile picture"></a>
            </div>

            <div>
              Points: <?php echo $reply['main']['points']; ?>
              <a href="<?php echo $this->url->create('reply/point?id=' . $reply['main']['id'] . '&amp;action=increase') ?>"><i class="fa fa-thumbs-up"></i></a>
              <a href="<?php echo $this->url->create('reply/point?id=' . $reply['main']['id'] . '&amp;action=decrease') ?>"><i class="fa fa-thumbs-down"></i></a>
            </div>

          </div>

          <div class="right">

            <div class="top">

              <div class="author">
                <?php if ($question->answered_id == $reply['main']['id']): ?>
                  <div class="accepted-badge">
                    <img src="http://icons.iconarchive.com/icons/bokehlicia/captiva/256/checkbox-icon.png" alt="Best answer" title="This answer has been accepted as the best answer"/>
                  </div>
                <?php endif; ?>

                <a class="user-link" href="<?php echo $this->url->create('user?id=' . $reply['main']['user_id'])?>">
                  <?php echo $reply['main']['username'] ?>
                </a>

              </div>

              <span class="meta"><?php echo $reply['main']['date_created'] ?></span>

            </div>

            <div class="post"><?php echo $this->textFilter->doFilter(htmlspecialchars($reply['main']['body']), 'shortcode, markdown'); ?></div>

            <?php if ($owner): ?>

              <div class="actions">
                <a class="btn btn-accept" href="<?php echo $this->url->create('reply/accept?replyID=' . $reply['main']['id']) ?>&amp;questionID=<?php echo $reply['main']['question_id']?>">
                  Accept as answer
                </a>
                <?php if ($admin): ?>
                <a class="btn btn-warning" href="<?php echo $this->url->create('reply/delete?replyID=' . $reply['main']['id']) ?>">
                  Remove answer
                </a>
                <?php endif; ?>
              </div>

            <?php endif; ?>

          </div>

        </div>

        <div class="child-comments">
          <?php if (!empty($reply['replies'])): ?>

            <h3>Replies to this answer:</h3>

            <?php foreach ($reply['replies'] as $comment): ?>

              <div class="child-comment">

                <div class="left">

                  <div class="image">
                    <a href="<?php echo $this->url->create('user?id=' . $comment['user_id'])?>"><img class="profile" src="http://cdn.devilsworkshop.org/files/2013/01/enlarged-facebook-profile-picture.jpg" alt="Profile picture"></a>
                  </div>

                  <div>
                    Points: <?php echo $comment['points']; ?>
                    <a href="<?php echo $this->url->create('reply/point?id=' . $comment['id'] . '&amp;action=increase') ?>"><i class="fa fa-thumbs-up"></i></a>
                    <a href="<?php echo $this->url->create('reply/point?id=' . $comment['id'] . '&amp;action=decrease') ?>"><i class="fa fa-thumbs-down"></i></a>
                  </div>

                  <a href="<?php echo $this->url->create('user?id=' . $comment['user_id'])?>">
                    <?php echo $comment['username'] ?>
                  </a>
                </div>

                <div class="right">
                  <div class="top">
                    <a class="user-link" href="<?php echo $this->url->create('user?id=' . $comment['user_id'])?>">
                      <?php echo $comment['username'] ?>
                    </a>
                    <span class="meta"><?php echo $reply['main']['date_created'] ?></span>
                  </div>
                  <div class="post">
                    <?php echo $this->textFilter->doFilter(htmlspecialchars($comment['body']), 'shortcode, markdown'); ?>
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
              <form action="reply/create" method="POST">
                <div>
                  <textarea name="reply_comment" rows="8" cols="40"></textarea>
                  <input type="hidden" name="comment_id" value="<?php echo $reply['main']['id'] ?>">
                  <input type="hidden" name="question_id" value="<?php echo $reply['main']['question_id'] ?>">
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
          <input type="hidden" name="question_id" value="<?php echo $question->id ?>">
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
