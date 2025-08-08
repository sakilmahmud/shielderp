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
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Accounts -->
                <li class="nav-item <?php if (in_array($activePage, ['accounts', 'transfer_fund', 'payment_methods'])) echo 'menu-open'; ?>">
                    <a href="#" class="nav-link <?php if (in_array($activePage, ['accounts', 'transfer_fund', 'payment_methods'])) echo 'active'; ?>">
                        <i class="nav-icon bi bi-wallet2 text-purple"></i>
                        <p>
                            Accounts
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/accounts/account_balance'); ?>" class="nav-link <?php if ($activePage === 'accounts') echo 'active'; ?>">
                                <i class="bi bi-graph-up nav-icon"></i>
                                <p>Account Balances</p>
                            </a>
                        </li>
                        <?php if ($this->session->userdata('role') == 1) : ?>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/accounts/transfer_fund'); ?>" class="nav-link <?php if ($activePage === 'transfer_fund') echo 'active'; ?>">
                                    <i class="bi bi-arrow-left-right nav-icon"></i>
                                    <p>Transfer Fund</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/PaymentMethods'); ?>" class="nav-link <?php if ($activePage === 'payment_methods') echo 'active'; ?>">
                                    <i class="bi bi-credit-card-2-front nav-icon"></i>
                                    <p>Payment Methods</p>
                                </a>
                            </li>

                        <?php endif; ?>
                    </ul>
                </li>

                <!-- Invoices -->
                <li class="nav-item <?php if (in_array($activePage, ['invoices', 'customers'])) echo 'menu-open'; ?>">
                    <a href="#" class="nav-link <?php if (in_array($activePage, ['invoices', 'customers'])) echo 'active'; ?>">
                        <i class="nav-icon bi bi-file-earmark-text icon-invoice"></i>
                        <p>Invoices <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/invoices'); ?>" class="nav-link <?php if ($activePage === 'invoices') echo 'active'; ?>">
                                <i class="bi bi-receipt-cutoff nav-icon"></i>
                                <p>Invoices</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/customers'); ?>" class="nav-link <?php if ($activePage === 'customers') echo 'active'; ?>">
                                <i class="bi bi-people nav-icon"></i>
                                <p>Customers</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Purchases -->
                <li class="nav-item <?php if (in_array($activePage, ['purchase_entries', 'suppliers'])) echo 'menu-open'; ?>">
                    <a href="#" class="nav-link <?php if (in_array($activePage, ['purchase_entries', 'suppliers'])) echo 'active'; ?>">
                        <i class="nav-icon bi bi-cart4 icon-deeporange"></i>
                        <p>Purchases <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/purchase_entries'); ?>" class="nav-link <?php if ($activePage === 'purchase_entries') echo 'active'; ?>">
                                <i class="bi bi-cart-plus nav-icon"></i>
                                <p>Purchase Entries</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/suppliers'); ?>" class="nav-link <?php if ($activePage === 'suppliers') echo 'active'; ?>">
                                <i class="bi bi-truck nav-icon"></i>
                                <p>Suppliers</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- DTP Services -->
                <li class="nav-item <?php if (in_array($activePage, ['dtp', 'dtp_categories'])) echo 'menu-open'; ?>">
                    <a href="#" class="nav-link <?php if (in_array($activePage, ['dtp', 'dtp_categories'])) echo 'active'; ?>">
                        <i class="nav-icon bi bi-file-earmark-text icon-blue"></i>
                        <p>
                            DTP Services
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/dtp'); ?>" class="nav-link <?php if ($activePage === 'dtp') echo 'active'; ?>">
                                <i class="nav-icon bi bi-calendar-event"></i>
                                <p>All DTP Services</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/dtp/categories'); ?>" class="nav-link <?php if ($activePage === 'dtp_categories') echo 'active'; ?>">
                                <i class="nav-icon bi bi-tags"></i>
                                <p>DTP Categories</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Expenses -->
                <li class="nav-item <?php if (in_array($activePage, ['expense', 'expense_head'])) echo 'menu-open'; ?>">
                    <a href="#" class="nav-link <?php if (in_array($activePage, ['expense', 'expense_head'])) echo 'active'; ?>">
                        <i class="nav-icon bi bi-currency-rupee icon-red"></i>
                        <p>Expenses <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/expense'); ?>" class="nav-link <?php if ($activePage === 'expense') echo 'active'; ?>">
                                <i class="bi bi-list-task nav-icon"></i>
                                <p>All Expenses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/expense/head'); ?>" class="nav-link <?php if ($activePage === 'expense_head') echo 'active'; ?>">
                                <i class="bi bi-tag nav-icon"></i>
                                <p>Expense Heads</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Income -->
                <li class="nav-item <?php if (in_array($activePage, ['income', 'income_head'])) echo 'menu-open'; ?>">
                    <a href="#" class="nav-link <?php if (in_array($activePage, ['income', 'income_head'])) echo 'active'; ?>">
                        <i class="nav-icon bi bi-cash-coin icon-lightgreen"></i>
                        <p>Income <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/income'); ?>" class="nav-link <?php if ($activePage === 'income') echo 'active'; ?>">
                                <i class="bi bi-list-task nav-icon"></i>
                                <p>All Incomes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/income/head'); ?>" class="nav-link <?php if ($activePage === 'income_head') echo 'active'; ?>">
                                <i class="bi bi-tag nav-icon"></i>
                                <p>Income Heads</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Products -->
                <li class="nav-item <?php if (in_array($activePage, ['products', 'categories', 'brands', 'hsn_codes', 'product_types'])) echo 'menu-open'; ?>">
                    <a href="#" class="nav-link <?php if (in_array($activePage, ['products', 'categories', 'brands', 'hsn_codes', 'product_types'])) echo 'active'; ?>">
                        <i class="nav-icon bi bi-boxes icon-orange"></i>
                        <p>Products <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/products'); ?>" class="nav-link <?php if ($activePage === 'products') echo 'active'; ?>">
                                <i class="bi bi-box nav-icon"></i>
                                <p>Products</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/categories'); ?>" class="nav-link <?php if ($activePage === 'categories') echo 'active'; ?>">
                                <i class="bi bi-tags nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/brands'); ?>" class="nav-link <?php if ($activePage === 'brands') echo 'active'; ?>">
                                <i class="bi bi-c-circle nav-icon"></i>
                                <p>Brands</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/hsn-codes'); ?>" class="nav-link <?php if ($activePage === 'hsn_codes') echo 'active'; ?>">
                                <i class="bi bi-layers nav-icon"></i>
                                <p>HSN Codes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/product-types'); ?>" class="nav-link <?php if ($activePage === 'product_types') echo 'active'; ?>">
                                <i class="bi bi-layers nav-icon"></i>
                                <p>Product Types</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Inventory -->
                <li class="nav-item <?php if (in_array($activePage, ['add', 'stocks'])) echo 'menu-open'; ?>">
                    <a href="#" class="nav-link <?php if (in_array($activePage, ['add', 'stocks'])) echo 'active'; ?>">
                        <i class="nav-icon bi bi-building icon-inventory"></i>
                        <p>Inventory <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/stocks/add'); ?>" class="nav-link <?php if ($activePage === 'add_stock') echo 'active'; ?>">
                                <i class="bi bi-plus-square nav-icon"></i>
                                <p>Add / Update Stocks</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/stocks'); ?>" class="nav-link <?php if ($activePage === 'view_stocks') echo 'active'; ?>">
                                <i class="bi bi-search nav-icon"></i>
                                <p>View / Search Stocks</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Tasks -->
                <li class="nav-item <?php if (in_array($activePage, ['tasks', 'clients', 'doers', 'task-categories'])) echo 'menu-open'; ?>">
                    <a href="#" class="nav-link <?php if (in_array($activePage, ['tasks', 'clients', 'doers', 'task-categories'])) echo 'active'; ?>">
                        <i class="nav-icon bi bi-kanban icon-teal"></i>
                        <p>Tasks <i class="nav-arrow bi bi-chevron-right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/tasks'); ?>" class="nav-link <?php if ($activePage === 'tasks') echo 'active'; ?>">
                                <i class="bi bi-clipboard-check nav-icon"></i>
                                <p>All Tasks</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/clients'); ?>" class="nav-link <?php if ($activePage === 'clients') echo 'active'; ?>">
                                <i class="bi bi-people nav-icon"></i>
                                <p>Clients</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/doers'); ?>" class="nav-link <?php if ($activePage === 'doers') echo 'active'; ?>">
                                <i class="bi bi-person-gear nav-icon"></i>
                                <p>Doers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?php echo base_url('admin/task-categories'); ?>" class="nav-link <?php if ($activePage === 'task-categories') echo 'active'; ?>">
                                <i class="bi bi-tags nav-icon"></i>
                                <p>Task Categories</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <?php if ($this->session->userdata('role') == 1) : ?>
                    <!-- Reports -->
                    <?php
                    $pages = ['premium_only', 'reports', 'report_accounts', 'cashbook', 'payment_paid', 'payment_received', 'daily_summary', 'ledger_dashboard', 'customer_ledger', 'supplier_ledger', 'income_ledger', 'expense_ledger', 'profit_loss', 'balance_sheet', 'inventory', 'stock_availability', 'fast_moving_items', 'items_not_moving', 'gstr-reports', 'sales', 'customers', 'purchases', 'suppliers', 'expenses', 'staff'];
                    ?>
                    <li class="nav-item <?php if (in_array($activePage, $pages)) echo 'menu-open'; ?>">
                        <a href="<?php echo base_url('admin/reports'); ?>" class="nav-link <?php if (in_array($activePage, $pages)) echo 'active'; ?>">
                            <i class="nav-icon bi bi-bar-chart icon-darkblue"></i>
                            <p>Reports <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/reports/accounts'); ?>" class="nav-link <?php if ($activePage === 'report_accounts') echo 'active'; ?>">
                                    <i class="bi bi-file-earmark-bar-graph nav-icon"></i>
                                    <p>Accounts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/reports/inventory'); ?>" class="nav-link <?php if (in_array($activePage, ['inventory', 'stock_availability', 'fast_moving_items', 'items_not_moving'])) echo 'active'; ?>">
                                    <i class="bi bi-box-seam nav-icon"></i>
                                    <p>Inventory</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/reports/sales'); ?>" class="nav-link <?php if ($activePage === 'sales') echo 'active'; ?>">
                                    <i class="bi bi-graph-up-arrow nav-icon"></i>
                                    <p>Sales</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/reports/customers'); ?>" class="nav-link <?php if ($activePage === 'customers') echo 'active'; ?>">
                                    <i class="bi bi-people nav-icon"></i>
                                    <p>Customers</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/reports/purchases'); ?>" class="nav-link <?php if ($activePage === 'purchases') echo 'active'; ?>">
                                    <i class="bi bi-cart4 nav-icon"></i>
                                    <p>Purchases</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/reports/suppliers'); ?>" class="nav-link <?php if ($activePage === 'suppliers') echo 'active'; ?>">
                                    <i class="bi bi-truck nav-icon"></i>
                                    <p>Suppliers</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/reports/expenses'); ?>" class="nav-link <?php if ($activePage === 'expenses') echo 'active'; ?>">
                                    <i class="bi bi-cash-coin nav-icon"></i>
                                    <p>Expenses</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/reports/staff'); ?>" class="nav-link <?php if ($activePage === 'staff') echo 'active'; ?>">
                                    <i class="bi bi-person-badge nav-icon"></i>
                                    <p>Staff</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/reports/gstr'); ?>" class="nav-link <?php if ($activePage === 'gstr-reports') echo 'active'; ?>">
                                    <i class="bi bi-file-text nav-icon"></i>
                                    <p>GSTR</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Settings -->
                    <li class="nav-item <?php if (in_array($activePage, ['settings', 'units', 'bank_details', 'company_details'])) echo 'menu-open'; ?>">
                        <a href="#" class="nav-link <?php if (in_array($activePage, ['settings', 'units', 'bank_details', 'company_details'])) echo 'active'; ?>">
                            <i class="nav-icon bi bi-gear-fill icon-gray"></i>
                            <p>Settings <i class="nav-arrow bi bi-chevron-right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/settings'); ?>" class="nav-link <?php if ($activePage === 'settings') echo 'active'; ?>">
                                    <i class="bi bi-sliders nav-icon"></i>
                                    <p>General Settings</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/units'); ?>" class="nav-link <?php if ($activePage === 'units') echo 'active'; ?>">
                                    <i class="bi bi-rulers nav-icon"></i>
                                    <p>Units</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/settings/company_details'); ?>" class="nav-link <?php if ($activePage === 'company_details') echo 'active'; ?>">
                                    <i class="bi bi-buildings nav-icon"></i>
                                    <p>Company Details</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/settings/bank_details'); ?>" class="nav-link <?php if ($activePage === 'bank_details') echo 'active'; ?>">
                                    <i class="bi bi-bank nav-icon"></i>
                                    <p>Bank Details</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/settings/states'); ?>" class="nav-link <?php if ($activePage === 'states') echo 'active'; ?>">
                                    <i class="bi bi-map nav-icon"></i>
                                    <p>States</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Social Media -->
                    <li class="nav-item has-treeview <?php if (in_array($activePage, ['posts', 'wa', 'contacts'])) echo 'menu-open'; ?>">
                        <a href="#" class="nav-link <?php if (in_array($activePage, ['posts', 'wa', 'contacts'])) echo 'active'; ?>">
                            <i class="nav-icon bi bi-share-fill icon-social"></i>
                            <p>
                                Social Media
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/posts'); ?>" class="nav-link <?php if ($activePage === 'posts') echo 'active'; ?>">
                                    <i class="bi bi-images nav-icon"></i>
                                    <p>Social Media Posts</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/wa'); ?>" class="nav-link <?php if ($activePage === 'wa') echo 'active'; ?>">
                                    <i class="bi bi-whatsapp nav-icon"></i>
                                    <p>WhatsApp Promotions</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('admin/contacts'); ?>" class="nav-link <?php if ($activePage === 'contacts') echo 'active'; ?>">
                                    <i class="bi bi-journal-bookmark nav-icon"></i>
                                    <p>Contact DB</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($this->session->userdata('role') == 1) : ?>
                    <!-- Other Admin Users -->
                    <li class="nav-item">
                        <a href="<?php echo base_url('admin/adminAccounts'); ?>" class="nav-link <?php if ($activePage === 'adminAccounts') echo 'active'; ?>">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Admin Users</p>
                        </a>
                    </li>

                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>