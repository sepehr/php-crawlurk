<?php defined('PATH') OR exit('No direct script access allowed');
/**
 * Procedural helpers for Takhtegaz Crawler.
 *
 * @package		TakhtegazCrawler
 * @category	Core
 * @author		Sepehr Lajevardi <me@sepehr.ws>
 * @copyright	Copyright (c) 2013 www.takhtegaz.com
 * @license		WTFPL - http://www.wtfpl.net/txt/copying/
 * @filesource
 */

if ( ! function_exists('rmdir_recursive'))
{
	/**
	 * Recursively removes a directory.
	 *
	 * @param  string $path    Directory path to remove.
	 * @param  bool   $suicide Whether to remove itself or not.
	 *
	 * @return void
	 */
	function rmdir_recursive($path, $suicide = TRUE)
	{
		static $self;
		isset($self) OR $self = $path;

		$result   = FALSE;
		$iterator = new DirectoryIterator($path);

		// Recurse into the path
		foreach ($iterator as $item)
		{
			// Remove if it's a file
			$item->isFile() AND unlink($item->getRealPath());

			// Go deep Chandler, go!
			if ( ! $item->isDot() AND $item->isDir())
			{
				rmdir_recursive($item->getRealPath());
			}
		}

		// Remove child dirs. Source dir (initial $path) will
		// be removed only if it's a suicidal function call!
		if ($suicide OR realpath($self) != realpath($path))
		{
			rmdir($path);
		}
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('help_crawler'))
{
	/**
	 * Prints CLI help for the crawler script.
	 *
	 * @return void
	 */
	function help_crawler()
	{
		global $cli;

		$cli->eol()
			// Name:
			->writeln('NAME')
			->tab()
			->writeln('Takhtegaz Crawler')
			->eol()
			// Synopsis:
			->writeln('SYNOPSIS')
			->tab()
			->writeln('php path/to/crawler --adapter=ADAPTER_NAME --limit=CRAWL_PAGE_LIMIT --output=DUMP_DIR')
			->eol()
			// Description:
			->writeln('DESCRIPTION')
			->tab()
			->writeln('Modular and Multi-process web crawler for Takhtegaz')
			->eol()
			// Options:
			->writeln('OPTIONS')
			// --help option:
			->tab()
			->writeln('--help:    Shows this help content')
			// --limit option:
			->tab()
			->writeln('--limit:   Page limit to crawl')
			// --all option:
			->tab()
			->writeln('--all:     Tries to crawl the whole website to extract all desired items instead of new ones')
			// --output option:
			->tab()
			->writeln('--output:  Output directory (default to PATH/tmp/)')
			// --dump option:
			->tab()
			->writeln('--dump:    Dumps extractions to screen instead of file. Ignores --output option')
			// --multi option:
			->tab()
			->writeln('--multi:   Multi-process mode. Requires: PCNTL, SEMAPHORE, POSIX, PDO and PDO_SQLITE driver. Only works on unix-based systems and in CLI environment')
			// --hello option:
			->tab()
			->writeln('--hello:   Say hello to this crawler, face to face!')
			// --adapter option:
			->tab()
			->writeln('--adapter: Adapter (site) name. Valid adapter names:')
				// Valid adapter names:
				->tab()->tab()
				->writeln('* cario')
				->tab()->tab()
				->writeln('* cartel')
				->tab()->tab()
				->writeln('* hamshahree')
				->tab()->tab()
				->writeln('* mashinbazar')
				->tab()->tab()
				->writeln('* onecar')
				->tab()->tab()
				->writeln('* bama')
			->eol();
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('help_mailer'))
{
	/**
	 * Prints CLI help for the mailer script.
	 *
	 * @return void
	 */
	function help_mailer()
	{
		global $cli;

		$cli->eol()
			// Name:
			->writeln('NAME')
			->tab()
			->writeln('Takhtegaz Crawler Mailer')
			->eol()
			// Synopsis:
			->writeln('SYNOPSIS')
			->tab()
			->writeln('php path/to/mailer --email=EMAIL_ADDRESS --dir=DUMP_DIR')
			->eol()
			// Description:
			->writeln('DESCRIPTION')
			->tab()
			->writeln('Merges crawler output files together and mails it')
			->eol()
			// Options:
			->writeln('OPTIONS')
			// --help option:
			->tab()
			->writeln('--help:     Shows this help content')
			// --email option:
			->tab()
			->writeln('--email:    Email address to send crawler report to')
			// --dir option:
			->tab()
			->writeln('--dir:      Path to directory of crawler output files')
			// --subdir option:
			->tab()
			->writeln('--subdir:   Subdirectory to look in instead of date("ymd")')
			// --rmsubdir option:
			->tab()
			->writeln('--rmsubdir: Purge subdirectory and its input files after successfull merge')
			->eol();
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('signature'))
{
	/**
	 * Returns crawler mailer signature.
	 *
	 * @return string
	 */
	function signature()
	{
		$feelings = array(
			'Happy', 'Excited', 'Tired', 'Annoyed',
			'Scarstic', 'Blessed', 'Confused', 'Angry',
			'Bored', 'Sick', 'Awesome', 'Sleepy',
			'Guilty', 'Amused', 'Exhausted', 'Hopeful',
			'Alone', 'Tough', 'Lost', 'Relaxed',
			'Depressed', 'Accomplished', 'Curious',
			'Lost', 'Ignorant', 'Proud', 'Crawly',
			'Drunk', 'Wasted', 'Fresh', 'Stupid',
			'Lovely', 'Busy', 'Disappointed', 'Hungry',
			'Fool', 'Poor', 'Wealthy', 'Bossy', 'Cold',
		);

		return '—— ' . $feelings[array_rand($feelings)] . ' Crawler Agent あ';
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('greeting'))
{
	/**
	 * Returns how crawler greets!
	 *
	 * @return string
	 */
	function greeting()
	{
		$greeting = array(
			'Hi!', 'Hello,', 'Hello there,', 'Hi there,',
			'Hey yo!', 'Zzup!', 'Yo, man!', 'Sup?!',
			'Holla!', 'Hallo,', 'Bonos díes!', 'Saluton!',
			'Bonjour!', 'Salut!', 'Aloha!', 'Salute,',
			'¡Hola!', 'Ciao!',
		);
		return $greeting[array_rand($greeting)];
	}
}

// End of file.