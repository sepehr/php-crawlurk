<?php defined('PATH') OR exit('No direct script access allowed');

// Include the PhoneExtractorCrawler
require_once PATH . 'mods/PhoneExtractorCrawler.php';

/**
 * Crawler adapter class for mashinbazar.com
 *
 * Crawl all ad links in all listing pages: (about 3000 items)
 * $ php /path/to/crawler --adapter=mashinbazar [--all]
 *
 * Crawl newly added links: (40 hits by default)
 * $ php /path/to/crawler --adapter=mashinbazar --limit=40
 *
 * @package		Crawlurk
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) Sepehr Lajevardi
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
class MashinbazarCrawler extends PhoneExtractorCrawler {

	/**
	 * URL to crawl.
	 *
	 * @var string
	 */
	public $_url = 'http://www.mashinbazar.com/search.php?limit=20';

	/**
	 * CSS selector.
	 *
	 * @var string
	 */
	protected $_selector = '.search-result-base .search-result-row .text-base .text';

	/**
	 * URI follow regex rule.
	 *
	 * @var string
	 */
	protected $_follow = '/search.php\?page=[0-9]+&limit=[0-9]+/';

	// ------------------------------------------------------------------------
}
// End of MashinbazarCrawler class
