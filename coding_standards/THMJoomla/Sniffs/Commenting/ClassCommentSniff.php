<?php
/**
 * Parses and verifies the doc comments for classes.
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
 * @version   CVS: $Id: ClassCommentSniff.php 301632 2010-07-28 01:57:56Z squiz $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

if (class_exists('PHP_CodeSniffer_CommentParser_ClassCommentParser', true) === false)
{
	$error = 'Class PHP_CodeSniffer_CommentParser_ClassCommentParser not found';
	throw new PHP_CodeSniffer_Exception($error);
}

require_once 'FileCommentSniff.php';
require_once 'ClassCommentLinksParser.php';

if (class_exists('Joomla_Sniffs_Commenting_FileCommentSniff', true) === false)
{
	$error = 'Class Joomla_Sniffs_Commenting_FileCommentSniff not found';
	throw new PHP_CodeSniffer_Exception($error);
}

/**
 * Parses and verifies the doc comments for classes.
 *
 * Verifies that :
 * <ul>
 *  <li>A doc comment exists.</li>
 *  <li>There is a blank newline after the short description.</li>
 *  <li>There is a blank newline between the long and short description.</li>
 *  <li>There is a blank newline between the long description and tags.</li>
 *  <li>Check the order of the tags.</li>
 *  <li>Check the indentation of each tag.</li>
 *  <li>Check required and optional tags and the format of their content.</li>
 * </ul>
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.0RC2
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class THMJoomla_Sniffs_Commenting_ClassCommentSniff extends THMJoomla_Sniffs_Commenting_FileCommentSniff
{


	/**
	 * Tags in correct order and related info.
	 *
	 * @var array
	 */
	protected $tags = array(
		'version'    => array(
			'required'       => false,
			'allow_multiple' => false,
			'order_text'     => 'is first',
		),
		'category'   => array(
			'required'       => true,
			'allow_multiple' => false,
			'order_text'     => 'must follow @version (if used)',
		),
		'package'    => array(
			'required'       => true,
			'allow_multiple' => false,
			'order_text'     => 'must follow @category (if used)',
		),
		'subpackage' => array(
			'required'       => false,
			'allow_multiple' => false,
			'order_text'     => 'must follow @package',
		),
		'author'     => array(
			'required'       => false,
			'allow_multiple' => true,
			'order_text'     => 'is first',
		),
		'copyright'  => array(
			'required'       => false,
			'allow_multiple' => true,
			'order_text'     => 'must follow @author (if used) or @subpackage (if used) or @package',
		),
		'license'    => array(
			'required'       => false,
			'allow_multiple' => false,
			'order_text'     => 'must follow @copyright (if used)',
		),
		'link'       => array(
			'required'       => false,
			'allow_multiple' => true,
			'order_text'     => 'must follow @version (if used)',
		),
		'see'        => array(
			'required'       => false,
			'allow_multiple' => true,
			'order_text'     => 'must follow @link (if used)',
		),
		'since'      => array(
			'required'       => false,
			'allow_multiple' => false,
			'order_text'     => 'must follow @see (if used) or @link (if used)',
		),
		'deprecated' => array(
			'required'       => false,
			'allow_multiple' => false,
			'order_text'     => 'must follow @since (if used) or @see (if used) or @link (if used)',
		),
	);

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
	 * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
	 * @param int                  $stackPtr  The position of the current token
	 *                                        in the stack passed in $tokens.
	 *
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$this->currentFile = $phpcsFile;

		$tokens    = $phpcsFile->getTokens();
		$type      = strtolower($tokens[$stackPtr]['content']);
		$errorData = array($type);
		$find      = array(
			T_ABSTRACT,
			T_WHITESPACE,
			T_FINAL,
		);

		// Extract the class comment docblock.
		$commentEnd = $phpcsFile->findPrevious($find, ($stackPtr - 1), null, true);

		if ($commentEnd !== false && $tokens[$commentEnd]['code'] === T_COMMENT)
		{
			$error = 'You must use "/**" style comments for a %s comment';
			$phpcsFile->addError($error, $stackPtr, 'WrongStyle', $errorData);

			return;
		}
		else
		{
			if ($commentEnd === false
				|| $tokens[$commentEnd]['code'] !== T_DOC_COMMENT
			)
			{
				$phpcsFile->addError('Missing %s doc comment', $stackPtr, 'Missing', $errorData);

				return;
			}
		}

		$commentStart = ($phpcsFile->findPrevious(T_DOC_COMMENT, ($commentEnd - 1), null, true) + 1);
		$commentNext  = $phpcsFile->findPrevious(T_WHITESPACE, ($commentEnd + 1), $stackPtr, false, $phpcsFile->eolChar);

		// Distinguish file and class comment.
		$prevClassToken = $phpcsFile->findPrevious(T_CLASS, ($stackPtr - 1));
		if ($prevClassToken === false)
		{
			// This is the first class token in this file, need extra checks.
			$prevNonComment = $phpcsFile->findPrevious(T_DOC_COMMENT, ($commentStart - 1), null, true);
			if ($prevNonComment !== false)
			{
				$prevComment = $phpcsFile->findPrevious(T_DOC_COMMENT, ($prevNonComment - 1));
				if ($prevComment === false)
				{
					// There is only 1 doc comment between open tag and class token.
					$newlineToken = $phpcsFile->findNext(T_WHITESPACE, ($commentEnd + 1), $stackPtr, false, $phpcsFile->eolChar);
					if ($newlineToken !== false)
					{
						$newlineToken = $phpcsFile->findNext(
							T_WHITESPACE,
							($newlineToken + 1),
							$stackPtr,
							false,
							$phpcsFile->eolChar
						);

						if ($newlineToken !== false)
						{
							// Blank line between the class and the doc block.
							// The doc block is most likely a file comment.
							$error = 'Missing %s doc comment';
							$phpcsFile->addError($error, ($stackPtr + 1), 'Missing', $errorData);

							return;
						}
					}//end if
				}//end if
			}//end if
		}//end if

		$comment = $phpcsFile->getTokensAsString(
			$commentStart,
			($commentEnd - $commentStart + 1)
		);

		// Parse the class comment.docblock.
		try
		{
			$this->commentParser = new PHP_CodeSniffer_CommentParser_ClassCommentParser($comment, $phpcsFile);
			$this->commentParser->parse();
			$this->commentParserLink = new THMPHP_CodeSniffer_CommentParser_ClassCommentLinksParser($comment, $phpcsFile);
			$this->commentParserLink->parse();
		}
		catch (PHP_CodeSniffer_CommentParser_ParserException $e)
		{
			$line = ($e->getLineWithinComment() + $commentStart);
			$phpcsFile->addError($e->getMessage(), $line, 'FailedParse');

			return;
		}

		$comment = $this->commentParser->getComment();
		if (is_null($comment) === true)
		{
			$error = 'Doc comment is empty for %s';
			$phpcsFile->addError($error, $commentStart, 'Empty', $errorData);

			return;
		}

		// No extra newline before short description.
		$short        = $comment->getShortComment();
		$newlineCount = 0;
		$newlineSpan  = strspn($short, $phpcsFile->eolChar);
		if ($short !== '' && $newlineSpan > 0)
		{
			$error = 'Extra newline(s) found before %s comment short description';
			$phpcsFile->addError($error, ($commentStart + 1), 'SpacingBeforeShort', $errorData);
		}

		$newlineCount = (substr_count($short, $phpcsFile->eolChar) + 1);

		// Exactly one blank line between short and long description.
		$long = $comment->getLongComment();
		if (empty($long) === false)
		{
			$between        = $comment->getWhiteSpaceBetween();
			$newlineBetween = substr_count($between, $phpcsFile->eolChar);
			if ($newlineBetween !== 2)
			{
				$error = 'There must be exactly one blank line between descriptions in %s comments';
				$phpcsFile->addError($error, ($commentStart + $newlineCount + 1), 'SpacingAfterShort', $errorData);
			}

			$newlineCount += $newlineBetween;
		}

		// Exactly one blank line before tags.
		$tags = $this->commentParser->getTagOrders();
		if (count($tags) > 1)
		{
			$newlineSpan = $comment->getNewlineAfter();
			if ($newlineSpan !== 2)
			{
				$error = 'There must be exactly one blank line before the tags in %s comments';
				if ($long !== '')
				{
					$newlineCount += (substr_count($long, $phpcsFile->eolChar) - $newlineSpan + 1);
				}

				$phpcsFile->addError($error, ($commentStart + $newlineCount), 'SpacingBeforeTags', $errorData);
				$short = rtrim($short, $phpcsFile->eolChar . ' ');
			}
		}

		// Check each tag.
		$this->processTags($commentStart, $commentEnd);

	}//end process()


	/**
	 * Process the version tag.
	 *
	 * @param int $errorPos The line number where the error occurs.
	 *
	 * @return void
	 */
	protected function processVersion($errorPos)
	{
		$version = $this->commentParser->getVersion();
		if ($version !== null)
		{
			$content = $version->getContent();
			$matches = array();
			if (empty($content) === true)
			{
				$error = 'Content missing for @version tag in file comment';
				$this->currentFile->addError($error, $errorPos, 'EmptyVersion');
			}
			else
			{
				if (!preg_match("/v([0-9]).([0-9]).([0-9])/", $content))
				{
					$error = 'Invalid version "' . $content . '" in file comment; consider "v<MajorRelease.MinorRelease.MaintenanceRelease>" instead. Example: "v1.0.0"';
					$data  = array($content);
					$this->currentFile->addError($error, $errorPos, 'InvalidVersion');
				}
			}
		}

	}//end processVersion()

	/**
	 * Process the package tag.
	 *
	 * @param int $errorPos The line number where the error occurs.
	 *
	 * @return void
	 */
	protected function processPackage($errorPos)
	{
		$package = $this->commentParser->getPackage();
		if ($package !== null)
		{
			$content = $package->getContent();
			$matches = array();
			if (empty($content) === true)
			{
				$error = 'Content missing for @package tag in doc comment';
				$this->currentFile->addError($error, $errorPos, 'EmptyPackage');
			}
			else
			{
				if (!strstr($content, 'thm_'))
				{
					$error = 'Invalid package "%s" in doc comment; consider "Package: thm_<name>" instead';
					$data  = array($content);
					$this->currentFile->addError($error, $errorPos, 'InvalidPackage', $data);
				}
				/*
				if(!preg_match("/thm_(\w+)/", $content))
				{
					$error = 'Invalid package "%s" in doc comment; consider "Package: thm_<name>" instead';
					$data  = array($content);
					$this->currentFile->addError($error, $errorPos, 'InvalidPackage', $data);
				}
				*/
			}
		}
	}//end processPackage()

	/**
	 * Process the subpackage tag.
	 *
	 * @param int $errorPos The line number where the error occurs.
	 *
	 * @return void
	 */
	protected function processSubPackage($errorPos)
	{
		$subpackage = $this->commentParser->getSubpackage();
		if ($subpackage !== null)
		{
			$content = $subpackage->getContent();
			$matches = array();
			if (empty($content) === true)
			{
				$error = 'Content missing for @subpackage tag in doc comment';
				$this->currentFile->addError($error, $errorPos, 'EmptySubPackage');
			}
			else
			{
				if (!preg_match("/com_thm_(\w+)/", $content) && !preg_match("/mod_thm_(\w+)/", $content) && !preg_match("/plg_thm_(\w+)/", $content) && !preg_match("/lib_thm_(\w+)/", $content))
				{
					$error = 'Invalid subpackage "%s" in doc comment; consider "SubPackage: com_thm_<name>, mod_thm_<name>, plg_thm_<name> or lib_thm_<name>" instead';
					$data  = array($content);
					$this->currentFile->addError($error, $errorPos, 'InvalidSubPackage', $data);
				}
			}
		}
	}//end processSubPackage()

	/**
	 * Process the category tag.
	 *
	 * @param int $errorPos The line number where the error occurs.
	 *
	 * @return void
	 */
	protected function processCategory($errorPos)
	{
		$category = $this->commentParser->getCategory();
		if ($category !== null)
		{
			$content = $category->getContent();
			$matches = array();
			if (empty($content) === true)
			{
				$error = 'Content missing for @$category tag in doc comment';
				$this->currentFile->addError($error, $errorPos, 'EmptyCategory');
			}
			else
			{
				if (!substr($content, 0, 7) == "Joomla.")
				{
					$error = 'Invalid category "%s" in doc comment; must start with "Joomla.<Type>" Example "Category: "Joomla.Component", "Joomla.Module", "Joomla.Plugin" or "Joomla.Library" instead';
					$data  = array($content);
					$this->currentFile->addError($error, $errorPos, 'InvalidCategory', $data);
				}
				else
				{
					if (strstr($content, 'Joomla.Component'))
					{
						if ($content != "Joomla.Component.Admin" && $content != "Joomla.Component.Site" && $content != "Joomla.Component.General")
						{
							$error = 'Invalid category "%s" in doc comment; must "Joomla.Component.General", "Joomla.Component.Site" or "Joomla.Component.Admin"  instead';
							$data  = array($content);
							$this->currentFile->addError($error, $errorPos, 'InvalidCategoryComponent', $data);
						}
					}
					else
					{
						if (strstr($content, 'Joomla.Module'))
						{
							if ($content != "Joomla.Module.Admin" && $content != "Joomla.Module.Site" && $content != "Joomla.Module.General")
							{
								$error = 'Invalid category "%s" in doc comment; must "Joomla.Module.General", "Joomla.Module.Site" or "Joomla.Module.Admin"  instead';
								$data  = array($content);
								$this->currentFile->addError($error, $errorPos, 'InvalidCategoryModule', $data);
							}
						}
						else
						{
							if (strstr($content, 'Joomla.Plugin'))
							{
								if ($content != "Joomla.Plugin.Authentication" &&
									$content != "Joomla.Plugin.Captcha" &&
									$content != "Joomla.Plugin.Content" &&
									$content != "Joomla.Plugin.Editors" &&
									$content != "Joomla.Plugin.Editors-XTD" &&
									$content != "Joomla.Plugin.Extension" &&
									$content != "Joomla.Plugin.Finder" &&
									$content != "Joomla.Plugin.Quickicon" &&
									$content != "Joomla.Plugin.Search" &&
									$content != "Joomla.Plugin.System" &&
									$content != "Joomla.Plugin.User"
								)
								{
									$error = 'Invalid category "%s" in doc comment; contain Joomla.Plugin.<Type>"
                            Example Category:
                            "Joomla.Plugin.Authentication",
                            "Joomla.Plugin.Captcha",
                            "Joomla.Plugin.Content",
                            "Joomla.Plugin.Editors",
                            "Joomla.Plugin.Editors-XTD",
                            "Joomla.Plugin.Extension",
                            "Joomla.Plugin.Finder",
                            "Joomla.Plugin.Quickicon",
                            "Joomla.Plugin.Search" or
                            "Joomla.Plugin.User"';
									$data  = array($content);
									$this->currentFile->addError($error, $errorPos, 'InvalidCategoryPlugin', $data);
								}
							}
							else
							{
								if ($content == "Joomla.Library")
								{
									// nothing yet
								}
								else
								{
									$error = 'Invalid category "%s" in doc comment; must start with "Joomla.<Type>" Example "Category: "Joomla.Component", "Joomla.Module", "Joomla.Plugin" or "Joomla.Library" instead';
									$data  = array($content);
									$this->currentFile->addError($error, $errorPos, 'InvalidCategory', $data);
								}
							}
						}
					}
				}
			}
		}
	}//end processCategory()

	/**
	 * Process the since tag.
	 *
	 * @param int $errorPos The line number where the error occurs.
	 *
	 * @return void
	 */
	protected function processSince($errorPos)
	{
		$category = $this->commentParser->getSince();
		if ($category !== null)
		{
			$content = $category->getContent();

			$matches = array();
			if (empty($content) === true)
			{
				$error = 'Content missing for @$category tag in doc comment';
				$this->currentFile->addError($error, $errorPos, 'EmptySince');
			}
			else
			{
				if (strstr($content, 'v') == false)
				{
					$error = 'Invalid version "%s" in class doc comment; consider "v<version_number>" instead';
					$data  = array($content);
					$this->currentFile->addError($error, $errorPos, 'InvalidSince', $data);
				}
			}
		}
	}//end processSince()

	/**
	 * Process the link tag.
	 *
	 * @param int $errorPos The line number where the error occurs.
	 *
	 * @return void
	 */
	protected function processLinks($errorPos)
	{
		$link = $this->commentParserLink->getLink();
		if ($link !== null)
		{
			$content = $link->getValue();
			$matches = array();
			if (empty($content) === true)
			{
				$error = 'Content missing for @link tag in file comment';
				$this->currentFile->addError($error, $errorPos, 'EmptyLink');
			}
			if ($content != 'www.mni.thm.de' && $content != 'www.thm.de')
			{
				$error = 'Invalid link "%s" in file comment; consider "www.mni.thm.de" or "www.thm.de" instead';
				$this->currentFile->addError($error, $errorPos, 'InvalidLink');
			}
		}

	}//end processLink()
}//end class

?>
