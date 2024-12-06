<div class="content-wrapper">
  <!-- Task List -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>List of Tasks</h2>
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
              <?php endif; ?>
              <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger">
                  <?php echo $this->session->flashdata('error'); ?>
                </div>
              <?php endif; ?>
              <?php if (!empty($tasks)) : ?>
                <table class="table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Client</th>
                      <th>Category</th>
                      <th>Start Date</th>
                      <th>Due Date</th>
                      <th>Status</th>
                      <th>Done Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($tasks as $task) : ?>
                      <tr>
                        <td>#<?php echo $task['id']; ?></td>
                        <td><a href="#" class="task-link" data-task-id="<?php echo $task['id']; ?>"><?php echo $task['title']; ?></a></td>
                        <td><a href="#" class="client-click" data-client-id="<?php echo $task['client_id']; ?>"><?php echo $task['client_name']; ?></a></td>
                        <td><?php echo $task['category_name']; ?></td>
                        <td><?php echo date('M d, Y h:i A', strtotime($task['start_date'])); ?></td>
                        <td><?php echo date('M d, Y h:i A', strtotime($task['due_date'])); ?></td>
                        <td>
                          <?php
                          $status_label = '';
                          if ($task['status'] == 1) {
                            if ($task['start_date'] > $task['done_time']) {
                              $status_label = '<span class="badge badge-primary">Advanced</span>';
                            } elseif ($task['due_date'] > $task['done_time']) {
                              $status_label = '<span class="badge badge-success">On time</span>';
                            } else {
                              $status_label = '<span class="badge badge-danger">Late</span>';
                            }
                          } elseif ($task['status'] == 0) {
                            if ($task['due_date'] < date('Y-m-d H:i:s')) {
                              $status_label = '<span class="badge badge-danger">Overdue</span>';
                            } elseif ($task['start_date'] < date('Y-m-d H:i:s') && $task['due_date'] > date('Y-m-d H:i:s')) {
                              $status_label = '<span class="badge badge-info">Ongoing</span>';
                            } else {
                              $status_label = '<span class="badge badge-warning">Pending</span>';
                            }
                          }
                          echo $status_label;
                          ?>
                        </td>
                        <td><?php echo ($task['done_time']) ? date('M d, Y h:i A', strtotime($task['done_time'])) : 'N/A'; ?></td>
                        <td class="d-flex" style="gap: 5px;">
                          <?php if (!$task['done_time']) : ?>
                            <button class="btn btn-warning btn-sm mark-as-done" data-task-id="<?php echo $task['id']; ?>" data-toggle="modal" data-target="#doneModal">Mark as done</button>
                          <?php else : ?>
                            <button class="btn btn-info btn-sm view-note" data-task-id="<?php echo $task['id']; ?>" data-note="<?php echo $task['note']; ?>" data-toggle="modal" data-target="#noteModal">View Note</button>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              <?php else : ?>
                <p>No tasks found.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Done Modal -->
<div class="modal fade" id="doneModal" tabindex="-1" role="dialog" aria-labelledby="doneModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="doneForm" action="<?php echo base_url('doer/markTaskAsDone'); ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="doneModalLabel">Mark Task as Done</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="task_id" id="task_id">
          <div class="form-group">
            <label for="note">Delivery Note</label>
            <textarea class="form-control" name="note" id="note" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noteModalLabel">Task Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="taskNote"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Client Modal -->
<div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="clientModalLabel">Client Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-striped">
          <tbody id="clientDetails"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('.mark-as-done').on('click', function() {
      var taskId = $(this).data('task-id');
      $('#task_id').val(taskId);
    });

    $('.view-note').on('click', function() {
      var note = $(this).data('note');
      $('#taskNote').text(note);
    });

    $('#doneForm').on('submit', function(e) {
      e.preventDefault();
      var form = $(this);
      $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(),
        success: function(response) {
          // Reload the page or update the task list dynamically
          location.reload();
        },
        error: function() {
          alert('Failed to mark the task as done.');
        }
      });
    });

    $('.client-click').on('click', function(e) {
      e.preventDefault();
      var clientId = $(this).data('client-id');
      $.ajax({
        type: 'GET',
        url: '<?php echo base_url("CommonController/getClientDetails"); ?>/' + clientId,
        success: function(response) {
          var client = JSON.parse(response);
          var clientDetailsHtml = '<tr><th>Name</th><td>' + client.full_name + '</td></tr>' +
            '<tr><th>Email</th><td>' + client.email + '</td></tr>' +
            '<tr><th>Phone</th><td>' + client.mobile + '</td></tr>' +
            '<tr><th>Address</th><td>' + client.address + '</td></tr>' +
            '<tr><th>Created on</th><td>' + client.created_at + '</td></tr>' +
            '<tr><th>Added by</th><td>' + client.added_by_name + '</td></tr>' +
            '<tr><th>Status</th><td>' + client.status + '</td></tr>';
          $('#clientDetails').html(clientDetailsHtml);
          $('#clientModal').modal('show');
        },
        error: function() {
          alert('Failed to fetch client details.');
        }
      });
    });
  });
</script>