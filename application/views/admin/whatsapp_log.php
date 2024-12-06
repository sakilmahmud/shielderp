<!-- views/admin/whatsapp.php -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>WhatsApp Message Logs</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/wa'); ?>" class="btn btn-info">Send New Message</a>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="commonTable" class="table table-sm table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Receiver Number</th>
                                    <th>Post Title</th>
                                    <th>Message</th>
                                    <th>Message Type</th>
                                    <th>File Path</th>
                                    <th>File Type</th>
                                    <th>Status</th>
                                    <th>Response</th>
                                    <th>Error Log</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($logs)): ?>
                                    <?php foreach ($logs as $log): ?>
                                        <tr>
                                            <td><?= $log['id'] ?></td>
                                            <td><?= $log['receiver_number'] ?></td>
                                            <td><?= !empty($log['post_title']) ? $log['post_title'] : 'N/A' ?></td>
                                            <td><?= $log['message'] ?></td>
                                            <td><?= $log['message_type'] ?></td>
                                            <td>
                                                <?php if (!empty($log['file_path'])): ?>
                                                    <a href="<?= $log['file_path'] ?>" target="_blank">View File</a>
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $log['file_type'] ?></td>
                                            <td><?= $log['msg_status'] ?></td>
                                            <td><?= $log['response_msg'] ?></td>
                                            <td><?= $log['error_log'] ?></td>
                                            <td><?= date('d-m-Y H:i:s', strtotime($log['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11">No logs found.</td>
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