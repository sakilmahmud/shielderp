<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>Edit State</h1>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <?php echo validation_errors(); ?>
                    <?php echo form_open('admin/settings/states/edit/' . $state['id']); ?>
                        <div class="form-group">
                            <label for="state_name">State Name</label>
                            <input type="text" class="form-control" id="state_name" name="state_name" value="<?php echo $state['state_name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="state_code">State Code</label>
                            <input type="text" class="form-control" id="state_code" name="state_code" value="<?php echo $state['state_code']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update State</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>