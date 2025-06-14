<footer class="app-footer">
    <div class="float-end d-none d-sm-inline"><b>Version</b> 2.1.1</div>
    <strong><?php echo getSetting('footer_text'); ?></strong>
</footer>

<!-- Task Details Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Description:</strong> <span id="taskDescription"></span></p>
                <p><strong>Category:</strong> <span id="taskCategory"></span></p>
                <p><strong>Start Time:</strong> <span id="taskStartTime"></span></p>
                <p><strong>End Time:</strong> <span id="taskEndTime"></span></p>
                <p><strong>Status:</strong> <span id="taskStatus"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>