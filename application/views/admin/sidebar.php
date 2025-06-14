<aside class="app-sidebar bg-light shadow" data-bs-theme="light">
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="<?php echo base_url('admin/dashboard'); ?>" class="brand-link">
      <!--begin::Brand Image-->
      <img
        src="<?php echo base_url(getSetting('admin_logo')); ?>"
        alt="<?php echo getSetting('admin_title'); ?>"
        class="brand-image opacity-75 shadow" />
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-light"><?php echo getSetting('admin_title'); ?></span>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!-- Brand Logo -->

  <!-- Sidebar -->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="menu"
        data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-link <?php if ($activePage === 'dashboard') echo 'active'; ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Accounts -->
        <li class="nav-item <?php if (in_array($activePage, ['accounts', 'transfer_fund', 'payment_methods'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['accounts', 'transfer_fund', 'payment_methods'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-wallet icon-purple"></i>
            <p>Accounts <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/accounts/account_balance'); ?>" class="nav-link <?php if ($activePage === 'accounts') echo 'active'; ?>">
                <i class="fas fa-balance-scale nav-icon"></i>
                <p>Account Balances</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/accounts/transfer_fund'); ?>" class="nav-link <?php if ($activePage === 'transfer_fund') echo 'active'; ?>">
                <i class="fas fa-exchange-alt nav-icon"></i>
                <p>Transfer Fund</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/PaymentMethods'); ?>" class="nav-link <?php if ($activePage === 'payment_methods') echo 'active'; ?>">
                <i class="fas fa-credit-card nav-icon"></i>
                <p>Payment Methods</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- DTP Services -->
        <li class="nav-item <?php if (in_array($activePage, ['dtp', 'dtp_categories'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['dtp', 'dtp_categories'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-file icon-blue"></i>
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

        <!-- Invoices -->
        <li class="nav-item <?php if (in_array($activePage, ['invoices', 'customers'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['invoices', 'customers'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-file-invoice icon-invoice"></i>
            <p>Invoices <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/invoices'); ?>" class="nav-link <?php if ($activePage === 'invoices') echo 'active'; ?>">
                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                <p>Invoices</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/customers'); ?>" class="nav-link <?php if ($activePage === 'customers') echo 'active'; ?>">
                <i class="fas fa-users nav-icon"></i>
                <p>Customers</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Products -->
        <li class="nav-item <?php if (in_array($activePage, ['products', 'categories', 'brands', 'product_types'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['products', 'categories', 'brands', 'product_types'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-cubes icon-orange"></i>
            <p>Products <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/products'); ?>" class="nav-link <?php if ($activePage === 'products') echo 'active'; ?>">
                <i class="fas fa-cube nav-icon"></i>
                <p>Products</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/categories'); ?>" class="nav-link <?php if ($activePage === 'categories') echo 'active'; ?>">
                <i class="fas fa-tags nav-icon"></i>
                <p>Categories</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/brands'); ?>" class="nav-link <?php if ($activePage === 'brands') echo 'active'; ?>">
                <i class="fas fa-copyright nav-icon"></i>
                <p>Brands</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/product-types'); ?>" class="nav-link <?php if ($activePage === 'product_types') echo 'active'; ?>">
                <i class="fas fa-stream nav-icon"></i>
                <p>Product Types</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Inventory -->
        <li class="nav-item <?php if (in_array($activePage, ['add', 'stocks'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['add', 'stocks'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-warehouse icon-inventory"></i>
            <p>Inventory <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/stocks/add'); ?>" class="nav-link <?php if ($activePage === 'add_stock') echo 'active'; ?>">
                <i class="fas fa-plus-square nav-icon"></i>
                <p>Add / Update Stocks</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/stocks'); ?>" class="nav-link <?php if ($activePage === 'view_stocks') echo 'active'; ?>">
                <i class="fas fa-search nav-icon"></i>
                <p>View / Search Stocks</p>
              </a>
            </li>
          </ul>
        </li>


        <!-- Purchases -->
        <li class="nav-item <?php if (in_array($activePage, ['purchase_entries', 'suppliers'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['purchase_entries', 'suppliers'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-shopping-cart icon-deeporange"></i>
            <p>Purchases <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/purchase_entries'); ?>" class="nav-link <?php if ($activePage === 'purchase_entries') echo 'active'; ?>">
                <i class="fas fa-cart-plus nav-icon"></i>
                <p>Purchase Entries</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/suppliers'); ?>" class="nav-link <?php if ($activePage === 'suppliers') echo 'active'; ?>">
                <i class="fas fa-truck nav-icon"></i>
                <p>Suppliers</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Income -->
        <li class="nav-item <?php if (in_array($activePage, ['income', 'income_head'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['income', 'income_head'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-hand-holding-usd icon-lightgreen"></i>
            <p>Income <i class="fas fa-angle-left right"></i></p>
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
                <i class="fas fa-tag nav-icon"></i>
                <p>Income Heads</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Expenses -->
        <li class="nav-item <?php if (in_array($activePage, ['expense', 'expense_head'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['expense', 'expense_head'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-money-bill-wave icon-red"></i>
            <p>Expenses <i class="fas fa-angle-left right"></i></p>
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
                <i class="fas fa-tag nav-icon"></i>
                <p>Expense Heads</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Tasks -->
        <li class="nav-item <?php if (in_array($activePage, ['tasks', 'clients', 'doers'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['tasks', 'clients', 'doers'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-tasks icon-teal"></i>
            <p>Tasks <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/tasks'); ?>" class="nav-link <?php if ($activePage === 'tasks') echo 'active'; ?>">
                <i class="fas fa-clipboard-list nav-icon"></i>
                <p>All Tasks</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/clients'); ?>" class="nav-link <?php if ($activePage === 'clients') echo 'active'; ?>">
                <i class="fas fa-user-friends nav-icon"></i>
                <p>Clients</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/doers'); ?>" class="nav-link <?php if ($activePage === 'doers') echo 'active'; ?>">
                <i class="fas fa-user-cog nav-icon"></i>
                <p>Doers</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Reports -->
        <?php
        $pages = ['premium_only', 'reports', 'cashbook', 'payment_paid', 'payment_received', 'daily_summary', 'ledger_dashboard', 'customer_ledger', 'supplier_ledger', 'income_ledger', 'expense_ledger', 'profit_loss', 'balance_sheet'];
        ?>
        <li class="nav-item <?php if (in_array($activePage, $pages)) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, $pages)) echo 'active'; ?>">
            <i class="nav-icon fas fa-chart-bar icon-darkblue"></i>
            <p>Reports <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/reports/accounts'); ?>" class="nav-link <?php if (in_array($activePage, $pages)) echo 'active'; ?>">
                <i class="fas fa-file-invoice-dollar nav-icon"></i>
                <p>Accounts</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/premiumOnly'); ?>" class="nav-link">
                <i class="fas fa-boxes nav-icon"></i>
                <p>Inventory</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/premiumOnly'); ?>" class="nav-link">
                <i class="fas fa-chart-bar nav-icon"></i>
                <p>Sales</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/premiumOnly'); ?>" class="nav-link">
                <i class="fas fa-user-friends nav-icon"></i>
                <p>Customers</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/premiumOnly'); ?>" class="nav-link">
                <i class="fas fa-shopping-cart nav-icon"></i>
                <p>Purchases</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/premiumOnly'); ?>" class="nav-link">
                <i class="fas fa-truck nav-icon"></i>
                <p>Suppliers</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/premiumOnly'); ?>" class="nav-link">
                <i class="fas fa-money-bill-wave nav-icon"></i>
                <p>Expenses</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/premiumOnly'); ?>" class="nav-link">
                <i class="fas fa-user-tie nav-icon"></i>
                <p>Staff</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/premiumOnly'); ?>" class="nav-link">
                <i class="fas fa-file-alt nav-icon"></i>
                <p>GSTR</p>
              </a>
            </li>
          </ul>
        </li>


        <!-- Settings -->
        <li class="nav-item <?php if (in_array($activePage, ['settings', 'units', 'bank_details', 'company_details'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['settings', 'units', 'bank_details', 'company_details'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-cogs icon-gray"></i>
            <p>Settings <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/settings'); ?>" class="nav-link <?php if ($activePage === 'settings') echo 'active'; ?>">
                <i class="fas fa-tools nav-icon"></i>
                <p>General Settings</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/units'); ?>" class="nav-link <?php if ($activePage === 'units') echo 'active'; ?>">
                <i class="fas fa-ruler-combined nav-icon"></i>
                <p>Units</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/settings/company_details'); ?>" class="nav-link <?php if ($activePage === 'company_details') echo 'active'; ?>">
                <i class="fas fa-building nav-icon"></i>
                <p>Company Details</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item has-treeview <?php if (in_array($activePage, ['posts', 'wa', 'contacts'])) echo 'menu-open'; ?>">
          <a href="#" class="nav-link <?php if (in_array($activePage, ['posts', 'wa', 'contacts'])) echo 'active'; ?>">
            <i class="nav-icon fas fa-share-alt icon-social"></i>
            <p>
              Social Media
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/posts'); ?>" class="nav-link <?php if ($activePage === 'posts') echo 'active'; ?>">
                <i class="nav-icon fas fa-images"></i>
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
              <a href="<?php echo base_url('admin/contacts'); ?>" class="nav-link <?php if ($activePage === 'contacts') echo 'active'; ?>">
                <i class="nav-icon fas fa-address-book"></i>
                <p>Contact DB</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</aside>