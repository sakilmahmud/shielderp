<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Post</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/posts'); ?>" class="btn btn-info">All Posts</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php echo form_open_multipart('admin/posts/add', 'class="needs-validation"'); ?>
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="post_title">Post Title:</label>
                        <input type="text" name="post_title" id="post_title" class="form-control" value="<?php echo set_value('post_title'); ?>">
                        <?php echo form_error('post_title', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="post_content">Post Content:</label>
                        <textarea name="post_content" id="post_content" class="form-control"><?php echo set_value('post_content'); ?></textarea>
                        <?php echo form_error('post_content', '<div class="text-danger">', '</div>'); ?>
                    </div>

                    <div class="form-group">
                        <label for="attached_file">Upload File:</label>
                        <div class="mt-2">
                            <input type="file" name="attached_file" id="attached_file" accept="image/*,application/pdf,audio/*,video/*">
                        </div>
                    </div>
                    <small>only allowed jpg, png, gif, pdf, mp3, mp4</small>
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save Post</button>
            </div>
        </div>
        <?php echo form_close(); ?>
</div>
</section>
</div>