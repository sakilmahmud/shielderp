<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo base_url();?>" target="_blank" class="brand-link">
      <img src="<?php echo base_url('assets/frontend/images/logo.png') ?>" alt="AP Logo" class="brand-image img-circle elevation-3" style="opacity: .8"><br>
      <span class="brand-text font-weight-light">AP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url('assets/admin/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo strtoupper($this->session->userdata('username')); ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-link <?php if ($activePage === 'dashboard') echo 'active'; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('admin/bookings'); ?>" class="nav-link <?php if ($activePage === 'bookings') echo 'active'; ?>">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>Bookings</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('admin/patients'); ?>" class="nav-link <?php if ($activePage === 'patients') echo 'active'; ?>">
              <i class="nav-icon fas fa-user"></i>
              <p>Patients</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('admin/payments'); ?>" class="nav-link <?php if ($activePage === 'payments') echo 'active'; ?>">
              <i class="nav-icon fas fa-credit-card"></i>
              <p>Payments</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('admin/password'); ?>" class="nav-link <?php if ($activePage === 'password') echo 'active'; ?>">
            <i class="nav-icon fas fa-key"></i>
              <p>Change Password</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('logout'); ?>" class="nav-link <?php if ($activePage === 'logout') echo 'active'; ?>">
              <i class="nav-icon fas fa-power-off"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>