<?php defined('PATH') OR exit('No direct script access allowed');

// Include the PhoneExtractorCrawler
require_once PATH . 'mods/PhoneExtractorCrawler.php';

/**
 * Crawler adapter class for cartel.ir
 *
 * * Crawl all ad links in all listing pages: (about 200 pages)
 * $ php /path/to/crawler --adapter=cartel --all --limit=0
 *
 * Crawl newly added links: (10 pages by default)
 * $ php /path/to/crawler --adapter=cartel --limit=0
 *
 * @package		TakhtegazCrawler
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) 2013 www.takhtegaz.com
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
class CartelCrawler extends PhoneExtractorCrawler {

	/**
	 * URLs to crawl.
	 *
	 * Setting multiple starting URLs because the website pager
	 * works via AJAX and the Crawler engine could not figureout
	 * next page links.
	 *
	 * The callee must instantiate this crawler per each URL.
	 *
	 * @var array
	 */
	public $_url = array(
		// 'http://www.cartel.ir/ajax.php?search=0&page=1',
		// 'http://www.cartel.ir/ajax.php?search=0&page=2',
		// ...
		//
		// Will be populated by the constructor method
	);

	/**
	 * CSS selector.
	 *
	 * @var string
	 */
	protected $_selector = 'table.pretty-table tr:nth-child(8) td:nth-child(2)';

	/**
	 * URI follow regex rule.
	 *
	 * @var string
	 */
	protected $_follow = '/http:\/\/(www\.)?cartel.ir\/id-[0-9]+-.*\/$/miu';

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
	protected $_referer = '/(http:\/\/(www\.)?cartel.ir)?\/ajax.php\?search=0&page=[1-9]+/miu';

	// ------------------------------------------------------------------------

	/**
	 * Configures the crawler to crawl new pages.
	 *
	 * @param  int $limit Crawler hit limit.
	 *
	 * @return void
	 */
	protected function _setup_new($limit = FALSE)
	{
		$this->_populate_urls(5);
	}

	// ------------------------------------------------------------------------

	/**
	 * Configures the crawler to crawl all possible pages.
	 *
	 * @param  int $limit Crawler hit limit.
	 *
	 * @return void
	 */
	protected function _setup_all($limit = FALSE)
	{
		$this->_populate_urls(89);
	}

	// ------------------------------------------------------------------------
	// Internal Helpers
	// ------------------------------------------------------------------------

	/**
	 * Dynamically populates $_url array for multi-dispatch operations.
	 *
	 * @param  int $limit Crawler hit limit.
	 *
	 * @return void
	 */
	private function _populate_urls($max)
	{
		global $cli;

		// Maximum pages (starting) to hit:
		$cli->info("It's a multi-dispatch operation. Targeting $max starting pages...");

		for ($i = 1; $i <= $max; $i++)
		{
			$this->_url[] = "http://www.cartel.ir/ajax.php?search=0&page=$i";
		}
	}

	// ------------------------------------------------------------------------
}
// End of CartelCrawler class
