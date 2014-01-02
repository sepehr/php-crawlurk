<?php defined('PATH') OR exit('No direct script access allowed');
/**
 * General configs for Crawlurk.
 *
 * @package		Crawlurk
 * @category	Core
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) Sepehr Lajevardi
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 * @filesource
 */

// ------------------------------------------------------------------------
// Configs
// ------------------------------------------------------------------------
$configs = array();

// ------------------------------------------------------------------------
// Constants
// ------------------------------------------------------------------------
define('VERSION', '1.0');
define('IS_CLI',  PHP_SAPI == 'cli');
// Mailer script uses "paste" system command to join
// export files horizontally. It needs file endings
// to be "\n". So we cannot use PHP_EOL here.
define('EOL', IS_CLI ? "\n" : '<br>');

// Cache types
define('CRAWLER_CACHE_MEMORY', 1);
define('CRAWLER_CACHE_SQLITE', 2);

// Crawl modes
// Follow EVERY link, even if the link leads to a different host or domain.
define('CRAWLER_FOLLOW_ALL',     0);
// Follow links that lead to the same domain.
define('CRAWLER_FOLLOW_HOST',    1);
// Follow links that lead to the same host.
define('CRAWLER_FOLLOW_DOMAIN',  2);
// Follow links to pages or files located in or under the same path.
define('CRAWLER_FOLLOW_SUBPATH', 3);
