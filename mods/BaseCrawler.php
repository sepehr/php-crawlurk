<?php defined('PATH') OR exit('No direct script access allowed');

/**
 * Base Crawler class for Takhtegaz.
 *
 * @package		TakhtegazCrawler
 * @category	Core
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) 2013 www.takhtegaz.com
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
abstract class BaseCrawler extends PHPCrawler implements BaseCrawlerInterface {

	/**
	 * URL to crawl.
	 *
	 * @var string
	 */
	public $_url = NULL;

	/**
	 * Port.
	 *
	 * @var int
	 */
	public $_port = 80;

	/**
	 * Crawled page object.
	 *
	 * @var object
	 */
	protected $_page = FALSE;

	/**
	 * Crawled page content.
	 *
	 * @var string
	 */
	protected $_content = FALSE;

	/**
	 * Array of extracted data.
	 *
	 * @var array
	 */
	protected $_pool = array();

	/**
	 * Array of URI follow regex rules.
	 *
	 * @var array
	 */
	protected $_follow = array();

	// ------------------------------------------------------------------------

	/**
	 * Configures and initiates a new crawler.
	 */
	public function __construct($limit = FALSE, $all = FALSE)
	{
		if ( ! isset($this->_url, $this->_port))
		{
			trigger_error('Invalid adapter implementation.', E_USER_ERROR);
			exit;
		}

		// Let the parent initializes
		parent::__construct();

		// If it's set to crawl all pages, call a custom configurator
		// method. So that derived adapters can implement their own
		// magic.
		$all AND $this->_setup_all();

		// Configure the crawler
		$this->_configure();

		// Set crawler page limit, if passed
		$limit AND $this->setPageLimit($limit);
	}

	// ------------------------------------------------------------------------

	/**
	 * Handles request header.
	 *
	 * Will be called after the header of a document was received
	 * and BEFORE the content will be received.
	 *
	 * The document won't be received if you let this method return
	 * any negative value.
	 *
	 * @param  PHPCrawlerResponseHeader $header Request header object.
	 *
	 * @return mixed
	 */
	public function handleHeaderInfo(PHPCrawlerResponseHeader $header)
	{
		global $cli;

		if ($header->http_status_code != 200)
		{
			$cli->err('Remote unavailable: ' . $header->http_status_code);
			return -1;
		}
	}

	// ------------------------------------------------------------------------

	/**
	 * Handles crawled page response.
	 *
	 * @param  PHPCrawlerDocumentInfo $page Object info of crawled page.
	 *
	 * @return void
	 */
	public function handleDocumentInfo(PHPCrawlerDocumentInfo $page)
	{
		global $cli;
		$this->_page = $page;

		// Call content processor, if no errors
		if ($page->error_code === 0)
		{
			$this->_content = $page->content;
			$this->process();
		}
		else
		{
			$this->error($page->error_code, $page->error_string);
		}

		//
		// Derived adapter classes may extend this method to implement
		// their own logic.
		//
	}

	// ------------------------------------------------------------------------

	/**
	 * Processes crawled page content.
	 *
	 * @return mixed
	 */
	public function process()
	{
		global $cli;
		$cli->info('Searching for treasures...');

		//
		// Derived adapter classes should extend this method to implement
		// their own logic.
		//
	}

	// ------------------------------------------------------------------------

	/**
	 * Handles crawled page error.
	 *
	 * @param  int    $error_code   Error code.
	 * @param  string $error_string Error string.
	 *
	 * @return void
	 */
	public function error($error_code, $error_string)
	{
		global $cli;
		$cli->err("$error_string [code: $error_code]")->eol();

		//
		// Derived adapter classes should extend this method to implement
		// their own logic.
		//
	}

	// ------------------------------------------------------------------------

	/**
	 * Returns extraction pool.
	 *
	 * @return array
	 */
	function get_pool()
	{
		return (is_array($this->_pool) AND ! empty($this->_pool))
			? array_unique(array_filter($this->_pool))
			: array();
	}

	// ------------------------------------------------------------------------

	/**
	 * Sets extraction pool value.
	 *
	 * @return void
	 */
	function set_pool(array $pool)
	{
		$this->_pool = $pool;
	}

	// ------------------------------------------------------------------------

	/**
	 * Flushes extraction pool with fresh water!
	 *
	 * @return void
	 */
	function flush_pool()
	{
		$this->_pool = array();
	}

	// ------------------------------------------------------------------------
	// Protected Helpers
	// ------------------------------------------------------------------------

	/**
	 * Configures the crawler.
	 *
	 * @return void
	 */
	protected function _configure()
	{
		// Set URL/Port to crawl
		if ( ! is_array($this->_url))
		{
			$this->setURL($this->_url);
			$this->setPort($this->_port);
		}

		// Set desired content type to crawl
		$this->addContentTypeReceiveRule("/text\/html/");
		// Rules are for break!
		$this->obeyRobotsTxt(FALSE);
		$this->obeyNoFollowTags(FALSE);
		// Set crawl mode
		$this->setFollowMode(CRAWLER_FOLLOW_HOST);
		// Set cache type
		$this->setUrlCacheType(CRAWLER_CACHE_MEMORY);
		// Disable cookies
		$this->enableCookieHandling(FALSE);
		// Follow redirects
		$this->setFollowRedirects(TRUE);
		// Set timeouts
		$this->setStreamTimeout(13);
		$this->setConnectionTimeout(20);
		// Set User-Agent
		$this->setUserAgentString('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.14 Safari/537.36');

		// Set white-listing follow rules, if any
		if ( ! empty($this->_follow))
		{
			is_array($this->_follow) OR $this->_follow = array($this->_follow);

			foreach ($this->_follow as $rule)
			{
				$this->addURLFollowRule($rule);
			}
		}

		// Do not crawl assets by default, we do hate them
		$this->addURLFilterRule('/(jpg|jpeg|gif|png|bmp|css|js)$/ i');
	}

	// ------------------------------------------------------------------------

	/**
	 * Extracts desired data from the passed haystack by a regex.
	 *
	 * @param  string $haytack String to extract from.
	 * @param  string $regex   Extraction regex as per required by preg_match_all().
	 *
	 * @return array
	 */
	protected function _extract_regex($haystack, $regex)
	{
		$haystack = $this->_pre_extract($haystack);

		if (preg_match_all($regex, $haystack, $match))
		{
			$match = is_array($match[0]) ? $match[0] : array($match[0]);
			return $this->_post_extract($match);
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Prints out crawl stats for this step.
	 *
	 * @return void
	 */
	protected function _crawl_step_stats()
	{
		global $cli;

		// Prep URI's parts for display
		$this->_page->file = urlencode($this->_page->file);
		if (strlen($this->_page->file) > 50)
		{
			$this->_page->file = substr($this->_page->file, 0, 15)	. ' ... ' . substr($this->_page->file, 45, 10) . ' [TRIMMED]';
		}

		$cli->info("Hit: \"{$this->_page->path}{$this->_page->file}{$this->_page->query}\" (HTTP 1.1 / {$this->_page->http_status_code})")
			->tab()
			// Total bytes received:
			->writeln('Bytes received: ' . round($this->_page->bytes_received / 1024, 2). 'KB')
			->tab()
			// Average transfer rate:
			->writeln('Transfer rate: '  . round($this->_page->data_transfer_rate / 1024, 2) . ' KB/s')
			->tab()
			// Total runtime:
			->writeln('Runtime: '        . round($this->_page->data_transfer_time, 2) . 's');
	}

	// ------------------------------------------------------------------------

	/**
	 * Pre extraction callback.
	 *
	 * @param  string $haytack String to be manipulated BEFORE extraction.
	 *
	 * @return string
	 */
	protected function _pre_extract($haystack)
	{
		//
		// Derived adapters may override this to do their own magic
		// BEFORE data extraction by regex.
		//

		return $haystack;
	}

	// ------------------------------------------------------------------------

	/**
	 * Post extraction callback.
	 *
	 * @param  array $match Array of matched data AFTER extraction.
	 *
	 * @return array
	 */
	protected function _post_extract(array $match)
	{
		//
		// Derived adapters may override this to do their own magic
		// AFTER data extraction by regex.
		//

		return $match;
	}

	// ------------------------------------------------------------------------

	/**
	 * Configures the crawler to crawl all possible pages.
	 *
	 * @return void
	 */
	protected function _setup_all()
	{
		//
		// Derived adapters may override this to configure the client
		// to crawl all pages.
		//
	}

	// ------------------------------------------------------------------------
}
// End of BaseCrawler class
