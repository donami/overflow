<div>
  <h1>User: <?php echo $user->username ?></h1>

  <h3>Questions by this user:</h3>
  <div>
    <?php if (empty($questions)): ?>

      <p>No questions by this user yet</p>

    <?php else: ?>

      <?php foreach ($questions as $question): ?>

        <div>

          <a href="<?php echo $this->url->create('question?id=' . $question->id) ?>"><?php echo $question->title ?></a>

        </div>

      <?php endforeach; ?>

    <?php endif; ?>
  </div>

  <h3>Answers by this user:</h3>
  <div>
    <?php if (empty($answers)): ?>

      <p>No questions by this user yet</p>

    <?php else: ?>

      <?php foreach ($answers as $answer): ?>

        <div>

          <a href="<?php echo $this->url->create('question?id=' . $answer->question_id) ?>"><?php echo $answer->question_title ?></a>

        </div>

      <?php endforeach; ?>

    <?php endif; ?>
  </div>

  <h3>Questions answered by this user:</h3>
  <div>Not yet implemented</div>

</div>
