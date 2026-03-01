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
use PHPMailer\PHPMailer\PHPMailer;

$MIOLO->uses( "/classes/bString.php", 'base');

class bEmail extends PHPMailer
{
    /**
     * @var boolean $log Defines whether logging is necessary.
     */
    private $log = FALSE;
    
    /**
     * @var string $logFile Full log file path.
     */
    private $logFile;

    function __construct($host, $port, $senderAddress, $senderName, $authRequired, $username, $password, $contentType)
    {
        parent::__construct();
        $this->setLanguage('br');
        $this->setEncoding('UTF-8');
        
        // Defines that SMTP will be used.
        $this->isSMTP();

        // Sets the parameter values.
        $this->setHost($host);
        $this->setPort($port);
        $this->setSender($senderAddress);
        $this->setSenderName($senderName);
        $this->setAuthRequired($authRequired);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setContentType($contentType);
        
        // Increases the connection timeout with the email service.
        $this->Timeout = 60; 
    }

    /**
     * Sets the email encoding.
     *
     * @param string $encoding Email encoding.
     */
    public function setEncoding( $encoding )
    {
        $this->CharSet = $encoding;
    }

    /**
     * Returns the email encoding.
     * 
     * @return string Encoding defined in the email.
     */
    public function getEncoding( )
    {
        return $this->CharSet;
    }

    /**
     * Sets the full log path
     */
    private function setFullLogPath()
    {
        if ( strlen( $this->logFile ) )
        {
            return;
        }

        // Checks log configuration.
        if ( MUTIL::getBooleanValue( MAIL_LOG_GENERATE ) )
        {
            return;
        }

        $MIOLO = MIOLO::getInstance();
        $this->logFile  = $MIOLO->getConf('home.logs')."/";
        $this->logFile .= defined('MAIL_LOG_FILE_NAME') ? MAIL_LOG_FILE_NAME : "mail.log";
    }

    /**
     * This method increments the recipients array.
     *
     * @param string $address Recipients separated by comma.
     */
    public function addRecipient($address)
    {
        // If @ is not found, do not add,
        if ( !preg_match( "/@/", $address ) )
        {
            return;
        }

        // If separated by commas.
        if ( preg_match("/,/", $address ) )
        {
            $recipients = explode(",", $address);
            
            foreach($recipients as $recipientAddress)
            {
                parent::addAddress($recipientAddress);
            }
        }
        else
        {
            parent::addAddress( trim( $address ) );
        }
    }
    
    /**
     * Sets the recipient address.
     *
     * @param string $address Recipient address.
     */
    public function setAddress($address)
    {
        // Clears all previous recipients.
        $this->ClearAddresses();
        
        // Adds the recipient address.
        $this->addRecipient($address);
    }
    
    /**
     * Returns the email recipients.
     *
     * @return array Array with email addresses.
     */
    public function getAddresses()
    {
        return array_keys( $this->all_recipients );
    }

    /**
     * Adds attachment. Multiple files can be separated by comma.
     *
     * @param string Full path of the file(s).
     */
    public function attachFile($fullFilePath)
    {
        $fullFilePath = trim($fullFilePath);

        // Supports multiple files separated by comma.
        if ( preg_match( "/,/", $fullFilePath ) )
        {
            $files = explode(",", $fullFilePath);

            foreach($files as $file)
            {
                parent::addAttachment($file);
            }
        }
        else
        {
            if ( file_exists( $fullFilePath ) )
            {
                parent::addAttachment( $fullFilePath );
            }
        }
    }
    
    /**
     * Gets the attachments.
     *
     * @return array Array with full paths of attached files.
     */
    public function getAttachments()
    {
        return $this->attachment;
    }

    /**
     * Sets the email content.
     *
     * @param string $content Email content.
     */
    public function setContent($content)
    {
        $this->Body = new BString($content);
    }
    
    /**
     * Gets the email content.
     *
     * @return string Email content.
     */
    public function getContent()
    {
        if ($this->isHtml())
        {
            $this->Body->replace("\n", "<br>");
        }
        
        return $this->Body->getString();
    }

    /**
     * Sets the email subject.
     *
     * @param string $subject Email subject.
     */
    public function setSubject($subject)
    {
        $subject = str_replace('  ', ' ', $subject);
        $this->Subject = new BString($subject);
    }
    
    /**
     * Gets the email subject.
     *
     * @return string Email subject.
     */
    public function getSubject()
    {
        return $this->Subject->getString();
    }

    /**
     * Sets the authentication user.
     *
     * @param string $username Authentication user.
     */
    public function setUsername($username)
    {
        $username = new BString($username);
        $username->replace(array("\n", "\t", "\r"), "");
        $username->trim();
        $this->Username = $username;
    }
    
    /**
     * Gets the user used for authentication.
     *
     * @return string User used.
     */
    public function getUsername()
    {
        return $this->Username->getString();
    }

    /**
     * Sets the authentication password.
     *
     * @param string $password Password required for authentication.
     */
    public function setPassword($password)
    {
        $this->Password = new BString($password);
    }
    
     /**
     * Gets the authentication password.
     *
     * @return string Authentication password.
     */
    public function getPassword()
    {
        return $this->Password->getString();
    }

    /**
     * Sets the server address.
     *
     * @param string $host Server address.
     */
    public function setHost($host)
    {
        $this->Host = $host;
    }
    
    /**
     * Gets the host/server used for sending the email.
     *
     * @return string Server address.
     */
    public function getHost()
    {
        return $this->Host;
    }

    /**
     * Sets the connection port.
     *
     * @param Integer $port Port number.
     */
    public function setPort($port)
    {
        $this->Port = $port;
    }
    
    /**
     * Gets the port used for the connection.
     *
     * @return Integer Port number.
     */
    public function getPort()
    {
        return $this->Port;
    }

    /**
     * Sets the email sender.
     *
     * @param string $sender Email sender.
     */
    public function setSender($sender)
    {
        $sender = new BString($sender);
        $sender->replace(array("\n", "\t", "\r"), "");
        $sender->trim();
        $this->From = $sender;
    }

    /**
     * Gets the sender address.
     *
     * @return string Sender address.
     */
    public function getSender()
    {
        return $this->From->getString();
    }
    
    /**
     * Sets the sender name.
     *
     * @param string $senderName Sender name.
     */
    public function setSenderName($senderName)
    {
        $this->FromName = new BString($senderName);
    }
    
    /**
     * Gets the sender name.
     *
     * @return string Sender name.
     */
    public function getSenderName()
    {
        return $this->FromName->getString();
    }
    
    /**
     *
     * Sets whether the server connection method is authenticated or not
     *
     * @param boolean $authentication If true, uses authentication when sending email.
     */
    public function setAuthRequired($authentication)
    {
        $this->SMTPAuth = $authentication;
    }
    
    /**
     * Gets the need to use authentication for sending email.
     *
     * @return boolean Returns true if authentication is required.
     */
    public function getAuthRequired()
    {
        return $this->SMTPAuth;
    }

    /**
     * Sets the content type to be sent (HTML|TEXT).
     *
     * @param boolean $html Defines whether email will be in HTML format.
     */
    public function setHtmlFormat($html=TRUE)
    {
        $this->IsHTML($html);
    }
    
    /**
     * returns whether the text type is html (isHTML true|false)
     *
     * @return (Boolean)
     */
    public function isHtml()
    {
        return $this->ContentType == 'text/html';
    }

    /**
     * Defines the content language.
     *
     * @param string $type Email content type.
     */
    public function setContentType($type = 'html')
    {
        switch ($type)
        {
            default:
                $this->setHtmlFormat(TRUE);
        }
    }

    /**
     * Gets the email content type.
     * 
     * @return Content type.
     */
    public function getContentType()
    {
        return $this->ContentType;
    }

    /**
     * Sends email.
     *
     * @return boolean Returns true if the email was sent.
     */
    public function send()
    {
        $sent = parent::send();
        $this->writeLog($sent);
        
        return $sent;
    }

    /**
     * Writes the log file.
     *
     * @param boolean Email sending result.
     */
    private function writeLog($result)
    {
        $this->setFullLogPath();

        if(!strlen($this->logFile))
        {
            return;
        }

        $recordSeparator    = "+----------------------------------------------------------------+";

        $content = new BString("\n{$recordSeparator}\n");
        $content.= ($result) ? "E-mail foi enviado com sucesso!\n" : "Não foi possível enviar o e-mail.\n";
        $content.= "Destino: '". (!is_null($this->getAddresses()) ? implode(",\n\t", $this->getAddresses()) : "null") ."'\n";
        $content.= "Data/Hora: '". date("d/m/Y H:i:s") ."'\n";
        $content.= "ContentType: '". $this->ContentType ."'\n";
        $content.= "Authenticate: '". $this->getAuthRequired() ."'\n";
        $content.= "Host: '". $this->getHost() .":". $this->getPort() ."'\n";
        $content.= "User: '". $this->getUsername() ."'\n";
        $content.= "Password: '". $this->getPassword() ."'\n";
        $content.= "From: '". $this->getSender() ."'\n";
        $content.= "From Name: '". $this->getSenderName() ."'\n";
        $content.= "Subject: '". $this->getSubject() ."'\n";
        $content.= "Content: '". $this->getContent() ."'\n";
        
        if(!$result)
        {
            $content.=  "\n". $this->ErrorInfo ."\n";
        }

        file_put_contents($this->logFile, $content, FILE_APPEND);
    }
    
    /**
     * Sets the SMTPSecure variable
     * 
     * @param type $SMTPSecure
     */
    public function setSmtpSecure($SMTPSecure)
    {
        $this->SMTPSecure = $SMTPSecure;
    }
}
