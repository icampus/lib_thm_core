<?php
/**
 * THMJoomla_Sniffs_PHP_ValidQuerySniff
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Niklas Simonis <niklas.simonis@mni.thm.de>
 * @copyright 2104
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * THMJoomla_Sniffs_PHP_ValidQuerySniff
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Niklas Simonis <niklas.simonis@mni.thm.de>
 * @copyright 2104
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.0.0
 */
class THMJoomla_Sniffs_PHP_ValidQuerySniff implements PHP_CodeSniffer_Sniff
{
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register()
	{
		return array(
				T_VARIABLE
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
        $errorData = array(ucfirst($tokens[$stackPtr]['content']));
      	$function = $tokens[$stackPtr]['content'];

      	
      	if($tokens[$stackPtr+2]['content'] == "=")
      	{
      		$variableStart = $phpcsFile->findNext(T_EQUAL, $stackPtr+1);
      		$variableEnd = $phpcsFile->findNext(T_SEMICOLON, $stackPtr+1);
      		
      		$content = "";
      		while($variableStart < $variableEnd)
      		{
      			$content = $content . $tokens[$variableStart]['content'];
      			$variableStart++;
      		}
      		
      		if(strstr($content, 'ALTER TABLE ')
      				|| strstr($content, 'INSERT INTO')
      				|| strstr($content, 'UPDATE ')
      				|| strstr($content, ' FROM')
      				|| strstr($content, 'DELETE FROM')
      				|| strstr($content, 'SELECT '))
      		{
      			$error = 'Please use the new SQL-API.';
      			$phpcsFile->addError($error, $stackPtr, 'InvalidSQLQuery', $errorData);
      		}
      	}
	}
}