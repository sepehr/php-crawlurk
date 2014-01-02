<?php defined('PATH') OR exit('No direct script access allowed');

// Include the GeneralCrawler
require_once PATH . 'mods/GeneralCrawler.php';

/**
 * Email Extractor Crawler Adapter Class.
 *
 * This adapter extracts valid email addresses given a CSS selector.
 *
 * @package		Crawlurk
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) Sepehr Lajevardi
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
abstract class EmailExtractorCrawler extends GeneralCrawler {

	/**
	 * Extraction Regex for valid email addresses.
	 *
	 * @var string
	 */
	protected $_regex = '/[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+/miu';

	// ------------------------------------------------------------------------
}
// End of EmailExtractorCrawler class
