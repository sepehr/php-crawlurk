<?php defined('PATH') OR exit('No direct script access allowed');

// Include the GeneralCrawler
require_once PATH . 'mods/GeneralCrawler.php';

/**
 * Phone Extractor Crawler Adapter Class.
 *
 * This adapter extracts IR phone numbers given a CSS selector.
 *
 * @package		TakhtegazCrawler
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) 2013 www.takhtegaz.com
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
abstract class PhoneExtractorCrawler extends GeneralCrawler {

	/**
	 * Extraction Regex for IR phone numbers.
	 *
	 * Accepted formats are:
	 * - +989xxxxxxxxx
	 * - 09xxxxxxxxx
	 * - 9xxxxxxxxx
	 *
	 * Space characters are escaped.
	 *
	 * @var string
	 */
	protected $_regex = '/(\+98)?0?9\d{9}/miu';

	// ------------------------------------------------------------------------
	// Protected Helpers
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
		// Remove white-space characters
		return str_replace(array(' ', "\t"), '', $haystack);
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
		// Maintain phone number format
		foreach ($match as &$phone)
		{
			$phone = str_replace(array(' ', '+98'), array('', ''), $phone);
			$phone = '0' . ltrim($phone, '0');
		}

		return $match;
	}

	// ------------------------------------------------------------------------
}
// End of PhoneExtractorCrawler class
