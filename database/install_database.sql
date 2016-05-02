-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 08, 2014 at 02:54 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test_kpos`
--

-- --------------------------------------------------------

--
-- Table structure for table `kpos_account_type`
--

CREATE TABLE IF NOT EXISTS `kpos_account_type` (
  `acc_id` tinyint(10) NOT NULL AUTO_INCREMENT,
  `acc_name` varchar(20) NOT NULL,
  `acc_credit` tinyint(1) NOT NULL DEFAULT '0',
  `acc_description` varchar(200) NOT NULL,
  PRIMARY KEY (`acc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_app_config`
--

CREATE TABLE IF NOT EXISTS `kpos_app_config` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpos_app_config`
--

INSERT INTO `kpos_app_config` (`key`, `value`) VALUES
('address', 'Gokondo,\nKigali, Rwanda'),
('automatic_email_on_recur', '0'),
('company', 'kpos'),
('company_logo', 'logo.jpg'),
('cron_key', '8g1v9RXuiMYnVo5u'),
('currency_symbol', 'RWF'),
('decimal_number', '0'),
('decimal_point', '.'),
('decimal_symbol', '.'),
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
('email', 'kamaroly@gmail.com'),
('email_send_method', 'phpmail'),
('enable_multi_currency', '0'),
('exchange_rate', '670'),
('exchange_rate_name', 'USD'),
('expiration_days', '30'),
('fax', '0'),
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
('phone', '250722127123'),
('print_after_sale', 'print_after_sale'),
('print_confirm', '0'),
('print_receipt_size', 'receipt'),
('return_policy', 'kamaro return police'),
('smtp_authentication', '0'),
('smtp_port', ''),
('smtp_security', ''),
('smtp_server_address', ''),
('smtp_username', ''),
('tax_rate_decimal_places', '2'),
('thousands_separator', ','),
('timezone', 'Africa/Cairo'),
('tin', '100123823'),
('website', 'www.huguksa');

-- --------------------------------------------------------

--
-- Table structure for table `kpos_bookings`
--

CREATE TABLE IF NOT EXISTS `kpos_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `num_child` int(11) DEFAULT NULL,
  `num_adult` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_cash_transaction`
--

CREATE TABLE IF NOT EXISTS `kpos_cash_transaction` (
  `Transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Amount` double NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT '1',
  `from_account_id` int(11) NOT NULL,
  `to_account_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `personal_id` int(11) NOT NULL,
  PRIMARY KEY (`Transaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=91 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_category`
--

CREATE TABLE IF NOT EXISTS `kpos_category` (
  `cid` bigint(10) NOT NULL AUTO_INCREMENT,
  `cname` varchar(100) NOT NULL,
  `camount` float(9,2) NOT NULL DEFAULT '0.00',
  `cbalance` float(9,2) NOT NULL DEFAULT '0.00',
  `cincome` tinyint(1) NOT NULL,
  `ctype` varchar(1) NOT NULL,
  `cparent` varchar(1) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_category_rel`
--

CREATE TABLE IF NOT EXISTS `kpos_category_rel` (
  `child_id` bigint(10) NOT NULL,
  `parent_id` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`child_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_ci_sessions`
--

CREATE TABLE IF NOT EXISTS `kpos_ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpos_ci_sessions`
--

INSERT INTO `kpos_ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('3c540d08f2816ef5b5a50a7dfa44744d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (K', 1391871155, 'a:2:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"1";}');

-- --------------------------------------------------------

--
-- Table structure for table `kpos_clientcontacts`
--

CREATE TABLE IF NOT EXISTS `kpos_clientcontacts` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_currencies`
--

CREATE TABLE IF NOT EXISTS `kpos_currencies` (
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

--
-- Dumping data for table `kpos_currencies`
--

INSERT INTO `kpos_currencies` (`curr_id`, `Name`, `Exchange_Rate`, `Symbol`, `Symbol_Suffix`, `Thousand_Separator`, `Decimal_Separator`, `Status`, `Default`, `Date`) VALUES
(1, 'AUD', 2.0000, 'AU $', 1, ',', '.', 1, 0, '2013-11-23 08:30:39'),
(2, 'EUR', 1.1500, '?', 1, ',', '.', 0, 0, '2013-11-23 12:13:35'),
(3, 'GBP', 100.0000, '?', 0, ',', '.', 1, 0, '2013-11-23 09:24:50'),
(4, 'USD', 1.6000, 'US $', 0, ',', '.', 1, 0, '2014-01-27 21:03:00'),
(5, 'RWF', 1.0000, 'Rwf', 0, ',', '.', 1, 1, '2014-01-28 21:24:39'),
(6, 'New Currency', 600.0000, '', 0, '', '', 0, 0, '2013-12-05 14:26:26'),
(7, 'New Currency', 600.0000, '', 0, '', '', 0, 0, '2014-01-28 21:24:36');

-- --------------------------------------------------------

--
-- Table structure for table `kpos_currency_sales`
--

CREATE TABLE IF NOT EXISTS `kpos_currency_sales` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `currency_id` smallint(5) NOT NULL DEFAULT '0',
  `total_sold` double NOT NULL,
  `currency_rate` decimal(10,0) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `subtotal_sold` double NOT NULL,
  PRIMARY KEY (`sale_id`,`currency_id`),
  KEY `currency_id` (`currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_customers`
--

CREATE TABLE IF NOT EXISTS `kpos_customers` (
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_employees`
--

CREATE TABLE IF NOT EXISTS `kpos_employees` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpos_employees`
--

INSERT INTO `kpos_employees` (`username`, `password`, `language`, `person_id`, `deleted`) VALUES
('admin', '439a6de57d475c1a0ba9bcb1c39f0af6', 'english', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `kpos_features`
--

CREATE TABLE IF NOT EXISTS `kpos_features` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_giftcards`
--

CREATE TABLE IF NOT EXISTS `kpos_giftcards` (
  `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcard_number` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `value` double(15,2) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`giftcard_id`),
  UNIQUE KEY `giftcard_number` (`giftcard_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_inventory`
--

CREATE TABLE IF NOT EXISTS `kpos_inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) NOT NULL DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text NOT NULL,
  `trans_inventory` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`),
  KEY `ospos_inventory_ibfk_1` (`trans_items`),
  KEY `ospos_inventory_ibfk_2` (`trans_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2022 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_invoices_recurring`
--

CREATE TABLE IF NOT EXISTS `kpos_invoices_recurring` (
  `invoice_recurring_id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `recur_start_date` date NOT NULL,
  `recur_end_date` date NOT NULL,
  `recur_frequency` char(2) NOT NULL,
  `recur_next_date` date NOT NULL,
  PRIMARY KEY (`invoice_recurring_id`),
  KEY `sale_id` (`sale_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_invoice_histories`
--

CREATE TABLE IF NOT EXISTS `kpos_invoice_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL,
  `clientcontacts_id` varchar(255) NOT NULL,
  `date_sent` date NOT NULL,
  `contact_type` int(1) NOT NULL,
  `email_body` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_items`
--

CREATE TABLE IF NOT EXISTS `kpos_items` (
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
  KEY `kpos_items_categories_fk` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=865 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_items_categories`
--

CREATE TABLE IF NOT EXISTS `kpos_items_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `cname` varchar(50) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_items_taxes`
--

CREATE TABLE IF NOT EXISTS `kpos_items_taxes` (
  `item_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`item_id`,`name`,`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_item_kits`
--

CREATE TABLE IF NOT EXISTS `kpos_item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`item_kit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_item_kit_items`
--

CREATE TABLE IF NOT EXISTS `kpos_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` double(15,2) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `ospos_item_kit_items_ibfk_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_migrations`
--

CREATE TABLE IF NOT EXISTS `kpos_migrations` (
  `version` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_modules`
--

CREATE TABLE IF NOT EXISTS `kpos_modules` (
  `name_lang_key` varchar(255) NOT NULL,
  `desc_lang_key` varchar(255) NOT NULL,
  `sort` int(10) NOT NULL,
  `module_id` varchar(255) NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpos_modules`
--

INSERT INTO `kpos_modules` (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES
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

-- --------------------------------------------------------

--
-- Table structure for table `kpos_people`
--

CREATE TABLE IF NOT EXISTS `kpos_people` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `kpos_people`
--

INSERT INTO `kpos_people` (`first_name`, `last_name`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`, `person_id`, `tin`) VALUES
('Admin First Name', 'Admin Last Name', '555-555-5555', 'admin@huguka.com', 'Address 1', '', '', '', '', '', '', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `kpos_permissions`
--

CREATE TABLE IF NOT EXISTS `kpos_permissions` (
  `module_id` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kpos_permissions`
--

INSERT INTO `kpos_permissions` (`module_id`, `person_id`) VALUES
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
-- Table structure for table `kpos_predefined`
--

CREATE TABLE IF NOT EXISTS `kpos_predefined` (
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
-- Table structure for table `kpos_receivings`
--

CREATE TABLE IF NOT EXISTS `kpos_receivings` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_receivings_items`
--

CREATE TABLE IF NOT EXISTS `kpos_receivings_items` (
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

-- --------------------------------------------------------

--
-- Table structure for table `kpos_receivings_payments`
--

CREATE TABLE IF NOT EXISTS `kpos_receivings_payments` (
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
-- Table structure for table `kpos_receiving_items_taxes`
--

CREATE TABLE IF NOT EXISTS `kpos_receiving_items_taxes` (
  `receiving_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` double(15,3) NOT NULL,
  PRIMARY KEY (`receiving_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_sales`
--

CREATE TABLE IF NOT EXISTS `kpos_sales` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
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
  PRIMARY KEY (`sale_id`),
  UNIQUE KEY `invoice_url_key` (`invoice_url_key`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  KEY `invoice_group_id` (`invoice_group_id`,`invoice_date_due`,`invoice_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=241 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_sales_items`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_items` (
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
-- Table structure for table `kpos_sales_items_taxes`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_items_taxes` (
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
-- Table structure for table `kpos_sales_payments`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_payments` (
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
-- Table structure for table `kpos_sales_quotations`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_quotations` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_sales_quotations_items`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_quotations_items` (
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
-- Table structure for table `kpos_sales_quotations_items_taxes`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_quotations_items_taxes` (
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
-- Table structure for table `kpos_sales_quotations_payments`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_quotations_payments` (
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
-- Table structure for table `kpos_sales_suspended`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_suspended` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_sales_suspended_items`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_suspended_items` (
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
-- Table structure for table `kpos_sales_suspended_items_taxes`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_suspended_items_taxes` (
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
-- Table structure for table `kpos_sales_suspended_payments`
--

CREATE TABLE IF NOT EXISTS `kpos_sales_suspended_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_sessions`
--

CREATE TABLE IF NOT EXISTS `kpos_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_suppliers`
--

CREATE TABLE IF NOT EXISTS `kpos_suppliers` (
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
-- Table structure for table `kpos_transaction`
--

CREATE TABLE IF NOT EXISTS `kpos_transaction` (
  `tid` bigint(20) NOT NULL AUTO_INCREMENT,
  `tdesc` varchar(100) NOT NULL,
  `tamount` float(9,2) NOT NULL,
  `tdate` date NOT NULL,
  `trelation` int(9) NOT NULL,
  `tmemo` text NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=534 ;

-- --------------------------------------------------------

--
-- Table structure for table `kpos_tran_relation`
--

CREATE TABLE IF NOT EXISTS `kpos_tran_relation` (
  `tr_id` int(7) NOT NULL AUTO_INCREMENT,
  `tr_cid` int(7) NOT NULL,
  `tr_tranid` int(7) NOT NULL,
  `tr_couple` int(7) NOT NULL,
  `tr_amount` float(9,2) NOT NULL,
  `tr_index` int(7) NOT NULL,
  `tr_acbalance` float(9,2) NOT NULL,
  PRIMARY KEY (`tr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=347 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kpos_currency_sales`
--
ALTER TABLE `kpos_currency_sales`
  ADD CONSTRAINT `kpos_currency_sales_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales` (`sale_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kpos_currency_sales_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `kpos_currencies` (`curr_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kpos_customers`
--
ALTER TABLE `kpos_customers`
  ADD CONSTRAINT `kpos_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kpos_people` (`person_id`);

--
-- Constraints for table `kpos_employees`
--
ALTER TABLE `kpos_employees`
  ADD CONSTRAINT `kpos_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kpos_people` (`person_id`);

--
-- Constraints for table `kpos_inventory`
--
ALTER TABLE `kpos_inventory`
  ADD CONSTRAINT `kpos_inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `kpos_items` (`item_id`),
  ADD CONSTRAINT `kpos_inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `kpos_employees` (`person_id`);

--
-- Constraints for table `kpos_items`
--
ALTER TABLE `kpos_items`
  ADD CONSTRAINT `kpos_items_categories_fk` FOREIGN KEY (`category_id`) REFERENCES `kpos_items_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kpos_items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `kpos_suppliers` (`person_id`);

--
-- Constraints for table `kpos_items_taxes`
--
ALTER TABLE `kpos_items_taxes`
  ADD CONSTRAINT `kpos_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `kpos_item_kit_items`
--
ALTER TABLE `kpos_item_kit_items`
  ADD CONSTRAINT `kpos_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `kpos_item_kits` (`item_kit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kpos_item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `kpos_permissions`
--
ALTER TABLE `kpos_permissions`
  ADD CONSTRAINT `kpos_permissions_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kpos_employees` (`person_id`),
  ADD CONSTRAINT `kpos_permissions_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `kpos_modules` (`module_id`);

--
-- Constraints for table `kpos_receivings`
--
ALTER TABLE `kpos_receivings`
  ADD CONSTRAINT `kpos_receivings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `kpos_employees` (`person_id`),
  ADD CONSTRAINT `kpos_receivings_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `kpos_suppliers` (`person_id`);

--
-- Constraints for table `kpos_receivings_items`
--
ALTER TABLE `kpos_receivings_items`
  ADD CONSTRAINT `kpos_receivings_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`),
  ADD CONSTRAINT `kpos_receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `kpos_receivings` (`receiving_id`);

--
-- Constraints for table `kpos_receivings_payments`
--
ALTER TABLE `kpos_receivings_payments`
  ADD CONSTRAINT `kpos_receivings_payments_ibfk_1` FOREIGN KEY (`receiving_id`) REFERENCES `kpos_receivings` (`receiving_id`);

--
-- Constraints for table `kpos_sales`
--
ALTER TABLE `kpos_sales`
  ADD CONSTRAINT `kpos_sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `kpos_employees` (`person_id`),
  ADD CONSTRAINT `kpos_sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `kpos_customers` (`person_id`);

--
-- Constraints for table `kpos_sales_items`
--
ALTER TABLE `kpos_sales_items`
  ADD CONSTRAINT `kpos_sales_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`),
  ADD CONSTRAINT `kpos_sales_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales` (`sale_id`);

--
-- Constraints for table `kpos_sales_items_taxes`
--
ALTER TABLE `kpos_sales_items_taxes`
  ADD CONSTRAINT `kpos_sales_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales_items` (`sale_id`),
  ADD CONSTRAINT `kpos_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`);

--
-- Constraints for table `kpos_sales_payments`
--
ALTER TABLE `kpos_sales_payments`
  ADD CONSTRAINT `kpos_sales_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales` (`sale_id`);

--
-- Constraints for table `kpos_sales_suspended`
--
ALTER TABLE `kpos_sales_suspended`
  ADD CONSTRAINT `kpos_sales_suspended_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `kpos_employees` (`person_id`),
  ADD CONSTRAINT `kpos_sales_suspended_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `kpos_customers` (`person_id`);

--
-- Constraints for table `kpos_sales_suspended_items`
--
ALTER TABLE `kpos_sales_suspended_items`
  ADD CONSTRAINT `kpos_sales_suspended_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`),
  ADD CONSTRAINT `kpos_sales_suspended_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales_suspended` (`sale_id`);

--
-- Constraints for table `kpos_sales_suspended_items_taxes`
--
ALTER TABLE `kpos_sales_suspended_items_taxes`
  ADD CONSTRAINT `kpos_sales_suspended_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales_suspended_items` (`sale_id`),
  ADD CONSTRAINT `kpos_sales_suspended_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`);

--
-- Constraints for table `kpos_sales_suspended_payments`
--
ALTER TABLE `kpos_sales_suspended_payments`
  ADD CONSTRAINT `kpos_sales_suspended_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales_suspended` (`sale_id`);

--
-- Constraints for table `kpos_suppliers`
--
ALTER TABLE `kpos_suppliers`
  ADD CONSTRAINT `kpos_suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kpos_people` (`person_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
