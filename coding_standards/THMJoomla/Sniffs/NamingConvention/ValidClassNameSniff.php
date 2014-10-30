<?php
/**
 * PEAR_Sniffs_NamingConventions_ValidClassNameSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * PEAR_Sniffs_NamingConventions_ValidClassNameSniff.
 *
 * Ensures class and interface names start with a capital letter
 * and use _ separators.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @author    Niklas Simonis <niklas.simonis@mni.thm.de>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.2
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class THMJoomla_Sniffs_NamingConvention_ValidClassNameSniff implements PHP_CodeSniffer_Sniff
{


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(
                T_CLASS,
                T_INTERFACE,
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
        $fileName = $phpcsFile->getFilename();
        $tokens = $phpcsFile->getTokens();

        $className = $phpcsFile->findNext(T_STRING, $stackPtr);
        $name      = trim($tokens[$className]['content']);
        $errorData = array(ucfirst($tokens[$stackPtr]['content']));

        // Make sure the first letter is a capital.
        if(strstr($name, 'JTable')
                || strstr($name, 'plgButton')
                || strstr($name, 'JElements')
                || strstr($name, 'JFormField')
                || strstr($name, 'Plg')
                || strstr($name, 'JHtml')
                || strstr($name, 'Com'))
        {
            if(!strstr($name, 'plgButton'))
            {
                if ((preg_match('|^[A-Z]|', $name) === 0) || (preg_match('|^[_A-Z]|', $name) === 0))
                {
                    $error = '%s name must begin with capital letters';
                    $phpcsFile->addError($error, $stackPtr, 'StartWithCaptial', $errorData);
                }
            }
        }
        else
        {
            if ((preg_match('|^[THM]|', $name) === 0) || (preg_match('|^[_THM]|', $name) === 0))
            {
                $error = '%s name must begin with THM capital letters';
                   $phpcsFile->addError($error, $stackPtr, 'StartWithCaptial', $errorData);
            }

            // Check MVC
            $this->checkMVC($name, $phpcsFile, $fileName, $errorData, $stackPtr, $stackPtr);
        }

        // Check that each new word starts with a capital as well, but don't
        // check the first word, as it is checked above.
        $validName = true;
        $nameBits  = explode('_', $name);
        $firstBit  = array_shift($nameBits);
        foreach ($nameBits as $bit) {
            if ($bit === '' || $bit{0} !== strtoupper($bit{0})) {
                $validName = false;
                break;
            }
        }

        if ($validName === false) {
            // Strip underscores because they cause the suggested name
            // to be incorrect.
            $nameBits = explode('_', trim($name, '_'));
            $firstBit = array_shift($nameBits);
            if ($firstBit === '') {
                $error = '%s name is not valid';
                $phpcsFile->addError($error, $stackPtr, 'Invalid', $errorData);
            } else {
                $newName = strtoupper($firstBit{0}).substr($firstBit, 1).'_';
                foreach ($nameBits as $bit) {
                    if ($bit !== '') {
                        $newName .= strtoupper($bit{0}).substr($bit, 1).'_';
                    }
                }

                $newName = rtrim($newName, '_');
                $error   = '%s name is not valid; consider %s instead';
                $data    = $errorData;
                $data[]  = $newName;
                $phpcsFile->addError($error, $stackPtr, 'Invalid', $data);
            }
        }//end if

    }//end process()

    /**
     * Checks System for MVC Convention
     *
     * @return void
     */
    public function checkMVC($name, $phpcsFile, $fileName, $errorData, $stackPtr, $stackPtr){

        // Check ENV
        $filepart = "";
        $env = getenv("OS");

        if($env == "Windows_NT")
        {
            $filepart = explode("\\", $fileName);
        }
        else
        {
            $filepart = explode("/", $fileName);
        }

        $count = sizeof($filepart);

        // Check Controller Class Name
        for($i=0; $i < $count; $i++)
        {
            if(strstr($filepart[$i], 'controllers'))
            {
                if(!strstr($name, 'Controller'))
                {
                    $error = '%s name must contain "Controller" Example: "<project_name>Controller<file_name>"';
                    $phpcsFile->addError($error, $stackPtr, 'InvalidControllerClassName', $errorData);
                }
            }
        }

        // Check Model Class Name
        for($i=0; $i < $count; $i++)
        {
            if(strstr($filepart[$i], 'models'))
            {
                if(!strstr($name, 'Model'))
                {
                    $error = '%s name must contain "Model" Example: "<project_name>Model<file_name>"';
                    $phpcsFile->addError($error, $stackPtr, 'InvalidModelClassName', $errorData);
                }
            }
        }

        // Check View Class Name
        for($i=0; $i < $count; $i++)
        {
            if(strstr($filepart[$i], 'views'))
            {
                if(!strstr($name, 'View') && $filepart[$i+2] == "view.html.php")
                {
                    $error = '%s name must contain "View" Example: "<project_name>View<file_name>"';
                    $phpcsFile->addError($error, $stackPtr, 'InvalidViewClassName', $errorData);
                }
            }
        }
    }


}//end class


?>
