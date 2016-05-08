<div>
  <?php if (!empty($questions)): ?>

    <?php foreach ($questions as $question): ?>

      <div>
        <?php echo $question->title ?>
      </div>

    <?php endforeach; ?>

  <?php endif; ?>
</div>
