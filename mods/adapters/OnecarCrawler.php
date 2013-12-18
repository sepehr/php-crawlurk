<?php defined('PATH') OR exit('No direct script access allowed');

// Include the PhoneExtractorCrawler
require_once PATH . 'mods/PhoneExtractorCrawler.php';

/**
 * Crawler adapter class for 1car.ir
 *
 * @package		TakhtegazCrawler
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) 2013 www.takhtegaz.com
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
class OnecarCrawler extends PhoneExtractorCrawler {

	/**
	 * URL to crawl.
	 *
	 * @var string
	 */
	public $_url = 'http://1car.ir/a';

	/**
	 * CSS selector.
	 *
	 * @var string
	 */
	protected $_selector = '#ContentPlaceHolder1_AdsDetails1_lbl_tel';

	/**
	 * URI follow regex rule.
	 *
	 * @var string
	 */
	protected $_follow = array(
		// Listings:
		'/\?smode=0&sort=adddate&sort_ad=desc&page=[0-9]+/miu',
		// Ad items:
		'/\/as-[0-9]+/miu',
	);

	// ------------------------------------------------------------------------
}
// End of OnecarCrawler class
