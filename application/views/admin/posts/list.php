<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Posts</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/posts/add'); ?>" class="btn btn-info">Add Post</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <?php if ($this->session->flashdata('message')) : ?>
                <div class="alert alert-success">
                    <?php echo $this->session->flashdata('message'); ?>
                </div>
            <?php elseif ($this->session->flashdata('error_message')) :  ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('error_message'); ?>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="commonTable" class="table table-sm table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>File Type</th>
                                    <th>Media</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($posts)) : ?>
                                    <?php foreach ($posts as $post) : ?>
                                        <tr>
                                            <td><?php echo $post['id']; ?></td>
                                            <td><?php echo $post['post_title']; ?></td>
                                            <td><?php echo strtoupper(pathinfo($post['post_media_url'], PATHINFO_EXTENSION)); ?></td>
                                            <td>
                                                <?php if (!empty($post['post_media_url'])) : ?>
                                                    <a href="<?php echo base_url($post['post_media_url']); ?>" target="_blank" class="btn btn-info btn-sm">View</a>
                                                    <button class="btn btn-secondary btn-sm copy-btn" data-url="<?php echo base_url($post['post_media_url']); ?>">Copy URL</button>
                                                    <span class="copy-alert text-success ml-2" style="display:none;">Copied</span>
                                                <?php else : ?>
                                                    No Media
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('h:iA jS M, Y', strtotime($post['created_at'])); ?></td>
                                            <td><?php echo $post['status'] ? 'Active' : 'Inactive'; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('admin/posts/edit/' . $post['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="<?php echo base_url('admin/posts/delete/' . $post['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No posts found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('.copy-btn').click(function() {
            var url = $(this).data('url');
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(url).select();
            document.execCommand('copy');
            tempInput.remove();

            var copyAlert = $(this).siblings('.copy-alert');
            copyAlert.show();
            setTimeout(function() {
                copyAlert.fadeOut('slow');
            }, 2000);
        });
    });
</script>