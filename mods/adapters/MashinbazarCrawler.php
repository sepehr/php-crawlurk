<?php defined('PATH') OR exit('No direct script access allowed');

// Include the PhoneExtractorCrawler
require_once PATH . 'mods/PhoneExtractorCrawler.php';

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
class MashinbazarCrawler extends PhoneExtractorCrawler {

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
	 * Array of URI regexes and associated POST data.
	 *
	 * @var array
	 */
	protected $_post = array(
		'/^http:\/\/www\.mashinbazar\.com\/search\/json\/a:list$/' => array(
			'iDisplayStart'  => 0,
			'iDisplayLength' => 10,
		),
	);

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
	protected $_follow = '/INTENTIONAL WRONG REGEX; SEE: process_first()/';

	// ------------------------------------------------------------------------

	/**
	 * Processes the first crawled page.
	 *
	 * NOTE: We're reading URLs to follow from a JSON file. The address to this JSON endpoint
	 * has been set as the starting URL of the adapter. The following method has wrapped the
	 * logic of parsing the JSON and extracting the URLs to follow on the forthcoming requests.
	 *
	 * Because of this (manually extracting the URLs to crawl) we need to set a invalid pattern
	 * in $_follow to avoid extra requests. So, the underlying crawler which respects the $_follow
	 * would find no links to add to the LinkCache. Instead we're adding our desired links here...
	 * manually.
	 *
	 * @return mixed
	 */
	public function process_first()
	{
		global $cli;

		if ( ! empty($this->_content) AND $json = json_decode($this->_content))
		{
			// Parse JSON and findout the child URLs to crawl
			if (empty($json->aaData))
			{
				$cli->err('"aaData" field is empty. No ads to crawl.')->eol();
				return FALSE;
			}

			$count = 0;
			foreach ($json->aaData as $ad)
			{
				// Seemingly wrapped in another array juss for fuckin fun!
				$ad     = $ad[0];
				$html   = str_get_html($ad);
				$anchor = $html->find('a');

				if (isset($anchor[0]) AND $anchor = $anchor[0]->getAttribute('href'))
				{
					$count++;
					$anchor = 'http://www.mashinbazar.com' . $anchor;

					// Push to the URL cache of the crawler
					$this->_add_url($anchor);
				}
			}

			$cli->info("Manually extracted and rewritten $count URLs to be crawled.")->eol();
		}

		// Invalid response
		else
		{
			$cli->err('Invalid JSON response.')->eol();
			return FALSE;
		}
	}

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
		$limit OR $limit = 51;
		$this->_post['/^http:\/\/www\.mashinbazar\.com\/search\/json\/a:list$/']['iDisplayLength'] = intval($limit) + 1;
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
		$this->_post['/^http:\/\/www\.mashinbazar\.com\/search\/json\/a:list$/']['iDisplayLength'] = 440;
	}

	// ------------------------------------------------------------------------
}
// End of MashinbazarCrawler class
