<?php
/**
 * Parses and verifies the doc comments for files.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Niklas Simonis <niklas.simonis@mni.thm.de>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: FileCommentSniff.php 301632 2010-07-28 01:57:56Z squiz $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_CommentParser_AbstractParser', true) === false) {
	$error = 'Class PHP_CodeSniffer_CommentParser_AbstractParser not found';
	throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Parses and verifies the link in doc comments.
 *
 * Verifies that :
 * <ul>
 *  <li>A doc comment exists.</li>
 *  <li>There is a blank newline after the short description.</li>
 *  <li>There is a blank newline between the long and short description.</li>
 *  <li>There is a blank newline between the long description and tags.</li>
 *  <li>A PHP version is specified.</li>
 *  <li>Check the order of the tags.</li>
 *  <li>Check the indentation of each tag.</li>
 *  <li>Check required and optional tags and the format of their content.</li>
 * </ul>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Niklas Simonis <niklas.simonis@mni.thm.de>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

class THMPHP_CodeSniffer_CommentParser_ClassCommentLinksParser extends PHP_CodeSniffer_CommentParser_AbstractParser
{
	private $_link = null;
	
	protected function getAllowedTags()
	{
		return array(
				'link'		 => true,
		);
	
	}//end getAllowedTags()
	
	protected function parseLink($tokens)
	{
		$this->_link = new PHP_CodeSniffer_CommentParser_PairElement(
				$this->previousElement,
				$tokens,
				'link',
				$this->phpcsFile
		);
	
		return $this->_link;
	
	}//end parseLicense()
	
	public function getLink()
	{
		return $this->_link;
	
	}//end getVersion()
}