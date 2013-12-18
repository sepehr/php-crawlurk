<?php defined('PATH') OR exit('No direct script access allowed');

// Include the PhoneExtractorCrawler
require_once PATH . 'mods/PhoneExtractorCrawler.php';

/**
 * Crawler adapter class for bama.ir
 *
 * @package		TakhtegazCrawler
 * @category	Adapters
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) 2013 www.takhtegaz.com
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

	// ------------------------------------------------------------------------

	/**
	 * Crawler adapter constructor.
	 */
	public function __construct($limit = FALSE, $all = FALSE)
	{
		// This check ensures that the URL population will
		// be performed just once. When the main crawler
		// script instantiates adapters (like this one)
		// will pass a $limit parameter. But when called
		// by the underlying crawler engine (PHPCrawler)
		// this value is unset.
		if ($limit OR $all)
		{
			// Maximum pages (site pagination) to hit:
			// It's different from Crawler's passed $limit,
			// it actually indicates how many time the crawler
			// should be re-dispatched with a new starting URI.
			$pager_max = $all ? 11 : 3;

			// Sets dynamic starting URLs to crawl
			for ($i = 1; $i <= $pager_max; $i++)
			{
				$this->_url[] = "http://www.bama.ir/car/page=$i";
			}
		}

		// We're done, let the parents in
		parent::__construct($limit, $all);
	}

	// ------------------------------------------------------------------------
}
// End of BamaCrawler class
