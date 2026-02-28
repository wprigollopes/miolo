<?php
/**
 * Copyright 2005-2017 Solis Soluções Livres Ltda.
 *
 * This file is part of the SolisGE/Sagu program.
 *
 * SolisGE/Sagu is proprietary software of SOLIS, developed and maintained
 * exclusively by this company.
 *
 * The usage license is available through exclusive acquisition from SOLIS.
 * The license is granted on a non-exclusive basis to the licensee.
 * Usage rights are perpetual.
 *
 * Although source code is provided, the software is the property of SOLIS.
 * The licensee is not permitted to resell, lend, or transfer (whether for
 * payment or not) the license to third parties. It is also not permitted,
 * at any time or for any reason, to perform any alienation, reproduction,
 * distribution, disclosure, registration, licensing, transfer, or any other
 * act that may harm or compromise the software property rights, the name
 * and image of its owner and the software itself, or that constitutes
 * competition with SOLIS.
 *
 * The licensee, with access to the software source code, shall have the
 * right to make changes to the respective code. However, in situations
 * where the licensee relies on official support provided by SOLIS, changes
 * to the source code are not permitted, under penalty of losing said support.
 *
 * For detailed information about the SolisGE/Sagu Software Licensing Terms,
 * read the "LICENCA.txt" file included with this software. e
 * 
 * 
 *
 *
 *
 *
 *
 **/
class BString
{
    private $string;
    private $encoding = 'UTF-8';
    
    /**
     * Constructs the object
     *
     * @param string $string Initial content
     * @param string $encoding Encoding
     */
    public function __construct($string = null, $encoding = null)
    {
        // If no encoding was passed, get it from the constant
        if ( $encoding == null && defined('BASE_ENCODING') )
        {
            $encoding = BASE_ENCODING;
        }
        
        // Set the encoding only if it was passed as parameter or if the BASE_ENCODING constant is defined, otherwise use UTF-8.
        if ( $encoding )
        {
            $this->setEncoding($encoding);
        }
        
        $this->setString($string);
    }

    /**
     * Static constructor used so that the constructor can be called
     * and the needed function invoked on the same line.
     *
     * @param string $string
     * @return BString
     *
     * @example BString::construct( $string )->generate() = returns the string in user format
     */
    public static function construct( $string, $encoding = null )
    {
        return new BString($string, $encoding);
    }

    
    /**
     * Sets the string
     *
     * @param $string
     */
    public function setString($string)
    {
        $this->string = $this->_convert( $string );
    }

    /**
     * Returns the string in the required encoding
     *
     * @param string $string
     * @return string the string in the required encoding
     */
    protected function _convert( $string )
    {
        return $string;
    }

    /**
     * Appends text to the string.
     *
     * Passes through the conversion function to ensure the string is in the correct encoding.
     *
     * @param string $string text to be appended
     */
    public function append( $string )
    {
        $this->string .= $this->_convert( $string ) ;
    }

    /**
     * Replaces one content with another in the current string.
     * Also returns the new string.
     *
     * @param string $search original content to search for
     * @param string $replace new content to substitute
     * @param string returns the new string
     */
    public function replace( $search, $replace )
    {
        $this->string = str_replace($search, $replace, $this->string );
        
        return $this;
    }

    /**
     * Converts the text to lowercase
     *
     * @return BString
     */
    public function toLower()
    {
        $this->string = mb_strtolower( $this->string,$this->getEncoding() );

        return $this;
    }

    /**
     * Converts the text to uppercase
     *
     * @return BString
     */
    public function toUpper()
    {
        $this->string = mb_strtoupper( $this->string ,$this->getEncoding() );

        return $this;
    }

    /**
     * Returns the character at the given index position
     *
     * @param integer $index index of the character to get
     * @return char the requested character
     */
    public function charAt($index)
    {
        return $this->string[ $index ];
    }
   
    /**
     * Gets the string
     *
     * @return string
     */
    public function getString()
    {
    	return $this->string;
    }

    /**
     * Sets the encoding
     *
     * @param $encoding
     */
    public function setEncoding($encoding)
    {
    	$this->encoding = $encoding;
    }

    /**
     * Gets the encoding
     *
     * @return string
     */
    public function getEncoding()
    {
    	return $this->encoding;
    }

    /**
     * Checks if the string is UTF8
     *
     * @param string the text to check
     * @return boolean
     */
    public static function isUTF8( $string )
    {
        //return mb_detect_encoding($this->getString(), 'UTF-8', true);
        //return iconv('ISO-8859-1', 'UTF-8', iconv('UTF-8', 'ISO-8859-1', $string ) ) == $string;
        return BString::checkEncoding($string, 'UTF-8');
    }

    /**
     * Checks if the string matches the given encoding
     *
     * @param string $string
     * @param string $enc
     * @return boolean
     */
    public static function checkEncoding( $string , $enc  )
    {
        return BString::detectEncoding( $string ) == $enc;
    }

    /**
     * Returns the encoding of the string
     *
     * @param string $string
     * @return string the encoding of the string
     */
    public static function detectEncoding($string)
    {
        $encList = array('UTF-8','ISO-8859-1');

        if ( is_array( $encList ) )
        {
            foreach ( $encList as $line => $enc)
            {
                if ( $enc == 'UTF-8' )
                {
                    if ( iconv('ISO-8859-1', 'UTF-8', iconv('UTF-8', 'ISO-8859-1', $string ) ) === $string )
                    {
                        return 'UTF-8';
                    }
                }
                else
                {
                    if ( iconv('UTF-8', $enc, iconv( $enc, 'UTF-8', $string ) ) === $string )
                    {
                        return $enc;
                    }
                }
            }
        }
    }

    /**
     * Returns the length of the string
     *
     * @return int length of the string
     */
    public function length()
    {
        return mb_strlen( $this->getString() , $this->getEncoding() );
    }

    /**
     * Removes whitespace from the beginning and end of the text
     *
     * @return BString
     */
    public function trim()
    {
        $this->string = trim($this->string);
        return $this;
    }

    /**
     * Converts the string to ASCII characters.
     * Removes accents and other specific characters.
     *
     * @return BString
     */
    public function toASCII()
    {
        $this->trim(); //remove whitespace
        $content = $this->string;
        $content = preg_replace("/[ÁÀÂÃÄáàâãä]/i", "A", $content);
        $content = preg_replace("/[ÉÈÊËéèêë]/i",   "E", $content);
        $content = preg_replace("/[ÍÌÎÏíìîï]/i",   "I", $content);
        $content = preg_replace("/[ÓÒÔÕÖóòôõö]/i", "O", $content);
        $content = preg_replace("/[ÚÙÛÜúùûü]/i",   "U", $content);
        $content = preg_replace("/[Ññ]/i",         "N", $content);
        $content = preg_replace("/[Çç]/i",         "C", $content);
        $content = preg_replace("/\+/i",           "",  $content);

        $this->string = $content;

        $this->toUpper(); //convert everything to uppercase

        return $this;
    }

    /**
     * Cuts the string from a starting point, optionally considering a length
     *
     * @param integer $start starting position
     * @param integer $length number of characters to cut / length
     * @return BString
     */
    public function sub($start, $length)
    {
        $this->string = mb_substr( $this->string, $start, $length, $this->getEncoding() );

        return $this;
    }
    
    /**
     * Explodes the string returning an array
     *
     * @param string $delimiter delimiter
     * @return array array with the exploded string
     */
    public function explode( $delimiter )
    {
        return explode( $delimiter, $this->string );
    }
         
    /**
     * Function called automatically by PHP when it needs to convert the object to String
     *
     * @return string the data in user format
     */
    public function __toString()
    {
        //$this->string placed between "" to ensure the string is really a string
        return $this->string;
    }
    
    /**
     * Function that miolo calls automatically, converting the object to string
     *
     * @return string the data in user format
     */
    public function generate()
    {
        return $this->getString();
    }
}
?>
