<?php
// @codingStandardsIgnoreFile
/**
 * THMJoomla_Sniffs_PHP_ValidLanguageSniff
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
 * THMJoomla_Sniffs_PHP_ValidLanguageSniff
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Niklas Simonis <niklas.simonis@mni.thm.de>
 * @copyright 2012
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.0.0
 */
class THMJoomla_Sniffs_PHP_ValidLanguageSniff implements PHP_CodeSniffer_Sniff
{
	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register()
	{
		return array(
			T_OPEN_PARENTHESIS
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
		$tokens        = $phpcsFile->getTokens();
		$errorData     = array(ucfirst($tokens[$stackPtr]['content']));
		$function      = $tokens[$stackPtr]['content'];
		$functionEnd   = $phpcsFile->findNext(T_CLOSE_PARENTHESIS, $stackPtr + 1);
		$functionStart = $stackPtr + 1;

		// Check ENV
		$env = getenv("OS");

		if ($tokens[$stackPtr - 1]['content'] == "_"
			&& $tokens[$stackPtr - 2]['content'] == "::"
			&& $tokens[$stackPtr - 3]['content'] == "JText"
		)
		{
			$variable = "";
			// Inhalt des JTEXT zusammenbasteln
			while ($functionStart < $functionEnd)
			{
				$variable = $variable . $tokens[$functionStart]['content'];
				$functionStart++;
			}

			if (strpos($variable, ",") != false)
			{
				$variable = strstr($variable, ',', true);
			}

			if (strpos($variable, ".") != false)
			{
				// Fehlerbehandlung
				$error = 'Concat operator not allowed in language keys.';
				$phpcsFile->addWarning($error, $stackPtr - 4, 'ConcatOperator', $errorData);
			}

			// Hochkommas entfernen
			$pos      = "";
			$variable = str_replace("'", "", $variable);
			$variable = str_replace("\"", "", $variable);
			// echo $variable . "\n";
			// Prüfen ob Variable
			if (preg_match("/[$].*/", $variable)
				|| preg_match("/J/", $variable)
				|| preg_match("/DATE_FORMAT/", $variable)
				|| preg_match("/COM_CONTENT/", $variable)
				|| preg_match("/COM_CATEGORIES/", $variable)
				|| (strstr($variable, '(') && strstr($variable, ')'))
			)
			{
				//echo "test\n";
				// do nothing!
			}
			// Prüfen auf Großschreibung und Zahlen
			elseif (preg_match("/[_A-Z0-9]*/", $variable))
			{
				if (preg_match("/COM_THM_/", $variable)
					|| preg_match("/MOD_THM_/", $variable)
					|| preg_match("/LIB_THM_/", $variable)
					|| preg_match("/PLG_AUTHENTICATION_THM_/", $variable)
					|| preg_match("/PLG_CAPTCHA_THM_/", $variable)
					|| preg_match("/PLG_CONTENT_THM_/", $variable)
					|| preg_match("/PLG_FIDER_THM_/", $variable)
					|| preg_match("/PLG_EDITORS_THM_/", $variable)
					|| preg_match("/PLG_EDITORS_XTD_THM_/", $variable)
					|| preg_match("/PLG_SEARCH_THM_/", $variable)
					|| preg_match("/PLG_SYSTEM_THM_/", $variable)
					|| preg_match("/PLG_USER_THM_/", $variable)
				)
				{
					$fileName    = $phpcsFile->getFilename();
					$filepart    = explode("\"", $fileName);
					$path_parts  = pathinfo($filepart[0]);
					$filepart[0] = $path_parts['dirname'];

					$pos_com = strrpos($filepart[0], "com_thm");
					$pos_mod = strrpos($filepart[0], "mod_thm");
					$pos_plg = strrpos($filepart[0], "plg_thm");

					if ($pos_com > 0)
					{
						$mainordner = substr($filepart[0], 0, $pos_com);
					}
					if ($pos_mod > 0)
					{
						$mainordner = substr($filepart[0], 0, $pos_mod);
					}
					if ($pos_plg > 0)
					{
						$mainordner = substr($filepart[0], 0, $pos_plg);
					}

					$files = scandir($mainordner);
					$count = sizeof($files);
					for ($i = 0; $i < $count; $i++)
					{
						if (preg_match("/^com_/", $files[$i]) || preg_match("/^mod_/", $files[$i]) || preg_match("/^plg_/", $files[$i]))
						{
							$mainordner = $mainordner . "" . $files[$i];
							$files2     = scandir($mainordner);
							$status     = false;

							// Nach language Ordner suchen
							for ($j = 0; $j < sizeof($files2); $j++)
							{
								if ($files2[$j] == "language")
								{
									$status = true;
									if ($env == "Windows_NT")
									{
										$langfolder = $mainordner . "\\" . $files2[$j];
									}
									else
									{
										$langfolder = $mainordner . "/" . $files2[$j];
									}
								}
							}
							if (!$status)
							{
								// Fehlerbehandlung
								$error = 'No language folder found.';
								$phpcsFile->addError($error, $stackPtr - 4, 'NoLanguageFolder', $errorData);
							}
							else
							{
								// Scannen des ganzen Language Ordners
								$files3 = scandir($langfolder);
								$key    = array_search('index.html', $files3);
								if ($key != false)
								{
									unset($files3[$key]);
								}

								$countlang = sizeof($files3);
								$found     = 0;
								$langfound = false;
								$foundVar  = false;

								for ($k = 0; $k < sizeof($files3); $k++)
								{
									if (preg_match("/[A-Za-z]/", $files3[$k]))
									{
										$langfound = true;

										if ($env == "Windows_NT")
										{
											$langfolderdir = $langfolder . "\\" . $files3[$k];
										}
										else
										{
											$langfolderdir = $langfolder . "/" . $files3[$k];
										}

										// Scannen der einzelnen Language Ordner
										$files4 = scandir($langfolderdir);

										$key_lang = array_search('index.html', $files4);
										if ($key_lang != false)
										{
											unset($files4[$key_lang]);
										}

										if ($files4 != null)
										{
											unset($files4[0]);
											unset($files4[1]);
										}

										for ($z = 2; $z < (sizeof($files4) + 2); $z++)
										{
											if (preg_match("/.ini/", $files4[$z]) && !preg_match("/sys.ini/", $files4[$z]))
											{
												if ($env == "Windows_NT")
												{
													$filepath = $langfolderdir . "\\" . $files4[$z];
												}
												else
												{
													$filepath = $langfolderdir . "/" . $files4[$z];
												}

												// Auf Doppeleintrag prüfen
												$ini_content = file_get_contents($filepath);
												$ini_content = str_replace(" ", "", $ini_content);
												// Parsen der .ini File
												$ini_array = parse_ini_file($filepath);

												$multipleentrys = substr_count($ini_content, "\n" . $variable . "=");
												if ($multipleentrys > 1)
												{
													// Fehlerbehandlung
													$error = 'Constant "' . $variable . '" more then one times defined in File "' . $files4[$z] . '". ';
													$phpcsFile->addError($error, $stackPtr - 4, 'MultipleDefined', $errorData);
												}

												if (array_key_exists($variable, $ini_array))
												{
													$foundVar = true;
													$found++;
												}
											}
										}
									}
								}

								if ($found != ($countlang - 2) && $foundVar)
								{
									// Fehlerbehandlung
									$error = 'Constant not defined in all languages.';
									$phpcsFile->addError($error, $stackPtr - 4, 'NotInAllLanguages', $errorData);
								}
								if (!$langfound)
								{
									// Fehlerbehandlung
									$error = 'No language found.';
									$phpcsFile->addError($error, $stackPtr - 4, 'NoLanguage', $errorData);
								}
								if (!$foundVar)
								{
									// Fehlerbehandlung
									$error = 'Constant not found in language file';
									$phpcsFile->addError($error, $stackPtr - 4, 'ConstantNotFound', $errorData);
								}
							}
						}
					}
				}
				else
				{
					// Fehlerbehandlung
					$error = 'Please use LanguageConstants, should start with "COM_THM_", "MOD_THM_", "PLG_<GROUP>_THM_" or "LIB_THM". Exception system variables.';
					$phpcsFile->addError($error, $stackPtr - 4, 'WrongType', $errorData);
				}
			}
			else
			{
				// Fehlerbehandlung
				$error = 'Language constant must be capital letters.';
				$phpcsFile->addError($error, $stackPtr - 4, 'LanguageConstantUppercase', $errorData);
			}
		}
	}
}
