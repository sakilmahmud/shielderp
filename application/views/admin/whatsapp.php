<!-- views/admin/whatsapp.php -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>WhatsApp</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/whatsapp-log'); ?>" class="btn btn-info">Message Log</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <?php if ($this->session->flashdata('message')) : ?>
                                <div class="alert alert-success">
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            <?php elseif ($this->session->flashdata('error_message')) :  ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('error_message'); ?>
                                </div>
                            <?php endif; ?>

                            <?php echo form_open_multipart('admin/whatsappPost', 'class="needs-validation"'); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Group</label>
                                        <select name="contacts_group_id" class="form-control" id="number_source">
                                            <option value="">Select Group</option>
                                            <?php foreach ($groups as $group): ?>
                                                <option value="<?php echo $group['id']; ?>"
                                                    <?php echo isset($contact) && $contact['contacts_group_id'] == $group['id'] ? 'selected' : ''; ?>>
                                                    <?php echo $group['title']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 allsource_contacts" style="display: none;">
                                    <div class="form-group">
                                        <label for="source_contacts">Source Contacts:</label>
                                        <select name="source_contacts[]" id="source_contacts" multiple></select>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <h4 class="mt-4">OR</h4>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="sender_number">Sender Number:</label>
                                        <input type="text" name="sender_number" id="sender_number" class="form-control" value="<?php echo isset($settings['sender_number']) ? $settings['sender_number'] : ''; ?>">
                                        <?php echo form_error('sender_number', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 all_posts">
                                    <div class="form-group">
                                        <label for="posts_id">Select a post:</label>
                                        <select name="posts_id" id="posts_id" class="form-control">
                                            <option value="">-- Select a Post --</option>
                                            <?php if (!empty($posts)) : ?>
                                                <?php foreach ($posts as $post) : ?>
                                                    <option value="<?php echo $post['id']; ?>"><?php echo $post['post_title']; ?></option>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <option value="">No posts available</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="form-group">
                                        <h4 class="mt-4">OR</h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="message">Message:</label>
                                        <textarea name="message" id="message" class="form-control"><?php echo isset($settings['message']) ? $settings['message'] : ''; ?></textarea>
                                        <?php echo form_error('message', '<div class="text-danger">', '</div>'); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="attached_file">Upload File:</label>
                                        <div class="mt-2">
                                            <input type="file" name="attached_file" id="attached_file" accept="image/*,application/pdf,audio/*,video/*">
                                        </div>
                                    </div>
                                    <small>only allowed jpg, png, gif, pdf, mp3, mp4</small>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary send-btn">Send Message</button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).on('click', '.send-btn', function() {
        $(this).addClass('disabled');
    });

    $(document).ready(function() {
        $('#posts_id').change(function() {
            if ($(this).val() !== "") {
                // If a post is selected, disable the message and upload file fields
                $('#message').prop('disabled', true).val('');
                $('#attached_file').prop('disabled', true).val('');
            } else {
                // If no post is selected, enable the message and upload file fields
                $('#message').prop('disabled', false);
                $('#attached_file').prop('disabled', false);
            }
        });
    });

    $(document).ready(function() {
        $("#posts_id").chosen().trigger("chosen:updated");
        $('#number_source').change(function() {
            var source = $(this).val();

            if (source) {
                $("#sender_number").attr('readonly', true);
                $.ajax({
                    url: '<?php echo base_url('admin/getContacts'); ?>',
                    type: 'POST',
                    data: {
                        source: source
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#source_contacts').empty(); // Clear any existing options
                        $('#source_contacts').append('<option value="">Select Contact</option>'); // Add default option

                        if (response.length > 0) {
                            $.each(response, function(index, contact) {
                                $('#source_contacts').append('<option value="' + contact.contact + '">' + contact.full_name + ' (' + contact.contact + ')</option>');
                            });
                            $('.allsource_contacts').show(); // Show the source_contacts dropdown
                            $("#source_contacts").chosen().trigger("chosen:updated");
                        } else {
                            $('.allsource_contacts').hide(); // Hide if no contacts found
                        }
                    }
                });
            } else {
                $('.allsource_contacts').hide(); // Hide the source_contacts dropdown if no source selected
            }
        });
    });
</script>