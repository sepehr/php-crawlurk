<?php defined('PATH') OR exit('No direct script access allowed');

/**
 * Crawler adapter class for new mashinbazar.com
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
class MashinbazarCrawler extends BaseCrawler {

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
	public $_url = 'http://www.mashinbazar.com/search/json/a:list';

	/**
	 * CSS selector.
	 *
	 * @var string
	 */
	protected $_selector = '.box1 .box1_list .adsCars.clearfix:nth-child(3) .col-lg-8 b';

	/**
	 * URI follow regex rule.
	 *
	 * @var string
	 */
	protected $_follow = '/\\"\\\/ads\\\/view\\\/id:\d+\\"/miu';
	// protected $_follow = '/(http:\/\/(www\.)?mashinbazar.com\/search\/json\/)?$/miu';

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
	//protected $_referer = '/(http:\/\/(www\.)?cartel.ir)?\/ajax.php\?search=0&page=[1-9]+/miu';

	// ------------------------------------------------------------------------

	public function process()
	{

	}

	// ------------------------------------------------------------------------
}
// End of MashinbazarCrawler class
