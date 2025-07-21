<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Add State</h1>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php echo validation_errors(); ?>
                    <?php echo form_open('admin/settings/states/add'); ?>
                        <div class="form-group">
                            <label for="state_name">State Name</label>
                            <input type="text" class="form-control" id="state_name" name="state_name" required>
                        </div>
                        <div class="form-group">
                            <label for="state_code">State Code</label>
                            <input type="text" class="form-control" id="state_code" name="state_code" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add State</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>