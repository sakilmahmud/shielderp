<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <!-- Today's Tasks Section -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Today's Tasks</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($todaysTasks)) : ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Task</th>
                                            <th>Client</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($todaysTasks as $task) : ?>
                                            <tr>
                                                <td><a href="#" class="task-link" data-task-id="<?php echo $task->id; ?>"><?php echo $task->title; ?></a></td>
                                                <td><?php echo $task->client_name; ?></td>
                                                <td><?php echo date("h:i A", strtotime($task->due_date)); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No tasks for today.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- This Week's Tasks Section -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">This Week's Tasks</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($weeksTasks)) : ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Task</th>
                                            <th>Client</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($weeksTasks as $task) : ?>
                                            <tr>
                                                <td><a href="#" class="task-link" data-task-id="<?php echo $task->id; ?>"><?php echo $task->title; ?></a></td>
                                                <td><?php echo $task->client_name; ?></td>
                                                <td><?php echo date("jS F, Y", strtotime($task->due_date)); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No tasks for this week.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Overdue Tasks Section -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Overdue Tasks</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($overdueTasks)) : ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Task</th>
                                            <th>Client</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($overdueTasks as $task) : ?>
                                            <tr>
                                                <td><a href="#" class="task-link" data-task-id="<?php echo $task->id; ?>"><?php echo $task->title; ?></a></td>
                                                <td><?php echo $task->client_name; ?></td>
                                                <td><?php echo date("jS F, Y", strtotime($task->due_date)); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No overdue tasks.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>