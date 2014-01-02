<?php defined('PATH') OR exit('No direct script access allowed');

/**
 * Crawler interface for Crawlurk adapters.
 *
 * All crawler adapters should implement this interface.
 *
 * @package		Crawlurk
 * @category	Core
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) Sepehr Lajevardi
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 */
interface BaseCrawlerInterface {

	// $_url;
	// $_port;
	// $_page;
	// $_pool;
	// $_content;
	// $_follow;
	// $_referrer;

	// ------------------------------------------------------------------------
	// Crawler Adapter API
	// ------------------------------------------------------------------------

	/**
	 * Processes crawled page content.
	 *
	 * @return mixed
	 */
	function process();

	// ------------------------------------------------------------------------

	/**
	 * Handles crawled page error.
	 *
	 * @param  int    $error_code   Error code.
	 * @param  string $error_string Error string.
	 *
	 * @return void
	 */
	function error($error_code, $error_string);

	// ------------------------------------------------------------------------

	/**
	 * Returns extraction pool.
	 *
	 * @return array
	 */
	function get_pool();

	// ------------------------------------------------------------------------

	/**
	 * Sets extraction pool value.
	 *
	 * @return void
	 */
	function set_pool(array $pool);

	// ------------------------------------------------------------------------

	/**
	 * Flushes extraction pool with fresh water!
	 *
	 * @return void
	 */
	function flush_pool();

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
	function handleHeaderInfo(PHPCrawlerResponseHeader $header);

	// ------------------------------------------------------------------------

	/**
	 * Handles crawled page response.
	 *
	 * @param  PHPCrawlerDocumentInfo $page Object info of crawled page.
	 *
	 * @return void
	 */
	function handleDocumentInfo(PHPCrawlerDocumentInfo $page);

	// ------------------------------------------------------------------------
}
// End of BaseCrawlerInterface interface
