<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2><?php echo $isUpdate ? 'Edit Unit' : 'Add Unit'; ?></h2>
            <a href="<?php echo base_url('admin/units'); ?>" class="btn btn-primary">Units</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            $action = base_url('admin/units/add');
                            if ($isUpdate) {
                                $action = base_url('admin/units/edit/' . $unit['id']);
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post">
                                <div class="form-group">
                                    <label for="name">Unit Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name', isset($unit['name']) ? $unit['name'] : ''); ?>">
                                    <?php echo form_error('name'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="symbol">Symbol</label>
                                    <input type="text" class="form-control" id="symbol" name="symbol" value="<?php echo set_value('symbol', isset($unit['symbol']) ? $unit['symbol'] : ''); ?>">
                                    <?php echo form_error('symbol'); ?>
                                </div>
                                <?php if ($isUpdate) : ?>
                                    <input type="hidden" name="unit_id" value="<?php echo $unit['id']; ?>">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                <?php else : ?>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>