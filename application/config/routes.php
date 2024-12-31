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
$route['admin/dashboard'] = 'AdminController/dashboard';
$route['admin/settings'] = 'AdminController/generalSettings';
$route['admin/settings/update'] = 'AdminController/updateSettings';
$route['admin/settings/company_details'] = 'AdminController/companyDetails';
$route['admin/settings/bank_details'] = 'AdminController/bankDetails';

$route['admin/password'] = 'AdminController/passwordChange';
$route['admin/password/update'] = 'AdminController/updatePassword';

/** DTP Routes */
$route['admin/dtp'] = 'admin/DtpController/index';
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
$route['admin/income/add'] = 'admin/IncomeController/addIncome';
$route['admin/income/edit/(:num)'] = 'admin/IncomeController/editIncome/$1';
$route['admin/income/delete/(:num)'] = 'admin/IncomeController/deleteIncome/$1';

$route['admin/income/head'] = 'admin/IncomeController/head';
$route['admin/income/head/add'] = 'admin/IncomeController/addHead';
$route['admin/income/head/edit/(:num)'] = 'admin/IncomeController/editHead/$1';
$route['admin/income/head/delete/(:num)'] = 'admin/IncomeController/deleteHead/$1';

/** Expense routes */
$route['admin/expense'] = 'admin/ExpenseController/index';
$route['admin/expense/add'] = 'admin/ExpenseController/addExpense';
$route['admin/expense/edit/(:num)'] = 'admin/ExpenseController/editExpense/$1';
$route['admin/expense/delete/(:num)'] = 'admin/ExpenseController/deleteExpense/$1';

$route['admin/expense/head'] = 'admin/ExpenseController/head';
$route['admin/expense/head/add'] = 'admin/ExpenseController/addHead';
$route['admin/expense/head/edit/(:num)'] = 'admin/ExpenseController/editHead/$1';
$route['admin/expense/head/delete/(:num)'] = 'admin/ExpenseController/deleteHead/$1';


/** Categories */
$route['admin/categories'] = 'admin/CategoriesController/index';
$route['admin/categories/add'] = 'admin/CategoriesController/add';
$route['admin/categories/add-ajax'] = 'admin/CategoriesController/addAjax';
$route['admin/categories/edit/(:num)'] = 'admin/CategoriesController/edit/$1';
$route['admin/categories/delete/(:num)'] = 'admin/CategoriesController/delete/$1';

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

/** end of brands */

$route['admin/products'] = 'admin/ProductsController/index';
$route['admin/products/add'] = 'admin/ProductsController/add';
$route['admin/products/add-ajax'] = 'admin/ProductsController/addAjax';
$route['admin/products/edit/(:num)'] = 'admin/ProductsController/edit/$1';
$route['admin/products/delete/(:num)'] = 'admin/ProductsController/delete/$1';
$route['admin/products/search'] = 'admin/ProductsController/search';
$route['admin/products/get_product_prices'] = 'admin/ProductsController/get_product_prices';
$route['admin/products/last_purchase_price'] = 'admin/ProductsController/get_last_purchase_price';
$route['admin/products/update_price'] = 'admin/ProductsController/update_price';
$route['admin/products/getProductsByCategory'] = 'admin/ProductsController/getProductsByCategory';

$route['admin/products/bulk-upload'] = 'admin/ProductsController/bulkUpload';
$route['admin/products/process-bulk-upload'] = 'admin/ProductsController/processBulkUpload';

$route['admin/products/update_stock'] = 'admin/ProductsController/update_stock';

$route['admin/stocks'] = 'admin/StockManagementController/index';
$route['admin/stocks/add']                  = 'admin/StockManagementController/add';
$route['admin/stocks/edit/(:num)']          = 'admin/StockManagementController/edit/$1';
$route['admin/stocks/delete/(:num)']        = 'admin/StockManagementController/delete/$1';

$route['admin/customers']                   = 'admin/CustomerController/index';
$route['admin/customers/add']               = 'admin/CustomerController/add';
$route['admin/customers/edit/(:num)']       = 'admin/CustomerController/edit/$1';
$route['admin/customers/delete/(:num)']     = 'admin/CustomerController/delete/$1';

$route['admin/suppliers']                   = 'admin/SupplierController/index';
$route['admin/suppliers/add']               = 'admin/SupplierController/add';
$route['admin/suppliers/edit/(:num)']       = 'admin/SupplierController/edit/$1';
$route['admin/suppliers/delete/(:num)']     = 'admin/SupplierController/delete/$1';

$route['admin/purchase_entries']            = 'admin/PurchaseEntryController/index';
$route['admin/purchase_entries/add']        = 'admin/PurchaseEntryController/add';
$route['admin/purchase_entries/edit/(:num)'] = 'admin/PurchaseEntryController/edit/$1';
$route['admin/purchase_entries/delete/(:num)'] = 'admin/PurchaseEntryController/delete/$1';

$route['admin/invoices']                    = 'admin/InvoiceController/index';
$route['admin/invoices/create']             = 'admin/InvoiceController/createInvoice';
$route['admin/invoices/edit/(:num)']        = 'admin/InvoiceController/updateInvoice/$1';
$route['admin/invoices/view/(:num)']        = 'admin/InvoiceController/view/$1';
$route['admin/invoices/print/(:num)']       = 'admin/InvoiceController/print/$1';

$route['admin/invoices/getLastestStocks']   = 'admin/InvoiceController/getLastestStocks';
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
$route['admin/reports/salesReport'] = 'admin/ReportsController/salesReport';
$route['admin/reports/purchaseReport'] = 'admin/ReportsController/purchaseReport';
$route['admin/reports/gstReport'] = 'admin/ReportsController/gstReport';
