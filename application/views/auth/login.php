<!doctype html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title><?php echo getSetting('site_title'); ?> - ERP Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="title" content="<?php echo getSetting('site_title'); ?> - ERP Login" />
  <meta name="author" content="Sakil M" />
  <meta name="description" content="ERP Systems" />
  <meta name="keywords" content="ERP, ERP Systems, CRM, CRM Systems, Inventory Management Systems" />

  <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/index.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/overlayscrollbars.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/bootstrap-icons.min.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/adminlte.min.css') ?>">
</head>

<body class="login-page bg-body-secondary">
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">

        <a href="<?php echo base_url(); ?>" class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
          <?php if (getSetting('admin_logo') && file_exists(FCPATH . getSetting('admin_logo'))): ?>
            <img
              src="<?php echo base_url(getSetting('admin_logo')); ?>"
              alt="<?php echo getSetting('site_title'); ?>"
              title="<?php echo getSetting('site_title'); ?>"
              class="w-100 opacity-75 shadow" />
          <?php else: ?>
            <h1 class="mb-2"><?php echo getSetting('site_title'); ?></h1>
          <?php endif; ?>
        </a>

      </div>
      <div class="card-body login-card-body">

        <?php if ($this->session->flashdata('error')): ?>
          <div class="alert alert-danger text-center">
            <?= $this->session->flashdata('error'); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger text-center">
            <?= $error; ?>
          </div>
        <?php endif; ?>

        <p class="login-box-msg">Sign in to start your session</p>

        <form action="<?php echo base_url('login') ?>" method="post">
          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="loginEmail" type="text" name="username" class="form-control" placeholder="Mobile or Username or Email" required>
              <label for="loginEmail">Mobile or Username or Email</label>
            </div>
            <div class="input-group-text"><span class="bi bi-person-fill"></span></div>
          </div>
          <div class="input-group mb-1">
            <div class="form-floating">
              <input id="loginPassword" type="password" class="form-control" name="password" placeholder="Password" required>
              <label for="loginPassword">Password</label>
            </div>
            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
          </div>

          <div class="row mb-2">
            <div class="col-8 d-inline-flex align-items-center">
              <div class="form-check">
                <input type="checkbox" name="remember" id="remember" value="1" class="form-check-input">
                <label class="form-check-label" for="remember"> Remember Me </label>
              </div>
            </div>
            <div class="col-4">
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Sign In</button>
              </div>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url('assets/admin/js/adminlte.min.js') ?>"></script>
</body>

</html>