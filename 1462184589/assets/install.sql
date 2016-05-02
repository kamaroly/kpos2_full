
DROP TABLE IF EXISTS `%DBPREFIX%account_type`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%account_type` (
  `acc_id` tinyint(10) NOT NULL AUTO_INCREMENT,
  `acc_name` varchar(20) NOT NULL,
  `acc_credit` tinyint(1) NOT NULL DEFAULT '0',
  `acc_description` varchar(200) NOT NULL,
  PRIMARY KEY (`acc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;




DROP TABLE IF EXISTS `%DBPREFIX%app_config`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%app_config` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





INSERT INTO `%DBPREFIX%app_config` (`key`, `value`) VALUES
('address', '%ADDRESS%'),
('automatic_email_on_recur', '0'),
('company', '%COMPANY%'),
('company_logo', 'logo.png'),
('cron_key', '8g1v9RXuiMYnVo5u'),
('currency_symbol', '0'),
('decimal_number', '0'),
('decimal_point', '.'),
('decimal_symbol', '0'),
('default_email_template', ''),
('default_include_item_tax', ''),
('default_invoice_tax_rate', ''),
('default_invoice_terms', ''),
('default_item_tax_rate', ''),
('default_tax_1_name', 'Sales Tax'),
('default_tax_1_rate', '0'),
('default_tax_2_name', 'Sales Tax 2'),
('default_tax_2_rate', '0'),
('default_tax_rate', '8'),
('email', '%EMAIL%'),
('email_send_method', 'phpmail'),
('enable_multi_currency', '0'),
('exchange_rate', '0'),
('exchange_rate_name', '0'),
('expiration_days', '30'),
('fax', '%FAX%'),
('finish_sale_confirm', 'print_confirm'),
('foreign_cash_account', '47'),
('insurance_parent_account', '1'),
('invoice_logo', ''),
('language', 'english'),
('local_cash_account', '46'),
('login_logo', 'logo.png'),
('merchant_currency_code', ''),
('merchant_driver', ''),
('merchant_enabled', '0'),
('merchant_signature', ''),
('merchant_test_mode', '0'),
('merchant_username', ''),
('online_payment_method', ''),
('phone', '%PHONE%'),
('print_after_sale', 'print_after_sale'),
('print_confirm', '0'),
('print_receipt_size', 'receipt_tape'),
('return_policy', 'THANK YOU\nCOME BACK AGAIN\nYOUR BEST STORE IN TOWN'),
('sdc_port', 'COM10'),
('serial_number', '000001'),
('show_logo_on_receipt', 'show_logo_on_receipt'),
('smtp_authentication', '0'),
('smtp_port', ''),
('smtp_security', ''),
('smtp_server_address', ''),
('smtp_username', ''),
('software_certificate_number', '01'),
('software_developer_id', 'KPO'),
('tax_rate_decimal_places', '2'),
('thousands_separator', '0'),
('timezone', 'Africa/Cairo'),
('tin', '%TIN%'),
('website', '%WEBSITE%');




DROP TABLE IF EXISTS `%DBPREFIX%bookings`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `num_child` int(11) DEFAULT NULL,
  `num_adult` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




DROP TABLE IF EXISTS `%DBPREFIX%cash_transaction`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%cash_transaction` (
  `Transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Amount` double NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT '1',
  `from_account_id` int(11) NOT NULL,
  `to_account_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `personal_id` int(11) NOT NULL,
  PRIMARY KEY (`Transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




DROP TABLE IF EXISTS `%DBPREFIX%category`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%category` (
  `cid` bigint(10) NOT NULL AUTO_INCREMENT,
  `cname` varchar(100) NOT NULL,
  `camount` float(9,2) NOT NULL DEFAULT '0.00',
  `cbalance` float(9,2) NOT NULL DEFAULT '0.00',
  `cincome` tinyint(1) NOT NULL,
  `ctype` varchar(1) NOT NULL,
  `cparent` varchar(1) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;





INSERT INTO `%DBPREFIX%category` (`cid`, `cname`, `camount`, `cbalance`, `cincome`, `ctype`, `cparent`) VALUES
(1, 'INCOME', 0.00, 0.00, 1, 'b', ''),
(2, 'Salary', 3000.00, 0.00, 1, 'b', ''),
(3, 'Investment Income', 0.00, 0.00, 1, 'b', ''),
(4, 'Internet Income', 200.00, -100231.00, 1, 'b', ''),
(5, 'Other Income', 0.00, 10000000.00, 1, 'b', ''),
(6, 'EXPENSES', 0.00, 0.00, 0, 'b', ''),
(7, 'Education', 0.00, 201462.00, 0, 'b', ''),
(8, 'Stationary', 20.00, 0.00, 0, 'b', ''),
(9, 'Tution Fee', 80.00, 101231.00, 0, 'b', ''),
(10, 'Auto', 0.00, 0.00, 0, 'b', ''),
(11, 'Fuel', 300.00, 200462.00, 0, 'b', ''),
(12, 'Maintenance', 100.00, 0.00, 0, 'b', ''),
(13, 'Daily Expenses', 0.00, 0.00, 0, 'b', ''),
(14, 'Lunch', 300.00, 0.00, 0, 'b', ''),
(15, 'Utility Bills', 0.00, 100231.00, 0, 'b', ''),
(16, 'Electricity', 100.00, 0.00, 0, 'b', ''),
(17, 'Water', 30.00, 0.00, 0, 'b', ''),
(18, 'Telephone (Fixed)', 100.00, 0.00, 0, 'b', ''),
(19, 'Telephone (Handphone)', 60.00, 0.00, 0, 'b', ''),
(20, 'Internet', 77.00, 0.00, 0, 'b', ''),
(21, 'Loans', 0.00, 31231.00, 0, 'b', ''),
(22, 'Car Loan', 500.00, 101231.00, 0, 'b', ''),
(23, 'Housing Loan', 700.00, 0.00, 0, 'b', ''),
(41, 'Cash Draw', 0.00, -108000.00, 0, 'a', '1'),
(27, 'Kamaro', 10.00, 0.00, 1, 'b', ''),
(37, 'STOCK', 0.00, 10000000.00, 0, 'a', '5'),
(35, 'CORAR', 0.00, 10000000.00, 0, 'a', '4'),
(36, 'SORAS', 0.00, 46980.00, 0, 'a', '4'),
(32, 'Maintance', 10000.00, 0.00, 0, 'b', ''),
(39, 'GifCard', 0.00, 0.00, 0, 'a', '1'),
(38, 'Bralirwa', 0.00, 2437.00, 0, 'a', '3'),
(42, 'Lambert N', 0.00, -100000.00, 1, 'b', ''),
(43, 'Angelique', 10000.00, 0.00, 1, 'b', ''),
(44, 'Lambert N', 0.00, 100000.00, 0, 'a', '1'),
(45, 'MMI', 0.00, -16800.00, 0, 'a', '4'),
(46, 'RAMA', 0.00, 248750.00, 0, 'a', '4'),
(47, 'Jean Claude', 0.00, 927850.00, 0, 'a', '7'),
(48, 'KICUKIRO SCHOOL', 0.00, -10000.00, 0, 'a', '8'),
(49, 'another thing', 0.00, 0.00, 1, 'b', ''),
(50, 'Tawetimbi', 0.00, 0.00, 1, 'b', '');




DROP TABLE IF EXISTS `%DBPREFIX%category_rel`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%category_rel` (
  `child_id` bigint(10) NOT NULL,
  `parent_id` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`child_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




INSERT INTO `%DBPREFIX%category_rel` (`child_id`, `parent_id`) VALUES
(1, NULL),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, NULL),
(7, 6),
(8, 7),
(9, 7),
(10, 6),
(11, 10),
(12, 10),
(13, 6),
(14, 13),
(15, 6),
(16, 15),
(17, 15),
(18, 15),
(19, 15),
(20, 15),
(21, 6),
(22, 21),
(23, 21),
(27, 5),
(32, 10),
(42, 2),
(43, 2),
(49, 42),
(50, 49);




DROP TABLE IF EXISTS `%DBPREFIX%clientcontacts`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%clientcontacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `title` varchar(75) NOT NULL,
  `email` varchar(127) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `access_level` tinyint(1) NOT NULL DEFAULT '0',
  `supervisor` int(11) NOT NULL,
  `last_login` int(11) NOT NULL,
  `password_reset` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




DROP TABLE IF EXISTS `%DBPREFIX%currencies`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%currencies` (
  `curr_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL DEFAULT '',
  `Exchange_Rate` double(8,4) NOT NULL DEFAULT '0.0000',
  `Symbol` varchar(25) NOT NULL DEFAULT '',
  `Symbol_Suffix` tinyint(1) NOT NULL DEFAULT '0',
  `Thousand_Separator` varchar(10) NOT NULL DEFAULT '',
  `Decimal_Separator` varchar(10) NOT NULL DEFAULT '',
  `Status` tinyint(1) NOT NULL DEFAULT '0',
  `Default` tinyint(1) NOT NULL DEFAULT '0',
  `Date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`curr_id`),
  KEY `curr_id` (`curr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;


INSERT INTO `%DBPREFIX%currencies` (
 `Name`,
  `Exchange_Rate`,
   `Symbol`, 
   `Symbol_Suffix`, 
   `Thousand_Separator`,
    `Decimal_Separator`, 
    `Status`, 
    `Default`)
   VALUES
('%CURRENCY_NAME%', 1.0000, '%CURRENCY_SYMBOL%', 0, ',', '.', 1, 1);

DROP TABLE IF EXISTS `%DBPREFIX%currency_sales`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%currency_sales` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `currency_id` smallint(5) NOT NULL DEFAULT '0',
  `total_sold` double NOT NULL,
  `currency_rate` decimal(10,0) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `subtotal_sold` double NOT NULL,
  PRIMARY KEY (`sale_id`,`currency_id`),
  KEY `currency_id` (`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%customers`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%customers` (
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%employees`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%employees` (
  `username` varchar(255) NOT NULL,
  `working_mode` varchar(25) NOT NULL DEFAULT 'training',
  `password` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



INSERT INTO `%DBPREFIX%employees` (`username`, `working_mode`, `password`, `language`, `person_id`, `deleted`) VALUES
('%USERNAME%', 'training', MD5('%PASSWORD%'), '%LANGUAGE%', 1, 0);



DROP TABLE IF EXISTS `%DBPREFIX%features`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




DROP TABLE IF EXISTS `%DBPREFIX%giftcards`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%giftcards` (
  `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcard_number` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `value` double(15,2) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`giftcard_id`),
  UNIQUE KEY `giftcard_number` (`giftcard_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;




DROP TABLE IF EXISTS `%DBPREFIX%inventory`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) NOT NULL DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text NOT NULL,
  `trans_inventory` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`),
  KEY `ospos_inventory_ibfk_1` (`trans_items`),
  KEY `ospos_inventory_ibfk_2` (`trans_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1873 ;




DROP TABLE IF EXISTS `%DBPREFIX%invoices_recurring`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%invoices_recurring` (
  `invoice_recurring_id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `recur_start_date` date NOT NULL,
  `recur_end_date` date NOT NULL,
  `recur_frequency` char(2) NOT NULL,
  `recur_next_date` date NOT NULL,
  PRIMARY KEY (`invoice_recurring_id`),
  KEY `sale_id` (`sale_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;




DROP TABLE IF EXISTS `%DBPREFIX%invoice_histories`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%invoice_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `clientcontacts_id` varchar(255) NOT NULL,
  `date_sent` date NOT NULL,
  `contact_type` int(1) NOT NULL,
  `email_body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




DROP TABLE IF EXISTS `%DBPREFIX%items`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%items` (
  `name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `cost_price` double(15,2) NOT NULL,
  `unit_price` double(15,2) NOT NULL,
  `quantity` double(15,2) NOT NULL DEFAULT '0.00',
  `back_stock` double(15,2) NOT NULL DEFAULT '0.00',
  `reorder_level` double(15,2) NOT NULL DEFAULT '0.00',
  `location` varchar(255) NOT NULL,
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `dose` varchar(100) NOT NULL,
  `Generic` int(11) NOT NULL,
  `expiration_date` datetime NOT NULL,
  `image` varchar(255) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `vat_type` text NOT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  KEY `ospos_items_ibfk_1` (`supplier_id`),
  KEY `%DBPREFIX%items_categories_fk` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;




DROP TABLE IF EXISTS `%DBPREFIX%items_categories`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%items_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `cname` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;



DROP TABLE IF EXISTS `%DBPREFIX%items_taxes`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%items_taxes` (
  `item_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`item_id`,`name`,`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%item_kits`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




DROP TABLE IF EXISTS `%DBPREFIX%item_kit_items`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` double(15,2) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `ospos_item_kit_items_ibfk_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%migrations`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `%DBPREFIX%modules`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%modules` (
  `name_lang_key` varchar(255) NOT NULL,
  `desc_lang_key` varchar(255) NOT NULL,
  `sort` int(10) NOT NULL,
  `module_id` varchar(255) NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




INSERT INTO `%DBPREFIX%modules` (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES
('module_cash', 'module_cash_desc', 90, 'cash'),
('module_config', 'module_config_desc', 100, 'config'),
('module_backup', 'module_backup_desc', 110, 'config/backup'),
('module_customers', 'module_customers_desc', 10, 'customers'),
('module_employees', 'module_employees_desc', 80, 'employees'),
('module_giftcards', 'module_giftcards_desc', 90, 'giftcards'),
('module_home', 'module_home_desc', 9, 'home'),
('module_invoice', 'module_invoice_desc', 90, 'invoice'),
('module_items', 'module_items_desc', 20, 'items'),
('module_item_kits', 'module_item_kits_desc', 30, 'item_kits'),
('module_receivings', 'module_receivings_desc', 60, 'receivings'),
('module_reports', 'module_reports_desc', 50, 'reports'),
('module_sales', 'module_sales_desc', 70, 'sales'),
('module_suppliers', 'module_suppliers_desc', 40, 'suppliers');




DROP TABLE IF EXISTS `%DBPREFIX%people`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%people` (
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  `person_id` int(10) NOT NULL AUTO_INCREMENT,
  `tin` text NOT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

INSERT INTO `%DBPREFIX%people` (`first_name`, `last_name`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`, `person_id`, `tin`) VALUES
('%FIRST_NAME%', '%LAST_NAME%', '', '%EMAIL%', 'Address 1', '', '', '', '', '', '', 1, '');




DROP TABLE IF EXISTS `%DBPREFIX%permissions`;

CREATE TABLE IF NOT EXISTS `%DBPREFIX%permissions` (
  `module_id` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




INSERT INTO `%DBPREFIX%permissions` (`module_id`, `person_id`) VALUES
('cash', 1),
('config', 1),
('config/backup', 1),
('customers', 1),
('employees', 1),
('giftcards', 1),
('home', 1),
('items', 1),
('item_kits', 1),
('receivings', 1),
('reports', 1),
('sales', 1),
('suppliers', 1);





DROP TABLE IF EXISTS `%DBPREFIX%predefined`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%predefined` (
  `pid` int(3) NOT NULL AUTO_INCREMENT,
  `ptfrom` int(3) NOT NULL,
  `ptto` int(3) NOT NULL,
  `ptdesc` varchar(100) NOT NULL,
  `ptamount` float(9,2) NOT NULL,
  `ptmemo` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;




DROP TABLE IF EXISTS `%DBPREFIX%receivings`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(20) DEFAULT NULL,
  `tax_amount` decimal(10,0) NOT NULL,
  `received_amount` decimal(10,0) NOT NULL,
  PRIMARY KEY (`receiving_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




DROP TABLE IF EXISTS `%DBPREFIX%receivings_items`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%receivings_items` (
  `receiving_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL,
  `quantity_purchased` int(10) NOT NULL DEFAULT '0',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` double(15,2) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `%DBPREFIX%receivings_payments`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%receivings_payments` (
  `receiving_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  `date_paid` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_note` varchar(255) DEFAULT 'No Note provided',
  `credit` tinyint(1) NOT NULL DEFAULT '0',
  `done_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%receiving_items_taxes`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%receiving_items_taxes` (
  `receiving_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`receiving_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%sales`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `copied` tinyint(1) NOT NULL DEFAULT '0',
  `copied_date` datetime NOT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(512) DEFAULT NULL,
  `invoice_group_id` int(11) DEFAULT NULL,
  `invoice_status_id` tinyint(11) NOT NULL,
  `invoice_date_modified` datetime DEFAULT NULL,
  `invoice_date_due` date DEFAULT NULL,
  `invoice_number` varchar(20) DEFAULT NULL,
  `invoice_terms` longtext,
  `invoice_url_key` char(32) DEFAULT NULL,
  `po_number` varchar(100) DEFAULT NULL,
  `room` varchar(50) NOT NULL,
  `sdc_information` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  UNIQUE KEY `invoice_url_key` (`invoice_url_key`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  KEY `invoice_group_id` (`invoice_group_id`,`invoice_date_due`,`invoice_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100 ;




DROP TABLE IF EXISTS `%DBPREFIX%sales_items`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` double(15,2) NOT NULL DEFAULT '0.00',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` double(15,2) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



DROP TABLE IF EXISTS `%DBPREFIX%sales_items_taxes`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%sales_payments`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  `payment_amount_foreign` decimal(15,2) NOT NULL,
  `date_paid` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_note` varchar(255) DEFAULT 'No note written',
  `credit` tinyint(1) NOT NULL DEFAULT '0',
  `done_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%sales_quotations`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_quotations` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(512) DEFAULT NULL,
  `invoice_group_id` int(11) DEFAULT NULL,
  `invoice_date_created` date DEFAULT NULL,
  `invoice_date_modified` datetime DEFAULT NULL,
  `invoice_date_due` date DEFAULT NULL,
  `invoice_number` varchar(20) DEFAULT NULL,
  `invoice_terms` longtext,
  `invoice_url_key` char(32) DEFAULT NULL,
  `po_number` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  UNIQUE KEY `invoice_url_key` (`invoice_url_key`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  KEY `invoice_group_id` (`invoice_group_id`,`invoice_date_created`,`invoice_date_due`,`invoice_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;




DROP TABLE IF EXISTS `%DBPREFIX%sales_quotations_items`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_quotations_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` double(15,2) NOT NULL DEFAULT '0.00',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` double(15,2) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%sales_quotations_items_taxes`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_quotations_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%sales_quotations_payments`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_quotations_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  `payment_amount_foreign` decimal(15,2) NOT NULL,
  `date_paid` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_note` varchar(255) DEFAULT 'No note written',
  `credit` tinyint(1) NOT NULL DEFAULT '0',
  `done_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%sales_suspended`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_suspended` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(512) DEFAULT NULL,
  `po_number` varchar(100) DEFAULT NULL,
  `invoice_date_due` date DEFAULT NULL,
  `invoice_url_key` char(32) DEFAULT NULL,
  `invoice_number` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;




DROP TABLE IF EXISTS `%DBPREFIX%sales_suspended_items`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_suspended_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` double(15,2) NOT NULL DEFAULT '0.00',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` double(15,2) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%sales_suspended_items_taxes`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_suspended_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%sales_suspended_payments`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sales_suspended_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%sessions`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%suppliers`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%suppliers` (
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `tin` varchar(50) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




DROP TABLE IF EXISTS `%DBPREFIX%transaction`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%transaction` (
  `tid` bigint(20) NOT NULL AUTO_INCREMENT,
  `tdesc` varchar(100) NOT NULL,
  `tamount` float(9,2) NOT NULL,
  `tdate` date NOT NULL,
  `trelation` int(9) NOT NULL,
  `tmemo` text NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=620 ;




DROP TABLE IF EXISTS `%DBPREFIX%tran_relation`;
CREATE TABLE IF NOT EXISTS `%DBPREFIX%tran_relation` (
  `tr_id` int(7) NOT NULL AUTO_INCREMENT,
  `tr_cid` int(7) NOT NULL,
  `tr_tranid` int(7) NOT NULL,
  `tr_couple` int(7) NOT NULL,
  `tr_amount` float(9,2) NOT NULL,
  `tr_index` int(7) NOT NULL,
  `tr_acbalance` float(9,2) NOT NULL,
  PRIMARY KEY (`tr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=519 ;



ALTER TABLE `%DBPREFIX%currency_sales`
  ADD CONSTRAINT `%DBPREFIX%currency_sales_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `%DBPREFIX%sales` (`sale_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `%DBPREFIX%currency_sales_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `%DBPREFIX%currencies` (`curr_id`) ON DELETE CASCADE ON UPDATE CASCADE;



ALTER TABLE `%DBPREFIX%customers`
  ADD CONSTRAINT `%DBPREFIX%customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `%DBPREFIX%people` (`person_id`);



ALTER TABLE `%DBPREFIX%employees`
  ADD CONSTRAINT `%DBPREFIX%employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `%DBPREFIX%people` (`person_id`);


ALTER TABLE `%DBPREFIX%inventory`
  ADD CONSTRAINT `%DBPREFIX%inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `%DBPREFIX%items` (`item_id`),
  ADD CONSTRAINT `%DBPREFIX%inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `%DBPREFIX%employees` (`person_id`);


ALTER TABLE `%DBPREFIX%items_taxes`
  ADD CONSTRAINT `%DBPREFIX%items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `%DBPREFIX%items` (`item_id`) ON DELETE CASCADE;


ALTER TABLE `%DBPREFIX%item_kit_items`
  ADD CONSTRAINT `%DBPREFIX%item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `%DBPREFIX%item_kits` (`item_kit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `%DBPREFIX%item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `%DBPREFIX%items` (`item_id`) ON DELETE CASCADE;



ALTER TABLE `%DBPREFIX%permissions`
  ADD CONSTRAINT `%DBPREFIX%permissions_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `%DBPREFIX%employees` (`person_id`),
  ADD CONSTRAINT `%DBPREFIX%permissions_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `%DBPREFIX%modules` (`module_id`);



ALTER TABLE `%DBPREFIX%receivings`
  ADD CONSTRAINT `%DBPREFIX%receivings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `%DBPREFIX%employees` (`person_id`),
  ADD CONSTRAINT `%DBPREFIX%receivings_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `%DBPREFIX%suppliers` (`person_id`);



ALTER TABLE `%DBPREFIX%receivings_items`
  ADD CONSTRAINT `%DBPREFIX%receivings_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `%DBPREFIX%items` (`item_id`),
  ADD CONSTRAINT `%DBPREFIX%receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `%DBPREFIX%receivings` (`receiving_id`);



ALTER TABLE `%DBPREFIX%receivings_payments`
  ADD CONSTRAINT `%DBPREFIX%receivings_payments_ibfk_1` FOREIGN KEY (`receiving_id`) REFERENCES `%DBPREFIX%receivings` (`receiving_id`);



ALTER TABLE `%DBPREFIX%sales`
  ADD CONSTRAINT `%DBPREFIX%sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `%DBPREFIX%employees` (`person_id`),
  ADD CONSTRAINT `%DBPREFIX%sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `%DBPREFIX%customers` (`person_id`);



ALTER TABLE `%DBPREFIX%sales_items`
  ADD CONSTRAINT `%DBPREFIX%sales_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `%DBPREFIX%items` (`item_id`),
  ADD CONSTRAINT `%DBPREFIX%sales_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `%DBPREFIX%sales` (`sale_id`);



ALTER TABLE `%DBPREFIX%sales_items_taxes`
  ADD CONSTRAINT `%DBPREFIX%sales_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `%DBPREFIX%sales_items` (`sale_id`),
  ADD CONSTRAINT `%DBPREFIX%sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `%DBPREFIX%items` (`item_id`);



ALTER TABLE `%DBPREFIX%sales_payments`
  ADD CONSTRAINT `%DBPREFIX%sales_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `%DBPREFIX%sales` (`sale_id`);



ALTER TABLE `%DBPREFIX%sales_suspended`
  ADD CONSTRAINT `%DBPREFIX%sales_suspended_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `%DBPREFIX%employees` (`person_id`),
  ADD CONSTRAINT `%DBPREFIX%sales_suspended_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `%DBPREFIX%customers` (`person_id`);



ALTER TABLE `%DBPREFIX%sales_suspended_items`
  ADD CONSTRAINT `%DBPREFIX%sales_suspended_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `%DBPREFIX%items` (`item_id`),
  ADD CONSTRAINT `%DBPREFIX%sales_suspended_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `%DBPREFIX%sales_suspended` (`sale_id`);



ALTER TABLE `%DBPREFIX%sales_suspended_items_taxes`
  ADD CONSTRAINT `%DBPREFIX%sales_suspended_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `%DBPREFIX%sales_suspended_items` (`sale_id`),
  ADD CONSTRAINT `%DBPREFIX%sales_suspended_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `%DBPREFIX%items` (`item_id`);


ALTER TABLE `%DBPREFIX%sales_suspended_payments`
  ADD CONSTRAINT `%DBPREFIX%sales_suspended_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `%DBPREFIX%sales_suspended` (`sale_id`);



ALTER TABLE `%DBPREFIX%suppliers`
  ADD CONSTRAINT `%DBPREFIX%suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `%DBPREFIX%people` (`person_id`);
