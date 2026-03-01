<?php
// +-----------------------------------------------------------------+
// | MIOLO - Miolo Development Team - UNIVATES Centro Universitário  |
// +-----------------------------------------------------------------+
// | Copyleft (l) 2001 UNIVATES, Lajeado/RS - Brasil                 |
// +-----------------------------------------------------------------+
// | Licensed under GPL: see COPYING.TXT or FSF at www.fsf.org for   |
// |                     further details                             |
// |                                                                 |
// | Site: http://miolo.codigoaberto.org.br                          |
// | E-mail: vgartner@univates.br                                    |
// |         ts@interact2000.com.br                                  |
// +-----------------------------------------------------------------+
// | Abstract: This file contains utils functions                    |
// |                                                                 |
// | Created: 2001/08/14 Thomas Spriestersbach                       |
// |                     Vilson Cristiano Gärtner,                   |
// |                                                                 |
// | History: Initial Revision                                       |
// +-----------------------------------------------------------------+

/**
 * Class for decompressing zip files.
 * This class is used to decompress .zip files.
 *
 * Requires: PHP zip extension: http://pecl.php.net/packages/zip
 * Installation:
 *             - download the package file
 *             - decompress the file
 *             $ phpize5 (inside the created directory)
 *             $ ./configure
 *             $ make
 *             $ make install (as root user)
 *             - add to php.ini:
 *               extension=zip.so
 *             - restart apache
 *
 * For more information, see: http://php.net/manual/en/install.pecl.phpize.php
 */
class MZip
{
    public static function unzip($file, $dir)
    {

        $zip = new ZipArchive();

        $zip->open("$file");

        $files = array(substr($file,0,-4), $dir);

        if ( ! $zip->extractTo($dir) )
        {
            echo "Error!\n";
            echo $zip->status . "\n";
            echo $zip->statusSys . "\n";

        }

        $zip->close();
    }
}
