<?php defined('PATH') OR exit('No direct script access allowed');

// Include the PhoneExtractorCrawler
require_once PATH . 'mods/PhoneExtractorCrawler.php';

/**
 * Crawler adapter class for bama.ir
 *
 * Crawl all ad links in all listing pages: (about 1,000 pages)
 * $ php /path/to/crawler --adapter=bama --all --limit:0
 *
 * Crawl newly added links: (10 pages by default)
 * $ php /path/to/crawler --adapter=bama --limit=0
 *
 * @package		Crawlurk
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) Sepehr Lajevardi
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
class BamaCrawler extends PhoneExtractorCrawler {

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
		// 'http://www.bama.ir/car/page=1',
		// 'http://www.bama.ir/car/page=2',
		// ...
		//
		// Will be populated by the constructor method
	);

	/**
	 * CSS selector.
	 *
	 * @var string
	 */
	protected $_selector = '#ctl00_cphMain_SelectedAdInfo1_lblCellphoneNumber';

	/**
	 * URI follow regex rule.
	 *
	 * @var string
	 */
	protected $_follow = '/http:\/\/(www\.)?bama.ir\/car\/details-[0-9]+-[0-9]+\/.*/miu';

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
	protected $_referer = '/http:\/\/(www\.)?bama.ir\/car\/page=[0-9]+/miu';

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
		$this->_populate_urls(10);
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
		$this->_populate_urls(967);
	}

	// ------------------------------------------------------------------------

	/**
	 * Post-referer-failure callback.
	 *
	 * Returning -1 will halt the whole operation.
	 *
	 * @return mixed
	 */
	protected function _referer_fail()
	{
		// Skip current dispatch
		return -1;
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
			$this->_url[] = "http://www.bama.ir/car/page=$i";
		}
	}

	// ------------------------------------------------------------------------
}
// End of BamaCrawler class
