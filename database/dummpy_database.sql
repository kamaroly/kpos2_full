-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 20, 2013 at 10:35 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `kpos.2`
--

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_account_type`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_account_type` (
  `acc_id` tinyint(10) NOT NULL AUTO_INCREMENT,
  `acc_name` varchar(20) NOT NULL,
  `acc_credit` tinyint(1) NOT NULL DEFAULT '0',
  `acc_description` varchar(200) NOT NULL,
  PRIMARY KEY (`acc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `_DBPREFIX_account_type`
--

INSERT INTO `_DBPREFIX_account_type` (`acc_id`, `acc_name`, `acc_credit`, `acc_description`) VALUES
	                (1, 'Cash', 0,'Sales account'),
					(2, 'Saving', 0,'Savings account'),
					(3, 'Investment', 0,'Investment account');

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_app_config`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_app_config` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_DBPREFIX_app_config`
--

INSERT INTO `_DBPREFIX_app_config` (`key`, `value`) VALUES
('address', '123 Nowhere street'),
('company', 'Good News'),
('company_logo', 'logo.png'),
('currency_symbol', 'RWF '),
('decimal_number', '0'),
('decimal_symbol', '.'),
('default_tax_1_name', 'Sales Tax'),
('default_tax_1_rate', ''),
('default_tax_2_name', 'Sales Tax 2'),
('default_tax_2_rate', ''),
('default_tax_rate', '8'),
('email', 'admin@kpod.com'),
('exchange_rate', '666'),
('exchange_rate_name', 'USD'),
('expiration_days', '3'),
('fax', ''),
('foreign_cash_account', '38'),
('insurance_parent_account', '4'),
('language', 'english'),
('local_cash_account', '41'),
('phone', '555-555-5555'),
('print_after_sale', 'print_after_sale'),
('print_receipt_size', 'receipt'),
('return_policy', 'thank you for shoping with us'),
('thousands_separator', '0'),
('timezone', 'Africa/Cairo'),
('website', '');



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_category`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_category` (
  `cid` bigint(10) NOT NULL AUTO_INCREMENT,
  `cname` varchar(100) NOT NULL,
  `camount` float(9,2) NOT NULL DEFAULT '0.00',
  `cbalance` float(9,2) NOT NULL DEFAULT '0.00',
  `cincome` tinyint(1) NOT NULL,
  `ctype` varchar(1) NOT NULL,
  `cparent` varchar(1) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `_DBPREFIX_category`
--

INSERT INTO `_DBPREFIX_category` (`cid`, `cname`, `camount`, `cbalance`, `cincome`, `ctype`, `cparent`) VALUES
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
(32, 'Maintance', 10000.00, 0.00, 0, 'b', '');

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_category_rel`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_category_rel` (
  `child_id` bigint(10) NOT NULL,
  `parent_id` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`child_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_DBPREFIX_category_rel`
--

INSERT INTO `_DBPREFIX_category_rel` (`child_id`, `parent_id`) VALUES
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
(43, 2);

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_ci_sessions`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_DBPREFIX_ci_sessions`
--

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_customers`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_customers` (
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_employees`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_employees` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_DBPREFIX_employees`
--
INSERT INTO `_DBPREFIX_employees` (`username`, `password`, `language`, `person_id`, `deleted`) VALUES
('admin', '439a6de57d475c1a0ba9bcb1c39f0af6', 'english', 1, 0);

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_giftcards`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_giftcards` (
  `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcard_number` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `value` double(15,2) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`giftcard_id`),
  UNIQUE KEY `giftcard_number` (`giftcard_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `_DBPREFIX_giftcards`
--

INSERT INTO `_DBPREFIX_giftcards` (`giftcard_id`, `giftcard_number`, `value`, `deleted`) VALUES
(1, '12345', 100000000.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_inventory`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) NOT NULL DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text NOT NULL,
  `trans_inventory` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`),
  KEY `ospos_inventory_ibfk_1` (`trans_items`),
  KEY `ospos_inventory_ibfk_2` (`trans_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=297 ;



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_items`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_items` (
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
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  KEY `ospos_items_ibfk_1` (`supplier_id`),
  KEY `_DBPREFIX_items_categories_fk` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_items_categories`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_items_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `cname` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `_DBPREFIX_items_categories`
--


-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_items_taxes`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_items_taxes` (
  `item_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`item_id`,`name`,`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_item_kits`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_item_kit_items`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` double(15,2) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `ospos_item_kit_items_ibfk_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_modules`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_modules` (
  `name_lang_key` varchar(255) NOT NULL,
  `desc_lang_key` varchar(255) NOT NULL,
  `sort` int(10) NOT NULL,
  `module_id` varchar(255) NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_DBPREFIX_modules`
--

INSERT INTO `_DBPREFIX_modules` (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES
('module_cash', 'module_cash_desc', 90, 'cash'),
('module_config', 'module_config_desc', 100, 'config'),
('module_backup', 'module_backup_desc', 110, 'config/backup'),
('module_customers', 'module_customers_desc', 10, 'customers'),
('module_employees', 'module_employees_desc', 80, 'employees'),
('module_giftcards', 'module_giftcards_desc', 90, 'giftcards'),
('module_home', 'module_home_desc', 9, 'home'),
('module_items', 'module_items_desc', 20, 'items'),
('module_item_kits', 'module_item_kits_desc', 30, 'item_kits'),
('module_receivings', 'module_receivings_desc', 60, 'receivings'),
('module_reports', 'module_reports_desc', 50, 'reports'),
('module_sales', 'module_sales_desc', 70, 'sales'),
('module_suppliers', 'module_suppliers_desc', 40, 'suppliers');

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_people`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_people` (
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
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `_DBPREFIX_people`
--

INSERT INTO `_DBPREFIX_people` (`first_name`, `last_name`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`, `person_id`) VALUES
('Kamaro', 'Lambert', '555-555-5555', 'admin@huguka.com', 'Address 1', '', '', '', '', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_permissions`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_permissions` (
  `module_id` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `_DBPREFIX_permissions`
--

INSERT INTO `_DBPREFIX_permissions` (`module_id`, `person_id`) VALUES
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

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_predefined`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_predefined` (
  `pid` int(3) NOT NULL AUTO_INCREMENT,
  `ptfrom` int(3) NOT NULL,
  `ptto` int(3) NOT NULL,
  `ptdesc` varchar(100) NOT NULL,
  `ptamount` float(9,2) NOT NULL,
  `ptmemo` text NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_receivings`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`receiving_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_receivings_items`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_receivings_items` (
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

--
-- Dumping data for table `_DBPREFIX_receivings_items`
--


--
-- Table structure for table `_DBPREFIX_receivings_payments`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_receivings_payments` (
  `receiving_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  `date_paid` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_note` varchar(255) DEFAULT 'No Note provided',
  `credit` tinyint(1) NOT NULL DEFAULT '0',
  `done_by` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_sales`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_sales` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_sales_items`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_sales_items` (
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

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_sales_items_taxes`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_sales_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_sales_payments`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_sales_payments` (
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



-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_sales_suspended`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_sales_suspended` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_sales_suspended_items`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_sales_suspended_items` (
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

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_sales_suspended_items_taxes`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_sales_suspended_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_sales_suspended_payments`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_sales_suspended_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_sessions`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_suppliers`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_suppliers` (
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `tin` varchar(50) NOT NULL,
  `acc_id` int(11) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_transaction`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_transaction` (
  `tid` bigint(20) NOT NULL AUTO_INCREMENT,
  `tdesc` varchar(100) NOT NULL,
  `tamount` float(9,2) NOT NULL,
  `tdate` date NOT NULL,
  `trelation` int(9) NOT NULL,
  `tmemo` text NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=143 ;

-- --------------------------------------------------------

--
-- Table structure for table `_DBPREFIX_tran_relation`
--

CREATE TABLE IF NOT EXISTS `_DBPREFIX_tran_relation` (
  `tr_id` int(7) NOT NULL AUTO_INCREMENT,
  `tr_cid` int(7) NOT NULL,
  `tr_tranid` int(7) NOT NULL,
  `tr_couple` int(7) NOT NULL,
  `tr_amount` float(9,2) NOT NULL,
  `tr_index` int(7) NOT NULL,
  `tr_acbalance` float(9,2) NOT NULL,
  PRIMARY KEY (`tr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=281 ;



--
-- Constraints for dumped tables
--

--
-- Constraints for table `_DBPREFIX_customers`
--
ALTER TABLE `_DBPREFIX_customers`
  ADD CONSTRAINT `_DBPREFIX_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `_DBPREFIX_people` (`person_id`);

--
-- Constraints for table `_DBPREFIX_employees`
--
ALTER TABLE `_DBPREFIX_employees`
  ADD CONSTRAINT `_DBPREFIX_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `_DBPREFIX_people` (`person_id`);

--
-- Constraints for table `_DBPREFIX_inventory`
--
ALTER TABLE `_DBPREFIX_inventory`
  ADD CONSTRAINT `_DBPREFIX_inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `_DBPREFIX_items` (`item_id`),
  ADD CONSTRAINT `_DBPREFIX_inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `_DBPREFIX_employees` (`person_id`);

--
-- Constraints for table `_DBPREFIX_items`
--
ALTER TABLE `_DBPREFIX_items`
  ADD CONSTRAINT `_DBPREFIX_items_categories_fk` FOREIGN KEY (`category_id`) REFERENCES `_DBPREFIX_items_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `_DBPREFIX_items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `_DBPREFIX_suppliers` (`person_id`);

--
-- Constraints for table `_DBPREFIX_items_taxes`
--
ALTER TABLE `_DBPREFIX_items_taxes`
  ADD CONSTRAINT `_DBPREFIX_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `_DBPREFIX_items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `_DBPREFIX_item_kit_items`
--
ALTER TABLE `_DBPREFIX_item_kit_items`
  ADD CONSTRAINT `_DBPREFIX_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `_DBPREFIX_item_kits` (`item_kit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `_DBPREFIX_item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `_DBPREFIX_items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `_DBPREFIX_permissions`
--
ALTER TABLE `_DBPREFIX_permissions`
  ADD CONSTRAINT `_DBPREFIX_permissions_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `_DBPREFIX_employees` (`person_id`),
  ADD CONSTRAINT `_DBPREFIX_permissions_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `_DBPREFIX_modules` (`module_id`);

--
-- Constraints for table `_DBPREFIX_receivings`
--
ALTER TABLE `_DBPREFIX_receivings`
  ADD CONSTRAINT `_DBPREFIX_receivings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `_DBPREFIX_employees` (`person_id`),
  ADD CONSTRAINT `_DBPREFIX_receivings_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `_DBPREFIX_suppliers` (`person_id`);

--
-- Constraints for table `_DBPREFIX_receivings_items`
--
ALTER TABLE `_DBPREFIX_receivings_items`
  ADD CONSTRAINT `_DBPREFIX_receivings_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `_DBPREFIX_items` (`item_id`),
  ADD CONSTRAINT `_DBPREFIX_receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `_DBPREFIX_receivings` (`receiving_id`);

--
-- Constraints for table `_DBPREFIX_receivings_payments`
--
ALTER TABLE `_DBPREFIX_receivings_payments`
  ADD CONSTRAINT `_DBPREFIX_receivings_payments_ibfk_1` FOREIGN KEY (`receiving_id`) REFERENCES `_DBPREFIX_receivings` (`receiving_id`);

--
-- Constraints for table `_DBPREFIX_sales`
--
ALTER TABLE `_DBPREFIX_sales`
  ADD CONSTRAINT `_DBPREFIX_sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `_DBPREFIX_employees` (`person_id`),
  ADD CONSTRAINT `_DBPREFIX_sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `_DBPREFIX_customers` (`person_id`);

--
-- Constraints for table `_DBPREFIX_sales_items`
--
ALTER TABLE `_DBPREFIX_sales_items`
  ADD CONSTRAINT `_DBPREFIX_sales_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `_DBPREFIX_items` (`item_id`),
  ADD CONSTRAINT `_DBPREFIX_sales_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `_DBPREFIX_sales` (`sale_id`);

--
-- Constraints for table `_DBPREFIX_sales_items_taxes`
--
ALTER TABLE `_DBPREFIX_sales_items_taxes`
  ADD CONSTRAINT `_DBPREFIX_sales_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `_DBPREFIX_sales_items` (`sale_id`),
  ADD CONSTRAINT `_DBPREFIX_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `_DBPREFIX_items` (`item_id`);

--
-- Constraints for table `_DBPREFIX_sales_payments`
--
ALTER TABLE `_DBPREFIX_sales_payments`
  ADD CONSTRAINT `_DBPREFIX_sales_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `_DBPREFIX_sales` (`sale_id`);

--
-- Constraints for table `_DBPREFIX_sales_suspended`
--
ALTER TABLE `_DBPREFIX_sales_suspended`
  ADD CONSTRAINT `_DBPREFIX_sales_suspended_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `_DBPREFIX_employees` (`person_id`),
  ADD CONSTRAINT `_DBPREFIX_sales_suspended_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `_DBPREFIX_customers` (`person_id`);

--
-- Constraints for table `_DBPREFIX_sales_suspended_items`
--
ALTER TABLE `_DBPREFIX_sales_suspended_items`
  ADD CONSTRAINT `_DBPREFIX_sales_suspended_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `_DBPREFIX_items` (`item_id`),
  ADD CONSTRAINT `_DBPREFIX_sales_suspended_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `_DBPREFIX_sales_suspended` (`sale_id`);

--
-- Constraints for table `_DBPREFIX_sales_suspended_items_taxes`
--
ALTER TABLE `_DBPREFIX_sales_suspended_items_taxes`
  ADD CONSTRAINT `_DBPREFIX_sales_suspended_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `_DBPREFIX_sales_suspended_items` (`sale_id`),
  ADD CONSTRAINT `_DBPREFIX_sales_suspended_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `_DBPREFIX_items` (`item_id`);

--
-- Constraints for table `_DBPREFIX_sales_suspended_payments`
--
ALTER TABLE `_DBPREFIX_sales_suspended_payments`
  ADD CONSTRAINT `_DBPREFIX_sales_suspended_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `_DBPREFIX_sales_suspended` (`sale_id`);

--
-- Constraints for table `_DBPREFIX_suppliers`
--
ALTER TABLE `_DBPREFIX_suppliers`
  ADD CONSTRAINT `_DBPREFIX_suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `_DBPREFIX_people` (`person_id`);
