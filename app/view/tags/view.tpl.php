<div>
  <h1>Tag: <?php echo $tag->title ?></h1>

  <h3>Questions with this tag:</h3>
  <div>
    <?php if (empty($questions)): ?>

      <p>No questions with this tag</p>

    <?php else: ?>

      <?php foreach ($questions as $question): ?>

        <div>

          <a href="#"><?php echo $question->title ?> </a>

        </div>

      <?php endforeach; ?>

    <?php endif; ?>
  </div>

</div>
