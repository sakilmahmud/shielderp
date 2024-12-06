<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Post</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php echo form_open('admin/posts/edit/' . $post['id']); ?>
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="post_title">Post Title:</label>
                        <input type="text" name="post_title" id="post_title" class="form-control" value="<?php echo set_value('post_title', $post['post_title']); ?>">
                        <?php echo form_error('post_title', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="post_content">Post Content:</label>
                        <textarea name="post_content" id="post_content" class="form-control"><?php echo set_value('post_content', $post['post_content']); ?></textarea>
                        <?php echo form_error('post_content', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="post_media_url">Media URL:</label>
                        <input type="text" name="post_media_url" id="post_media_url" class="form-control" value="<?php echo set_value('post_media_url', $post['post_media_url']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="media_type">Media Type:</label>
                        <input type="text" name="media_type" id="media_type" class="form-control" value="<?php echo set_value('media_type', $post['media_type']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" <?php echo set_select('status', '1', $post['status'] == 1); ?>>Active</option>
                            <option value="0" <?php echo set_select('status', '0', $post['status'] == 0); ?>>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Post</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </section>
</div>