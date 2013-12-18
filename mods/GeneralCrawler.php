<?php defined('PATH') OR exit('No direct script access allowed');

/**
 * General Crawler Adapter Class.
 *
 * General crawler adapter class to extract data by a CSS
 * selector and an extraction regex rule.
 *
 * @package		TakhtegazCrawler
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) 2013 www.takhtegaz.com
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
abstract class GeneralCrawler extends BaseCrawler {

	/**
	 * CSS selector.
	 *
	 * @var string
	 */
	protected $_selector = '';

	/**
	 * Extraction Regex.
	 *
	 * @var string
	 */
	protected $_regex = '';

	// ------------------------------------------------------------------------
	// Public API
	// ------------------------------------------------------------------------

	/**
	 * Processes crawled page content.
	 *
	 * @return mixed
	 */
	public function process()
	{
		global $cli;

		// Print out crawl stats for this step
		$this->_crawl_step_stats();

		// Parse the fetched content
		if ($dom = str_get_html($this->_content))
		{
			$count    = 0;
			$elements = method_exists($this, '_selector')
				? $this->_selector($dom)
				: $dom->find($this->_selector);

			foreach ($elements as $item)
			{
				// Extract desired data from element's text
				if ($extracted = $this->_extract_regex($item->text(), $this->_regex))
				{
					// Remove dups
					$extracted   = array_unique(array_filter($extracted));
					// Increase count
					$count      += count($extracted);
					// And merge up in the pool
					$this->_pool = array_merge($this->_pool, $extracted);
				}
			}

			// How many items have been extracted?
			$cli->tab()->writeln("$count item(s) extracted.")->eol();
			return TRUE;
		}

		$cli->err('DOM parser failed.')->eol();
		return FALSE;
	}

	// ------------------------------------------------------------------------
}
// End of GeneralCrawler class
