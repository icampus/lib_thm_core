<?php
// @codingStandardsIgnoreFile
/**
 * THMJoomla_Sniffs_PHP_IncludeRequireSniff
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Niklas Simonis <niklas.simonis@mni.thm.de>
 * @copyright 2012
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * THMJoomla_Sniffs_PHP_IncludeRequireSniff
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Niklas Simonis <niklas.simonis@mni.thm.de>
 * @copyright 2012
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.0.0
 */
class THMJoomla_Sniffs_PHP_IncludeRequireSniff implements PHP_CodeSniffer_Sniff
{
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register()
	{
		return array(
			T_INCLUDE
		);

	}//end register()

	/**
	 * Processes this test, when one of its tokens is encountered.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile The current file being processed.
	 * @param int                  $stackPtr  The position of the current token
	 *                                        in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$function  = $tokens[$stackPtr]['content'];
		$errorData = array(ucfirst($tokens[$stackPtr]['content']));

		if ($function == "include")
		{
			$error = 'Please use the function "include_once" or "require_once" instead.';
			$phpcsFile->addError($error, $stackPtr, 'IncludeError', $errorData);
		}
	}
}
