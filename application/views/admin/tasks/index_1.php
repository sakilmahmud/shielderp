<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<div class="content-wrapper">
  <!-- Task List -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h2>List of Tasks</h2>
        </div>
        <div class="col-sm-6 text-right">
          <a href="<?php echo base_url('admin/tasks/add'); ?>" class="btn btn-primary">Add Task</a>
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

              <table id="tasksTable" class="table display">
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
              </table>

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
  });
</script>
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

  $(document).ready(function() {
    $('#tasksTable').DataTable({

      //"processing": true,
      //"serverSide": true,
      "bSort": false,
      "ajax": {
        "url": "<?php echo base_url('admin/tasks/getTasks'); ?>",
        "type": "GET"
      },
      "columns": [{
          "data": "id"
        },
        {
          "data": "title"
        },
        {
          "data": "client_name"
        },
        {
          "data": "doer_name"
        },
        {
          "data": "category_name"
        },
        {
          "data": "start_date"
        },
        {
          "data": "due_date"
        },
        {
          "data": "status"
        },
        {
          "data": "done_time"
        },
        {
          "data": "actions"
        }
      ],
      "searching": true, // Enable searching
      "paging": true, // Enable pagination
      "lengthChange": true, // Allow changing the number of records per page
      "pageLength": 10 // Default records per page
    });
  });
</script>