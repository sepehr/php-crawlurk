<?php defined('PATH') OR exit('No direct script access allowed');

// Include the PhoneExtractorCrawler
require_once PATH . 'mods/PhoneExtractorCrawler.php';

/**
 * Crawler adapter class for hamshahree.com
 *
 * @package		TakhtegazCrawler
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) 2013 www.takhtegaz.com
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
class HamshahreeCrawler extends PhoneExtractorCrawler {

	/**
	 * URL to crawl.
	 *
	 * @var string
	 */
	public $_url = 'http://hamshahree.com/index.php?MGID=18';

	/**
	 * CSS selector.
	 *
	 * @var string
	 */
	protected $_selector = 'table.TX2:nth-child(1) tr:nth-child(3) table tr:nth-child(5) table td';

	/**
	 * URI follow regex rule.
	 *
	 * @var string
	 */
	protected $_follow = array(
		// Listings:
		'/http:\/\/(www\.)?hamshahree\.com\/index.php\?MGID=18&cPAG=[0-9]+/miu',
		// Ad items:
		'/http:\/\/(www\.)?hamshahree\.com\/MGID_18\/SGID_[0-9]+\/.*/miu',
	);

	// ------------------------------------------------------------------------
}
// End of HamshahreeCrawler class
