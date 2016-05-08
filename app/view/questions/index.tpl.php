<div>
  <?php if (!empty($questions)): ?>

    <?php foreach ($questions as $question): ?>

      <div>
        <h4>
          <a href="<?php echo $this->url->create('question?id=' . $question->id)?>"><?php echo $question->title ?></a>
        </h4>
        <p>
          <?php echo $question->body ?>
        </p>
      </div>

    <?php endforeach; ?>

  <?php endif; ?>
</div>
