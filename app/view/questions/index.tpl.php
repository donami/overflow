<div>

  <h1>Read all questions</h1>

  <div class="box">

    <div class="title">Questions</div>

    <div class="content">

      <div>

        <?php if ($authed): ?>

          <a class="btn" href="<?php echo $this->url->create('question/create') ?>">Submit question</a>

        <?php else: ?>

          <p><a class="btn" href="<?php echo $this->url->create('login') ?>">Login to post a question</a></p>

        <?php endif; ?>

      </div>

      <?php if (!empty($questions)): ?>

        <?php foreach ($questions as $question): ?>

          <div>
            <h4>
              <a href="<?php echo $this->url->create('question?id=' . $question->id)?>"><?php echo htmlspecialchars($question->title) ?></a>
            </h4>
            <p>
              <?php echo htmlspecialchars($question->body) ?>
            </p>
          </div>

        <?php endforeach; ?>

      <?php endif; ?>

    </div>

  </div>



</div>
