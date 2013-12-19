<?php defined('PATH') OR exit('No direct script access allowed');

// Include the PhoneExtractorCrawler
require_once PATH . 'mods/PhoneExtractorCrawler.php';

/**
 * Crawler adapter class for 1car.ir
 *
 * Crawl all ad links in all listing pages: (no hit limit will crawl all pages)
 * $ php /path/to/crawler --adapter=onecar [--all]
 *
 * Crawl newly added links: (20 hits by default)
 * $ php /path/to/crawler --adapter=onecar --limit=20
 *
 * @package		Crawlurk
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) Sepehr Lajevardi
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

	/**
	 * Regex(es) to match against request referer.
	 *
	 * This will let us manually maintain the crawl DEPTH,
	 * since it's not currently supported by the underlying
	 * PHPCrawler.
	 *
	 * Set to FALSE in order to disable the referer check.
	 *
	 * @var string
	 */
	protected $_referer = '/(http:\/\/(www\.)?1car.ir)?\/a(\?smode=0&sort=adddate&sort_ad=desc&page=[0-9]+)?$/miu';

	// ------------------------------------------------------------------------
}
// End of OnecarCrawler class
