<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <h2 class="brand-link">
    <a href="<?php echo base_url(); ?>" target="_blank">
      <img src="<?php echo base_url(getSetting('admin_logo')); ?>" width="150px" alt="GC Logo" class="brand-image">
    </a>
    <?php echo getSetting('admin_title'); ?>
  </h2>

  <!-- Sidebar -->
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-link <?php if ($activePage === 'dashboard') echo 'active'; ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item <?php if ($activePage === 'accounts' || $activePage === 'transfer_fund') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'accounts' || $activePage === 'transfer_fund') echo 'active'; ?>">
            <i class="nav-icon fas fa-wallet"></i>
            <p>
              Accounts
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/accounts/account_balance'); ?>" class="nav-link <?php if ($activePage === 'accounts') echo 'active'; ?>">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>Account Balances</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/accounts/transfer_fund'); ?>" class="nav-link <?php if ($activePage === 'transfer_fund') echo 'active'; ?>">
                <i class="nav-icon fas fa-tags"></i>
                <p>Transfer Fund</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item <?php if ($activePage === 'dtp' || $activePage === 'dtp_categories') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'dtp' || $activePage === 'dtp_categories') echo 'active'; ?>">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>
              DTP Services
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/dtp'); ?>" class="nav-link <?php if ($activePage === 'dtp') echo 'active'; ?>">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>All DTP Services</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/dtp/categories'); ?>" class="nav-link <?php if ($activePage === 'dtp_categories') echo 'active'; ?>">
                <i class="nav-icon fas fa-tags"></i>
                <p>DTP Categories</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item <?php if ($activePage === 'income_head' || $activePage === 'income') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'income' || $activePage === 'income_head') echo 'active'; ?>">
            <i class="nav-icon fas fa-wallet"></i>
            <p>
              Income
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/income'); ?>" class="nav-link <?php if ($activePage === 'income') echo 'active'; ?>">
                <i class="fas fa-list nav-icon"></i>
                <p>All Incomes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/income/head'); ?>" class="nav-link <?php if ($activePage === 'income_head') echo 'active'; ?>">
                <i class="nav-icon fas fa-tags"></i>
                <p>Income Heads</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item <?php if ($activePage === 'expense_head' || $activePage === 'expense') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'expense' || $activePage === 'expense_head') echo 'active'; ?>">
            <i class="nav-icon fas fa-wallet"></i>
            <p>
              Expenses
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/expense'); ?>" class="nav-link <?php if ($activePage === 'expense') echo 'active'; ?>">
                <i class="fas fa-list nav-icon"></i>
                <p>All Expenses</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/expense/head'); ?>" class="nav-link <?php if ($activePage === 'expense_head') echo 'active'; ?>">
                <i class="nav-icon fas fa-tags"></i>
                <p>Expense Heads</p>
              </a>
            </li>
          </ul>
        </li>


        <li class="nav-item <?php if ($activePage === 'tasks' || $activePage === 'clients' || $activePage === 'doers') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'tasks' || $activePage === 'clients' || $activePage === 'doers') echo 'active'; ?>">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>Tasks <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/tasks'); ?>" class="nav-link <?php if ($activePage === 'tasks') echo 'active'; ?>">
                <i class="nav-icon fas fa-calendar-alt"></i>
                <p>All Tasks</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/clients'); ?>" class="nav-link <?php if ($activePage === 'clients') echo 'active'; ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Clients</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/doers'); ?>" class="nav-link <?php if ($activePage === 'doers') echo 'active'; ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Doers</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item <?php if ($activePage === 'invoices' || $activePage === 'customers') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'invoices' || $activePage === 'customers') echo 'active'; ?>">
            <i class="nav-icon fas fa-file-invoice"></i>
            <p>Invoices <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/invoices'); ?>" class="nav-link <?php if ($activePage === 'invoices') echo 'active'; ?>">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>Invoices</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/customers'); ?>" class="nav-link <?php if ($activePage === 'customers') echo 'active'; ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Customers</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item <?php if ($activePage === 'purchase_entries' || $activePage === 'suppliers') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'purchase_entries' || $activePage === 'suppliers') echo 'active'; ?>">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>Purchases <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/purchase_entries'); ?>" class="nav-link <?php if ($activePage === 'purchase_entries') echo 'active'; ?>">
                <i class="nav-icon fas fa-cart-plus"></i>
                <p>Purchase Entries</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/suppliers'); ?>" class="nav-link <?php if ($activePage === 'suppliers') echo 'active'; ?>">
                <i class="nav-icon fas fa-truck"></i>
                <p>Suppliers</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item <?php if ($activePage === 'products' || $activePage === 'categories' || $activePage === 'brands' || $activePage === 'product_types') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'products' || $activePage === 'categories' || $activePage === 'brands' || $activePage === 'product_types') echo 'active'; ?>">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Products <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/products'); ?>" class="nav-link <?php if ($activePage === 'products') echo 'active'; ?>">
                <i class="nav-icon fas fa-cubes"></i>
                <p>Products</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/categories'); ?>" class="nav-link <?php if ($activePage === 'categories') echo 'active'; ?>">
                <i class="nav-icon fas fa-tags"></i>
                <p>Categories</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/brands'); ?>" class="nav-link <?php if ($activePage === 'brands') echo 'active'; ?>">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>Brands</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/product-types'); ?>" class="nav-link <?php if ($activePage === 'product_types') echo 'active'; ?>">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>Product Types</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item <?php if ($activePage === 'stocks') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'stocks') echo 'active'; ?>">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Stocks <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/stocks'); ?>" class="nav-link <?php if ($activePage === 'stocks') echo 'active'; ?>">
                <i class="nav-icon fas fa-warehouse"></i>
                <p>Stock Management</p>
              </a>
            </li>
          </ul>
        </li>


        <!-- Other Menu Items -->

        <li class="nav-item <?php if ($activePage === 'sales_report' || $activePage === 'purchase_report' || $activePage === 'gst_report') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'sales_report' || $activePage === 'purchase_report' || $activePage === 'gst_report') echo 'active'; ?>">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>
              Reports
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('admin/reports/salesReport') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales Report</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/reports/purchaseReport'); ?>" class="nav-link">
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>Purchase Report</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/reports/gstReport'); ?>" class="nav-link">
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>GST Report</p>
              </a>
            </li>

          </ul>
        </li>

        <!-- Other Menu Items -->

        <li class="nav-item has-treeview <?php if ($activePage === 'settings' || $activePage === 'bank_details' || $activePage === 'company_details') echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if ($activePage === 'settings' || $activePage === 'bank_details' || $activePage === 'company_details') echo 'active'; ?>">
            <i class="nav-icon fas fa-cog"></i>
            <p>
              Settings
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/settings'); ?>" class="nav-link <?php if ($activePage === 'settings') echo 'active'; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>General Settings</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/PaymentMethods'); ?>" class="nav-link <?php if ($activePage === 'payment_methods') echo 'active'; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Payment Methods</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/settings/company_details'); ?>" class="nav-link <?php if ($activePage === 'company_details') echo 'active'; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Company Details</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/settings/bank_details'); ?>" class="nav-link <?php if ($activePage === 'bank_details') echo 'active'; ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Bank Details</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="<?php echo base_url('admin/posts'); ?>" class="nav-link <?php if ($activePage === 'posts') echo 'active'; ?>">
            <i class="nav-icon fab fa-whatsapp"></i>
            <p>Social Media Posts</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url('admin/wa'); ?>" class="nav-link <?php if ($activePage === 'wa') echo 'active'; ?>">
            <i class="nav-icon fab fa-whatsapp"></i>
            <p>WhatsApp Promotions</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url('logout'); ?>" class="nav-link">
            <i class="nav-icon fas fa-power-off"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>