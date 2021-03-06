<?php
/**
 * Generic_Sniffs_Commenting_FixmeSniff.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   CVS: $Id: FixmeSniff.php 301632 2010-07-28 01:57:56Z squiz $
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */

/**
 * Generic_Sniffs_Commenting_FixmeSniff.
 *
 * Warns about FIXME comments.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @version   Release: 1.3.0RC1
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 */
class Phpcs_Sniffs_Commenting_FixmeSniff implements PHP_CodeSniffer_Sniff
{

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
                                   'PHP',
                                   'JS',
                                  );


    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return PHP_CodeSniffer_Tokens::$commentTokens;

    }//end register()


    /**
     * Processes this sniff, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $content = $tokens[$stackPtr]['content'];
        $matches = Array();
        if (preg_match('|[^a-z]+fixme[^a-z]+(.*)|i', $content, $matches) !== 0) {
            // Clear whitespace and some common characters not required at
            // the end of a fix-me message to make the warning more informative.
            $type        = 'CommentFound';
            $fixmeMessage = trim($matches[1]);
            $fixmeMessage = trim($fixmeMessage, '[]().');
            $error       = 'Comment refers to a FIXME task';
            $data        = array($fixmeMessage);
            if ($fixmeMessage !== '') {
                $type   = 'TaskFound';
                $error .= ' "%s"';
            }

            $phpcsFile->addError($error, $stackPtr, $type, $data);
        }

    }//end process()


}//end class

?>
