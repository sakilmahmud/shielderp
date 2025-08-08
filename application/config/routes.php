<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/** Authentication */
$route['register'] = 'authController/register';
$route['login'] = 'authController/login';
$route['logout'] = 'authController/logout';

/** Frontend Routes */
$route['products'] = 'Home/products'; // Main products page
$route['products/(:num)'] = 'Home/products/$1'; // Pagination route
$route['products/(:any)'] = 'Home/product_details/$1'; // Product details
$route['categories'] = 'Home/categories';
$route['categories/(:any)'] = 'Home/category_products/$1';
$route['product-type/(:any)'] = 'Home/product_type/$1';

/** End of Frontend Routes */

/** Admin Routes */
$route['admin'] = 'AdminController/index';
$route['admin/demo'] = 'AdminController/demoDashboard';
$route['admin/dashboard'] = 'AdminController/dashboard';
$route['admin/premiumOnly'] = 'AdminController/premiumOnly';
/** other dashboard routes */
$route['admin/dashboard/due_customers'] = 'AdminController/ajax_due_customers';
$route['admin/dashboard/due_suppliers'] = 'AdminController/ajax_due_suppliers';
$route['admin/ajax/low-stock'] = 'AdminController/ajax_low_stock';
/** end of other dashboard routes */

$route['admin/settings'] = 'AdminController/generalSettings';
$route['admin/settings/company_details'] = 'AdminController/companyDetails';
$route['admin/settings/bank_details'] = 'AdminController/bankDetails';
$route['admin/settings/states'] = 'admin/settings/StatesController/index';
$route['admin/settings/states/add'] = 'admin/settings/StatesController/add';
$route['admin/settings/states/edit/(:num)'] = 'admin/settings/StatesController/edit/$1';
$route['admin/settings/states/delete/(:num)'] = 'admin/settings/StatesController/delete/$1';
$route['admin/settings/update'] = 'AdminController/updateSettings';


$route['admin/password'] = 'AdminController/passwordChange';
$route['admin/password/update'] = 'AdminController/updatePassword';

/** Contacts Routes */
$route['admin/contacts/group/add'] = 'admin/ContactsGroupController/add';
$route['admin/contacts/group'] = 'admin/ContactsGroupController/index';

$route['admin/contacts'] = 'admin/ContactsController/index';
$route['admin/contacts/add'] = 'admin/ContactsController/add';
$route['admin/contacts/edit/(:num)'] = 'admin/ContactsController/edit/$1';
$route['admin/contacts/delete/(:num)'] = 'admin/ContactsController/delete/$1';
$route['admin/contacts/bulk-add'] = 'admin/ContactsController/bulkAdd';
$route['admin/contacts/get-contacts'] = 'admin/ContactsController/getContacts';

/** Account Routes */
$route['admin/accounts/account_balance'] = 'admin/AccountsController/account_balance';
$route['admin/accounts/transfer_fund'] = 'admin/AccountsController/transfer_fund';
$route['admin/accounts/list_fund_transfers'] = 'admin/AccountsController/list_fund_transfers';
$route['admin/accounts/fetch_fund_transfers'] = 'admin/AccountsController/fetch_fund_transfers';
$route['admin/accounts/get_payment_method_balance'] = 'admin/AccountsController/get_payment_method_balance';


/** DTP Routes */
$route['admin/dtp'] = 'admin/DtpController/index';
$route['admin/dtp/fetch'] = 'admin/DtpController/fetchData';
$route['admin/dtp/add'] = 'admin/DtpController/add';
$route['admin/dtp/edit/(:num)'] = 'admin/DtpController/edit/$1';
$route['admin/dtp/delete/(:num)'] = 'admin/DtpController/delete/$1';
$route['admin/dtp/get_log_data/(:num)'] = 'admin/DtpController/get_log_data/$1';
/** DTP Categories */
$route['admin/dtp/categories'] = 'admin/DtpController/categories';
$route['admin/dtp/categories/add'] = 'admin/DtpController/addCategory';
$route['admin/dtp/categories/edit/(:num)'] = 'admin/DtpController/editCategory/$1';
$route['admin/dtp/categories/delete/(:num)'] = 'admin/DtpController/deleteCategory/$1';

/** Income routes */
$route['admin/income'] = 'admin/IncomeController/index';
$route['admin/income/fetch'] = 'admin/IncomeController/fetchIncomes';
$route['admin/income/add'] = 'admin/IncomeController/addIncome';
$route['admin/income/edit/(:num)'] = 'admin/IncomeController/editIncome/$1';
$route['admin/income/delete/(:num)'] = 'admin/IncomeController/deleteIncome/$1';

$route['admin/income/head'] = 'admin/IncomeController/head';
$route['admin/income/head/add'] = 'admin/IncomeController/addHead';
$route['admin/income/head/edit/(:num)'] = 'admin/IncomeController/editHead/$1';
$route['admin/income/head/delete/(:num)'] = 'admin/IncomeController/deleteHead/$1';

/** Expense routes */
$route['admin/expense'] = 'admin/ExpenseController/index';
$route['admin/expense/fetch'] = 'admin/ExpenseController/fetchExpenses';
$route['admin/expense/add'] = 'admin/ExpenseController/addExpense';
$route['admin/expense/edit/(:num)'] = 'admin/ExpenseController/editExpense/$1';
$route['admin/expense/delete/(:num)'] = 'admin/ExpenseController/deleteExpense/$1';

$route['admin/expense/head'] = 'admin/ExpenseController/head';
$route['admin/expense/head/add'] = 'admin/ExpenseController/addHead';
$route['admin/expense/head/edit/(:num)'] = 'admin/ExpenseController/editHead/$1';
$route['admin/expense/head/delete/(:num)'] = 'admin/ExpenseController/deleteHead/$1';


/** Units **/
$route['admin/units'] = 'admin/UnitsController/index';
$route['admin/units/add'] = 'admin/UnitsController/add';
$route['admin/units/edit/(:num)'] = 'admin/UnitsController/edit/$1';
$route['admin/units/delete/(:num)'] = 'admin/UnitsController/delete/$1';

/** Categories */
$route['admin/categories'] = 'admin/CategoriesController/index';
$route['admin/categories/add'] = 'admin/CategoriesController/add';
$route['admin/categories/add-ajax'] = 'admin/CategoriesController/addAjax';
$route['admin/categories/edit/(:num)'] = 'admin/CategoriesController/edit/$1';
$route['admin/categories/delete/(:num)'] = 'admin/CategoriesController/delete/$1';
$route['admin/categories-export-import'] = 'admin/CategoriesController/export_import';
$route['admin/export/categories'] = 'admin/CategoriesController/export_csv';
$route['admin/import/categories'] = 'admin/CategoriesController/import_csv';

/** product type */
$route['admin/product-types'] = 'admin/ProductTypeController/index';
$route['admin/product-type/add'] = 'admin/ProductTypeController/add';
$route['admin/product-type/add-ajax'] = 'admin/ProductTypeController/addAjax';
$route['admin/product-type/edit/(:num)'] = 'admin/ProductTypeController/edit/$1';
$route['admin/product-type/delete/(:num)'] = 'admin/ProductTypeController/delete/$1';

/** end of product type */

/**PaymentMethods routes */
$route['admin/PaymentMethods'] = 'admin/PaymentMethodsController/index';
$route['admin/PaymentMethods/add'] = 'admin/PaymentMethodsController/add';
$route['admin/PaymentMethods/store'] = 'admin/PaymentMethodsController/store';
$route['admin/PaymentMethods/edit/(:num)'] = 'admin/PaymentMethodsController/edit/$1';
$route['admin/PaymentMethods/delete/(:num)'] = 'admin/PaymentMethodsController/delete/$1';
/**end of PaymentMethods routes */

/** brands */
$route['admin/brands'] = 'admin/BrandsController/index';
$route['admin/brands/add'] = 'admin/BrandsController/add';
$route['admin/brands/add-ajax'] = 'admin/BrandsController/addAjax';
$route['admin/brands/edit/(:num)'] = 'admin/BrandsController/edit/$1';
$route['admin/brands/delete/(:num)'] = 'admin/BrandsController/delete/$1';
$route['admin/brands-export-import'] = 'admin/BrandsController/export_import';
$route['admin/brands-export'] = 'admin/BrandsController/export_csv';
$route['admin/brands-import'] = 'admin/BrandsController/import_csv';


/** end of brands */

/** hsn code */

$route['admin/hsn-codes'] = 'admin/HsnCodes/index';
$route['admin/hsn-codes/create'] = 'admin/HsnCodes/create';
$route['admin/hsn-codes/edit/(:num)'] = 'admin/HsnCodes/edit/$1';
$route['admin/hsn-codes/delete/(:num)'] = 'admin/HsnCodes/delete/$1';
$route['admin/hsn-codes/ajax-list'] = 'admin/HsnCodes/ajax_list';
$route['admin/hsn-codes/ajax_add'] = 'admin/HsnCodes/ajax_add';
$route['admin/hsn-codes-export-import'] = 'admin/HsnCodes/export_import';
$route['admin/export/hsn-codes'] = 'admin/HsnCodes/export_csv';
$route['admin/import/hsn-codes'] = 'admin/HsnCodes/import_csv';


/** end of hsn */

$route['admin/products'] = 'admin/ProductsController/index';
$route['admin/products/ajax_list'] = 'admin/ProductsController/ajax_list';
$route['admin/products/add'] = 'admin/ProductsController/add';
$route['admin/products/add-ajax'] = 'admin/ProductsController/addAjax';
$route['admin/products/edit/(:num)'] = 'admin/ProductsController/edit/$1';
$route['admin/products/delete/(:num)'] = 'admin/ProductsController/delete/$1';
$route['admin/products/search'] = 'admin/ProductsController/search';
$route['admin/products/product_details'] = 'admin/ProductsController/get_product_details';
$route['admin/products/get_product_prices'] = 'admin/ProductsController/get_product_prices';
$route['admin/products/last_purchase_price'] = 'admin/ProductsController/get_last_purchase_price';
$route['admin/products/update_price'] = 'admin/ProductsController/update_price';
$route['admin/products/getProductsByCategory'] = 'admin/ProductsController/getProductsByCategory';

$route['admin/products-export-import'] = 'admin/ProductsController/export_import';
$route['admin/products-export-import/export'] = 'admin/ProductsController/export_csv';
$route['admin/products-export-import/import'] = 'admin/ProductsController/import_csv';

$route['admin/products/bulk-upload'] = 'admin/ProductsController/bulkUpload';
$route['admin/products/process-bulk-upload'] = 'admin/ProductsController/processBulkUpload';

$route['admin/products/update_stock'] = 'admin/ProductsController/update_stock';

$route['admin/stocks'] = 'admin/StockManagementController/index';
$route['admin/stocks/add']                  = 'admin/StockManagementController/add';
$route['admin/stocks/edit/(:num)']          = 'admin/StockManagementController/edit/$1';
$route['admin/stocks/delete/(:num)']        = 'admin/StockManagementController/delete/$1';

$route['admin/customers']             = 'admin/CustomerController/index';
$route['admin/customers/add']         = 'admin/CustomerController/add';
$route['admin/customers/edit/(:num)'] = 'admin/CustomerController/edit/$1';
$route['admin/customers/delete/(:num)'] = 'admin/CustomerController/delete/$1';
$route['admin/customers/ajax-list']         = 'admin/CustomerController/ajax_list'; // NEW
$route['admin/customers/show/(:num)'] = 'admin/CustomerController/show/$1';
$route['admin/customers/upload_photo'] = 'admin/CustomerController/upload_photo';
$route['admin/customers/remove_photo'] = 'admin/CustomerController/remove_photo';


$route['admin/suppliers']                   = 'admin/SupplierController/index';
$route['admin/suppliers/add']               = 'admin/SupplierController/add';
$route['admin/suppliers/edit/(:num)']       = 'admin/SupplierController/edit/$1';
$route['admin/suppliers/delete/(:num)']     = 'admin/SupplierController/delete/$1';

$route['admin/purchase_entries']            = 'admin/PurchaseEntryController/index';
$route['admin/purchases/fetch']            = 'admin/PurchaseEntryController/fetchPurchases';
$route['admin/purchase_entries/add']        = 'admin/PurchaseEntryController/add';
$route['admin/purchase_entries/edit/(:num)'] = 'admin/PurchaseEntryController/edit/$1';
$route['admin/purchase_entries/delete/(:num)'] = 'admin/PurchaseEntryController/delete/$1';
$route['admin/purchases/addPayment']         = 'admin/PurchaseEntryController/addPayment';
$route['admin/purchases/updatePayment']   = 'admin/PurchaseEntryController/updatePayment';
$route['admin/purchases/getPaymentDetails/(:num)']   = 'admin/PurchaseEntryController/getPaymentDetails/$1';
$route['admin/purchases/deletePayment']      = 'admin/PurchaseEntryController/deletePayment';

$route['admin/invoices'] = 'admin/InvoiceController/index';
$route['admin/invoices/fetch'] = 'admin/InvoiceController/fetchInvoices';
$route['admin/invoices/create']             = 'admin/InvoiceController/createInvoice';
$route['admin/invoices/edit/(:num)']        = 'admin/InvoiceController/updateInvoice/$1';
$route['admin/invoices/view/(:num)']        = 'admin/InvoiceController/view/$1';
$route['admin/invoices/print/(:num)']       = 'admin/InvoiceController/print/$1';
$route['admin/payments/print_receipt/(:num)'] = 'admin/InvoiceController/print_receipt/$1';
$route['admin/invoices/delete/(:num)'] = 'admin/InvoiceController/delete/$1';

$route['admin/invoices/product-details'] = 'admin/InvoiceController/product_details';

$route['admin/invoices/getLastestStocks']   = 'admin/InvoiceController/getLastestStocks';
$route['admin/invoices/latest-sale-prices']   = 'admin/InvoiceController/latest_sale_prices';
$route['admin/invoices/addPayment']         = 'admin/InvoiceController/addPayment';
$route['admin/invoices/updatePayment']   = 'admin/InvoiceController/updatePayment';
$route['admin/invoices/getPaymentDetails/(:num)']   = 'admin/InvoiceController/getPaymentDetails/$1';
$route['admin/invoices/deletePayment']      = 'admin/InvoiceController/deletePayment';

/** tasks */
$route['admin/tasks'] = 'admin/tasks/taskManagement';
$route['admin/tasks/add'] = 'admin/tasks/addTask';
$route['admin/tasks/edit/(:num)'] = 'admin/tasks/edit/$1';
$route['admin/tasks/delete/(:num)'] = 'admin/tasks/delete/$1';

$route['admin/taskDetails'] = 'AdminController/taskDetails';

/** end of tasks */
/** tasks categories */
$route['admin/task-categories'] = 'admin/TaskCategoryController/index';
$route['admin/task-categories/add'] = 'admin/TaskCategoryController/add';
$route['admin/task-categories/edit/(:num)'] = 'admin/TaskCategoryController/edit/$1';
$route['admin/task-categories/delete/(:num)'] = 'admin/TaskCategoryController/delete/$1';
$route['admin/task-categories/save'] = 'admin/TaskCategoryController/save';
$route['admin/task-categories/ajax-list'] = 'admin/TaskCategoryController/ajax_list';

/** end of tasks categories */

/** clients */
$route['admin/clients'] = 'admin/clients/index';
$route['admin/clients/add'] = 'admin/clients/add';
$route['admin/clients/edit/(:num)'] = 'admin/clients/edit/$1';
$route['admin/clients/delete/(:num)'] = 'admin/clients/delete/$1';
/** end of clients */

/** doers */
$route['admin/doers'] = 'admin/doers/index';
$route['admin/doers/add'] = 'admin/doers/add';
$route['admin/doers/edit/(:num)'] = 'admin/doers/edit/$1';
$route['admin/doers/delete/(:num)'] = 'admin/doers/delete/$1';
/** end of doers */

/** posts */
$route['admin/posts'] = 'admin/PostsController/index';
$route['admin/posts/add'] = 'admin/PostsController/add';
$route['admin/posts/edit/(:num)'] = 'admin/PostsController/edit/$1';
$route['admin/posts/delete/(:num)'] = 'admin/PostsController/delete/$1';
/** end of posts */

/** Whatsapp */

$route['admin/wa'] = 'AdminController/whatsapp';
$route['admin/whatsappPost'] = 'AdminController/whatsappPost';
$route['admin/getContacts'] = 'AdminController/getContactsBySource';

$route['admin/whatsapp-log'] = 'AdminController/whatsappLog';

/** End of Whatsapp */

/** AdminUsers */
$route['admin/adminAccounts'] = 'admin/AdminUsers/list';
$route['admin/adminAccounts/(:num)'] = 'admin/AdminUsers/list/$1';
$route['admin/adminAccounts/add'] = 'admin/AdminUsers/addAdminUser';
$route['admin/adminAccounts/edit/(:num)'] = 'admin/AdminUsers/updateAdminUser/$1';
/** end of AdminUsers */


/** Doer Panel */
$route['doer/dashboard'] = 'doer/dashboard';
$route['doer/tasks'] = 'doer/tasks';


/** Reports */
$route['admin/reports'] = 'admin/ReportsController/index';
$route['admin/reports/accounts'] = 'admin/reports/AccountController/index';
$route['admin/reports/accounts/cashbook'] = 'admin/reports/AccountController/cashbook';
$route['admin/reports/accounts/export_cashbook/(:any)'] = 'admin/reports/AccountController/export_cashbook/$1';

$route['admin/reports/accounts/payment-paid'] = 'admin/reports/AccountController/payment_paid';
$route['admin/reports/accounts/export_payment_paid/(:any)'] = 'admin/reports/AccountController/export_payment_paid/$1';

$route['admin/reports/accounts/payment-received'] = 'admin/reports/AccountController/payment_received';
$route['admin/reports/accounts/export_payment_received/(:any)'] = 'admin/reports/AccountController/export_payment_received/$1';

$route['admin/reports/accounts/daily-summary'] = 'admin/reports/AccountController/daily_summary';
$route['admin/reports/accounts/export_daily_summary/(:any)'] = 'admin/reports/AccountController/export_daily_summary/$1';

$route['admin/reports/accounts/profit-loss'] = 'admin/reports/AccountController/profit_loss';
$route['admin/reports/accounts/export_profit_loss/(:any)'] = 'admin/reports/AccountController/export_profit_loss/$1';
$route['admin/reports/accounts/profit-loss-sold-value'] = 'admin/reports/AccountController/profit_loss_sold_value';

$route['admin/reports/accounts/balance-sheet'] = 'admin/reports/AccountController/balance_sheet';
$route['admin/reports/accounts/export_balance_sheet/(:any)'] = 'admin/reports/AccountController/export_balance_sheet/$1';

$route['admin/reports/accounts/ledger'] = 'admin/reports/AccountController/ledger_dashboard';

$route['admin/reports/accounts/ledger/customers'] = 'admin/reports/AccountController/customer_ledger';
$route['admin/reports/accounts/export_customer_ledger/(:any)'] = 'admin/reports/AccountController/export_customer_ledger/$1';

$route['admin/reports/accounts/ledger/suppliers'] = 'admin/reports/AccountController/supplier_ledger';
$route['admin/reports/accounts/export_supplier_ledger/(:any)'] = 'admin/reports/AccountController/export_supplier_ledger/$1';

$route['admin/reports/accounts/ledger/income'] = 'admin/reports/AccountController/income_ledger';
$route['admin/reports/accounts/export_income_ledger/(:any)'] = 'admin/reports/AccountController/export_income_ledger/$1';

$route['admin/reports/accounts/ledger/expense'] = 'admin/reports/AccountController/expense_ledger';
$route['admin/reports/accounts/export_expense_ledger/(:any)'] = 'admin/reports/AccountController/export_expense_ledger/$1';



$route['admin/reports/accounts/tax'] = 'admin/reports/AccountController/tax';
$route['admin/reports/gstr'] = 'admin/reports/GstrController/index';
$route['admin/reports/gstr/generate_json'] = 'admin/reports/GstrController/generate_json';
$route['admin/reports/gstr/generate_csv'] = 'admin/reports/GstrController/generate_csv';
$route['admin/reports/gstr/generate_xlsx'] = 'admin/reports/GstrController/generate_xlsx';
$route['admin/reports/gstr/download_report/(:num)'] = 'admin/reports/GstrController/download_report/$1';
$route['admin/reports/gstr/delete_report/(:num)'] = 'admin/reports/GstrController/delete_report/$1';
$route['admin/reports/exportGstJson'] = 'admin/ReportsController/exportGstJson';
$route['admin/reports/accounts/chart-of-accounts'] = 'admin/reports/AccountController/chart_of_accounts';

$route['admin/reminder/add'] = 'AdminController/add_reminder';
$route['admin/reminder/detail/(:num)'] = 'AdminController/get_reminder_detail/$1';
$route['admin/reminder/done/(:num)'] = 'AdminController/mark_reminder_done/$1';

/** Inventory Reports */
$route['admin/reports/inventory'] = 'admin/reports/InventoryController/index';
$route['admin/reports/inventory/stock-availability'] = 'admin/reports/InventoryController/stock_availability';
$route['admin/reports/inventory/fast-moving-items'] = 'admin/reports/InventoryController/fast_moving_items';
$route['admin/reports/inventory/items-not-moving'] = 'admin/reports/InventoryController/items_not_moving';
$route['admin/reports/inventory/fetch_stock_availability'] = 'admin/reports/InventoryController/fetch_stock_availability';
$route['admin/reports/inventory/export_stock_availability/(:any)'] = 'admin/reports/InventoryController/export_stock_availability/$1';
$route['admin/reports/inventory/export_fast_moving_items/(:any)'] = 'admin/reports/InventoryController/export_fast_moving_items/$1';
$route['admin/reports/inventory/export_items_not_moving/(:any)'] = 'admin/reports/InventoryController/export_items_not_moving/$1';

$route['admin/reports/sales'] = 'admin/ReportsController/sales';
$route['admin/reports/customers'] = 'admin/ReportsController/customers';
$route['admin/reports/purchases'] = 'admin/ReportsController/purchases';
$route['admin/reports/suppliers'] = 'admin/ReportsController/suppliers';
$route['admin/reports/expenses'] = 'admin/ReportsController/expenses';
$route['admin/reports/staff'] = 'admin/ReportsController/staff';

$route['admin/reports/fetch_sales'] = 'admin/ReportsController/fetch_sales';
$route['admin/reports/fetch_customers'] = 'admin/ReportsController/fetch_customers';
$route['admin/reports/fetch_purchases'] = 'admin/ReportsController/fetch_purchases';
$route['admin/reports/fetch_suppliers'] = 'admin/ReportsController/fetch_suppliers';
$route['admin/reports/fetch_expenses'] = 'admin/ReportsController/fetch_expenses';
$route['admin/reports/fetch_staff'] = 'admin/ReportsController/fetch_staff';
