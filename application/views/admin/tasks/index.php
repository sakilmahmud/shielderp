<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid d-flex justify-content-between align-items-center my-2">
      <h2>List of Tasks</h2>
      <a href="<?php echo base_url('admin/tasks/add'); ?>" class="btn btn-info">Add Task</a>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body">
          <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
          <?php endif; ?>
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
            <table id="tasksTable" class="table table-sm table-striped table-bordered">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Client</th>
                  <th>Doer</th>
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

                    <td><?php echo $task['doer_name']; ?></td>

                    <td><?php echo $task['category_name']; ?></td>
                    <td><?php echo date('M d, Y h:i A', strtotime($task['start_date'])); ?></td>
                    <td><?php echo date('M d, Y h:i A', strtotime($task['due_date'])); ?></td>

                    <td>
                      <?php
                      $status_label = '';
                      if ($task['status'] == 1) {
                        if ($task['start_date'] > $task['done_time']) {
                          $status_label = '<span class="badge bg-primary">Advanced</span>';
                        } elseif ($task['due_date'] > $task['done_time']) {
                          $status_label = '<span class="badge bg-success">On time</span>';
                        } else {
                          $status_label = '<span class="badge bg-danger">Late</span>';
                        }
                      } elseif ($task['status'] == 0) {
                        if ($task['due_date'] < date('Y-m-d H:i:s')) {
                          $status_label = '<span class="badge bg-danger">Overdue</span>';
                        } elseif ($task['start_date'] < date('Y-m-d H:i:s') && $task['due_date'] > date('Y-m-d H:i:s')) {
                          $status_label = '<span class="badge bg-info">Ongoing</span>';
                        } else {
                          $status_label = '<span class="badge bg-warning">Pending</span>';
                        }
                      }
                      echo $status_label;
                      ?>
                    </td>
                    <td><?php echo ($task['done_time']) ? date('M d, Y h:i A', strtotime($task['done_time'])) : 'N/A'; ?></td>
                    <td>
                      <div class="d-flex align-items-center" style="gap: 5px;">
                        <?php if (!$task['done_time']) : ?>
                          <button class="btn btn-warning btn-sm mark-as-done" data-task-id="<?php echo $task['id']; ?>">Mark as done</button>
                        <?php else : ?>
                          <button class="btn btn-info btn-sm view-note" data-task-id="<?php echo $task['id']; ?>" data-note="<?php echo $task['note']; ?>" data-toggle="modal" data-target="#noteModal">View Note</button>
                        <?php endif; ?>
                        <a href="<?php echo base_url('admin/tasks/edit/') . $task['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php
                        if ($this->session->userdata('role') == 1) : ?>
                          <a href="<?php echo base_url('admin/tasks/delete/') . $task['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>

                        <?php endif; ?>
                      </div>
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
  </section>
</div>

<!-- Done Modal -->
<div class="modal fade" id="doneModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="doneForm" action="<?php echo base_url('doer/markTaskAsDone'); ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="doneModalLabel">Mark Task as Done</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="task_id" id="task_id">
          <div class="form-group">
            <label for="note">Delivery Note</label>
            <textarea class="form-control" name="note" id="note" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Note Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="noteModalLabel">Task Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="taskNote"></p>
      </div>
    </div>
  </div>
</div>

<!-- Client Modal -->
<div class="modal fade" id="clientModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="clientModalLabel">Client Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-striped">
          <tbody id="clientDetails"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
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
  });
</script>
<script>
  $(document).ready(function() {
    $('.mark-as-done').on('click', function() {
      var taskId = $(this).data('task-id');
      $('#task_id').val(taskId);

      $("#doneModal").modal("show");

    });

    $('.view-note').on('click', function() {
      var note = $(this).data('note');
      $('#taskNote').text(note);
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


  });
</script>

<script>
  $(document).ready(function() {
    $('#tasksTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false,
      "responsive": true
    });
  });
</script>