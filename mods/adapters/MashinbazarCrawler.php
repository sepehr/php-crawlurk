<?php defined('PATH') OR exit('No direct script access allowed');

// Include the PhoneExtractorCrawler
require_once PATH . 'mods/PhoneExtractorCrawler.php';

/**
 * Crawler adapter class for mashinbazar.com
 *
 * Crawl all ad links in all listing pages:
 * $ php /path/to/crawler --adapter=mashinbazar --all
 *
 * Crawl newly added links:
 * $ php /path/to/crawler --adapter=mashinbazar --limit=1
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
	public $_url = 'http://www.mashinbazar.com/search.php?limit=100';

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

	/**
	 * Processes crawled page content.
	 *
	 * Overriding parent method to apply custom extraction logic.
	 * This implementation avoids the DOM parsing extra load by
	 * applying the regex against the whole fetched content.
	 *
	 * @return mixed
	 */
	public function process()
	{
		global $cli;

		// Print out crawl stats for this step
		$this->_crawl_step_stats();

		// Parse the fetched content
		if ( ! empty($this->_content))
		{
			$count = 0;

			// Extract desired data from element's text
			if ($extracted = $this->_extract_regex($this->_content, $this->_regex))
			{
				// Remove dups
				$extracted   = array_unique(array_filter($extracted));
				// Increase count
				$count      += count($extracted);
				// And merge up in the pool
				$this->_pool = array_merge($this->_pool, $extracted);
			}

			// How many items have been extracted?
			$cli->tab()->writeln("$count item(s) extracted.")->eol();
			return TRUE;
		}

		$cli->err('DOM parser failed.')->eol();
		return FALSE;
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
		// Load all records in one page
		$this->_url = 'http://www.mashinbazar.com/search.php?limit=3500';
		// Set limit to 1
		$this->setPageLimit(1);
	}

	// ------------------------------------------------------------------------
}
// End of MashinbazarCrawler class
