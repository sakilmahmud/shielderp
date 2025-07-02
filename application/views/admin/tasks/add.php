<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><?php echo ($isUpdate) ? "Edit " : "Add " ?>Task</h2>
            <a href="<?php echo base_url('admin/tasks'); ?>" class="btn btn-info">All Tasks</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php if (!empty(validation_errors())) : ?>
                        <div class="alert alert-danger"><?php echo validation_errors(); ?></div>
                    <?php endif; ?>
                    <?php
                    $action = base_url('admin/tasks/add');
                    if ($isUpdate) {
                        $action = base_url('admin/tasks/edit/') . $task['id'];
                    }
                    ?>
                    <form action="<?php echo $action; ?>" method="post">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter task title" value="<?php echo set_value('title', isset($task['title']) ? $task['title'] : ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Enter task description"><?php echo set_value('description', isset($task['description']) ? $task['description'] : ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <div class="my-1 d-flex justify-content-between">
                                <label for="client_id">Client</label>
                                <a href="javascript:void(0)" class="text-sm add_client"><span class="badge bg-success">Add Client</span></a>
                            </div>
                            <select class="form-control client_id" id="client_id" name="client_id" required>
                                <option value="">Select Client</option>
                                <?php foreach ($clients as $client) : ?>
                                    <option value="<?php echo $client['id']; ?>" <?php echo set_select('client_id', $client['id'], isset($task['client_id']) && $task['client_id'] == $client['id']); ?>><?php echo $client['full_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="my-1 d-flex justify-content-between">
                                <label for="doer_id">Doer</label>
                                <a href="javascript:void(0)" class="text-sm add_doer"><span class="badge bg-success">Add Doer</span></a>
                            </div>
                            <select class="form-control doer_id" id="doer_id" name="doer_id" required>
                                <option value="">Select Doer</option>
                                <?php foreach ($all_staff as $doer) : ?>
                                    <option value="<?php echo $doer['id']; ?>" <?php echo set_select('doer_id', $doer['id'], isset($task['doer_id']) && $task['doer_id'] == $doer['id']); ?>><?php echo $doer['full_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="my-1 d-flex justify-content-between">
                                <label for="category_id">Category</label>
                                <a href="javascript:void(0)" class="text-sm add_category"><span class="badge bg-success">Add Category</span></a>
                            </div>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($task_categories as $category) : ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo set_select('category_id', $category['id'], isset($task['category_id']) && $task['category_id'] == $category['id']); ?>><?php echo $category['cat_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?php echo isset($task['start_date']) ? date('Y-m-d\TH:i', strtotime($task['start_date'])) : date('Y-m-d') . 'T10:00'; ?>">
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="datetime-local" class="form-control" id="due_date" name="due_date" value="<?php echo isset($task['due_date']) ? date('Y-m-d\TH:i', strtotime($task['due_date'])) : date('Y-m-d') . 'T19:30'; ?>">
                        </div>

                        <?php if (!$isUpdate) { ?>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="0">Pending</option>
                                    <option value="1">Done</option>
                                    <option value="2">Hold</option>
                                    <option value="3">Cancelled</option>
                                </select>
                            </div>
                        <?php } ?>
                        <div class="form-group mt-3">
                            <?php if ($isUpdate) { ?>
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <button type="submit" class="btn btn-primary">Update</button>
                            <?php } else { ?>
                                <button type="submit" class="btn btn-primary">Add</button>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add Client Modal -->
<div class="modal fade" id="addClientModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClientModalLabel">Add Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addClientForm">
                    <div class="form-group">
                        <label for="client_mobile">Mobile</label>
                        <input type="text" class="form-control" id="client_mobile" name="mobile" required>
                    </div>
                    <div class="form-group">
                        <label for="client_full_name">Full Name</label>
                        <input type="text" class="form-control" id="client_full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="client_address">Address</label>
                        <input type="text" class="form-control" id="client_address" name="address">
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">Add Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Doer Modal -->
<div class="modal fade" id="addDoerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDoerModalLabel">Add Doer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDoerForm">
                    <div class="form-group">
                        <label for="doer_mobile">Mobile</label>
                        <input type="text" class="form-control" id="doer_mobile" name="mobile" required>
                    </div>
                    <div class="form-group">
                        <label for="doer_full_name">Full Name</label>
                        <input type="text" class="form-control" id="doer_full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="doer_address">Address</label>
                        <input type="text" class="form-control" id="doer_address" name="address">
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">Add Doer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Task Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="cat_name">Category Name</label>
                        <input type="text" class="form-control" id="cat_name" name="cat_name" required>
                    </div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    // Show the modal when the "Add Client" link is clicked
    $(".add_client").on("click", function() {
        $("#addClientModal").modal("show");
    });

    // Handle the form submission for adding a new client
    $("#addClientForm").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?php echo base_url('admin/clients/add_ajax'); ?>", // Adjust this URL as necessary
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    // Append the new client to the dropdown
                    var newOption = $("<option></option>")
                        .attr("value", response.client.id)
                        .text(response.client.full_name);
                    $(".client_id").append(newOption);

                    // Set the new client as the selected option
                    $(".client_id").val(response.client.id).trigger("chosen:updated");

                    // Close the modal
                    $("#addClientModal").modal("hide");

                    // Clear the form for the next time
                    $("#addClientForm")[0].reset();
                } else {
                    alert("There was an error adding the client. Please try again.");
                }
            },
            error: function() {
                alert("An error occurred. Please try again.");
            },
        });
    });

    // Show the modal when the "Add Doer" link is clicked
    $(".add_doer").on("click", function() {
        $("#addDoerModal").modal("show");
    });

    // Handle the form submission for adding a new doer
    $("#addDoerForm").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?php echo base_url('admin/doers/add_ajax'); ?>", // Adjust this URL as necessary
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    // Append the new doer to the dropdown
                    var newOption = $("<option></option>")
                        .attr("value", response.doer.id)
                        .text(response.doer.full_name);
                    $(".doer_id").append(newOption);

                    // Set the new doer as the selected option
                    $(".doer_id").val(response.doer.id).trigger("chosen:updated");

                    // Close the modal
                    $("#addDoerModal").modal("hide");

                    // Clear the form for the next time
                    $("#addDoerForm")[0].reset();
                } else {
                    alert("There was an error adding the doer. Please try again.");
                }
            },
            error: function() {
                alert("An error occurred. Please try again.");
            },
        });
    });
    // Show category modal
    $(".add_category").on("click", function() {
        $("#addCategoryModal").modal("show");
    });

    // Handle Add Category AJAX
    $("#addCategoryForm").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            url: "<?php echo base_url('admin/task_categories/add_ajax'); ?>", // You'll create this route/controller
            method: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    var newOption = $("<option></option>")
                        .attr("value", response.category.id)
                        .text(response.category.cat_name);
                    $("#category_id").append(newOption);

                    // Set selected and close modal
                    $("#category_id").val(response.category.id).trigger("chosen:updated");
                    $("#addCategoryModal").modal("hide");
                    $("#addCategoryForm")[0].reset();
                } else {
                    alert("Error adding category. Try again.");
                }
            },
            error: function() {
                alert("An error occurred. Please try again.");
            },
        });
    });
</script>