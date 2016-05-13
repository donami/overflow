<div>
  
  <h1>View list of tags</h1>

  <div class="box">


    <div class="title">Tags</div>

    <div class="content">
      <?php if (!empty($tags)): ?>

        <?php foreach($tags as $tag): ?>

          <div>
            <a href="<?php echo $this->url->create('tag?id=' . $tag->id) ?>"><?php echo $tag->title ?></a>
          </div>

        <?php endforeach; ?>

      <?php else: ?>

        <p>No tags found</p>

      <?php endif; ?>
    </div>

  </div>

</div>
