<div id="question-container">
  <h1><?php echo $question->title ?></h1>
  <div>
    <p><?php echo $question->body ?></p>
  </div>

  <div>
    <h6>Tags:</h6>

    <?php if (empty($tags)): ?>

      <p>No tags</p>

    <?php else: ?>

      <?php foreach ($tags as $tag): ?>

        <div>
          <a href="<?php echo $this->url->create('tag?id=' . $tag->tagId) ?>"><?php echo $tag->title ?></a>
        </div>

      <?php endforeach; ?>

    <?php endif; ?>

  </div>

  <h3>Replies</h3>
  <?php if (empty($replies)): ?>

    <p>No replies yet</p>

  <?php else: ?>

    <?php foreach ($replies as $reply): ?>

      <div class="comment">

        <div class="parent-comment">

          <div>
            <a href="<?php echo $this->url->create('user?id=' . $reply['main']['user_id'])?>">
              <?php echo $reply['main']['username'] ?>
            </a>
          </div>
          <div><?php echo $reply['main']['body'] ?></div>

        </div>

        <div class="child-comments">
          <?php if (!empty($reply['replies'])): ?>

            <?php foreach ($reply['replies'] as $comment): ?>

              <div>
                <div>
                  <a href="<?php echo $this->url->create('user?id=' . $comment['user_id'])?>">
                    <?php echo $comment['username'] ?>
                  </a>
                </div>
                <?php echo $comment['body']; ?>
              </div>

            <?php endforeach; ?>

          <?php endif; ?>
        </div>

        <div class="add-comment">
          add comment

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

      </div>

    <?php endforeach; ?>

  <?php endif; ?>

  <div>
    <form action="">
      <div>
        <textarea name="comment" cols="30" rows="10"></textarea>
      </div>
      <div>
        <button type="submit">Reply</button>
      </div>
    </form>
  </div>

</div>
