<div>
  <h1>Edit question</h1>

  <div class="box">

    <div class="title">Fill in form</div>

    <div class="content">

      <form action="edit" method="POST">

        <div>
          <label for="title">Title</label>
          <input type="text" name="title" placeholder="Title" id="title" value="<?php echo $question->getTitle() ?>"/>
        </div>

        <div>
          <label for="body">Question</label>
          <textarea name="body" id="body" cols="30" rows="10" placeholder="Message"><?php echo $question->getBody() ?></textarea>
        </div>

        <div>
          <label for="tags">Tags</label>
          <input type="text" id="tags" name="tags" placeholder="Seperate with comma" value="<?php echo $tags ?>"/>
        </div>

        <div>
          <input type="hidden" name="id" value="<?php echo $question->getId() ?>">
          <button type="submit">Save</button>
        </div>

      </form>

    </div>

  </div>

</div>
