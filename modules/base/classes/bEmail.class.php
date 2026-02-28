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

$MIOLO->uses( "/classes/BString.class.php", 'base');

class bEmail extends PHPMailer
{
    /**
     * @var boolean $log Defines whether logging is necessary.
     */
    private $log = FALSE;
    
    /**
     * @var string $arquivoDeLog Full log file path.
     */
    private $arquivoDeLog;

    function __construct($host, $porta, $enderecoRemetente, $nomeRemetente, $necessidadeDeAutenticacao, $usuario, $senha, $tipoDeConteudo)
    {
        parent::__construct();
        $this->setLanguage('br');
        $this->definirCodificacao('UTF-8');
        
        // Defines that SMTP will be used.
        $this->isSMTP();

        // Sets the parameter values.
        $this->definirHost($host);
        $this->definirPorta($porta);
        $this->definirRemetente($enderecoRemetente);
        $this->definirNomeRemetente($nomeRemetente);
        $this->definirNecessidadeAutenticacao($necessidadeDeAutenticacao);
        $this->definirUsuario($usuario);
        $this->definirSenha($senha);
        $this->definirTipoConteudo($tipoDeConteudo);
        
        // Increases the connection timeout with the email service.
        $this->Timeout = 60; 
    }

    /**
     * Sets the email encoding.
     *
     * @param string $codificacao Email encoding.
     */
    public function definirCodificacao( $codificacao )
    {
        $this->CharSet = $codificacao;
    }

    /**
     * Returns the email encoding.
     * 
     * @return string Encoding defined in the email.
     */
    public function obterCodificacao( )
    {
        return $this->CharSet;
    }

    /**
     * Sets the full log path
     */
    private function definirCaminhoCompletoLog()
    {
        if ( strlen( $this->arquivoDeLog ) )
        {
            return;
        }

        // Checks log configuration.
        if ( MUTIL::getBooleanValue( MAIL_LOG_GENERATE ) )
        {
            return;
        }

        $MIOLO = MIOLO::getInstance();
        $this->arquivoDeLog  = $MIOLO->getConf('home.logs')."/";
        $this->arquivoDeLog .= defined('MAIL_LOG_FILE_NAME') ? MAIL_LOG_FILE_NAME : "mail.log";
    }

    /**
     * This method increments the recipients array.
     *
     * @param string $endereco Recipients separated by comma.
     */
    public function adicionarDestinatario($endereco)
    {
        // If @ is not found, do not add,
        if ( !preg_match( "/@/", $endereco ) )
        {
            return;
        }

        // If separated by commas.
        if ( preg_match("/,/", $endereco ) )
        {
            $destinatarios = explode(",", $endereco);
            
            foreach($destinatarios as $enderecoDestinatario)
            {
                parent::addAddress($enderecoDestinatario);
            }
        }
        else
        {
            parent::addAddress( trim( $endereco ) );
        }
    }
    
    /**
     * Sets the recipient address.
     *
     * @param string $endereco Recipient address.
     */
    public function definirEndereco($endereco)
    {
        // Clears all previous recipients.
        $this->ClearAddresses();
        
        // Adds the recipient address.
        $this->adicionarDestinatario($endereco);
    }
    
    /**
     * Returns the email recipients.
     *
     * @return array Array with email addresses.
     */
    public function obterEnderecos()
    {
        return array_keys( $this->all_recipients );
    }

    /**
     * Adds attachment. Multiple files can be separated by comma.
     *
     * @param string Full path of the file(s).
     */
    public function adicionarAnexo($caminhoCompletoArquivo)
    {
        $caminhoCompletoArquivo = trim($caminhoCompletoArquivo);

        // Supports multiple files separated by comma.
        if ( preg_match( "/,/", $caminhoCompletoArquivo ) )
        {
            $arquivos = explode(",", $caminhoCompletoArquivo);

            foreach($arquivos as $arquivo)
            {
                parent::addAttachment($arquivo);
            }
        }
        else
        {
            if ( file_exists( $caminhoCompletoArquivo ) )
            {
                parent::addAttachment( $caminhoCompletoArquivo );
            }
        }
    }
    
    /**
     * Gets the attachments.
     *
     * @return array Array with full paths of attached files.
     */
    public function obterAnexos()
    {
        return $this->attachment;
    }

    /**
     * Sets the email content.
     *
     * @param string $conteudo Email content.
     */
    public function definirConteudo($conteudo)
    {
        $this->Body = new BString($conteudo);
    }
    
    /**
     * Gets the email content.
     *
     * @return string Email content.
     */
    public function obterConteudo()
    {
        if ($this->obterEHtml())
        {
            $this->Body->replace("\n", "<br>");
        }
        
        return $this->Body->getString();
    }

    /**
     * Sets the email subject.
     *
     * @param string $assunto Email subject.
     */
    public function definirAssunto($assunto)
    {
        $assunto = str_replace('  ', ' ', $assunto);
        $this->Subject = new BString($assunto);
    }
    
    /**
     * Gets the email subject.
     *
     * @return string Email subject.
     */
    public function obterAssunto()
    {
        return $this->Subject->getString();
    }

    /**
     * Sets the authentication user.
     *
     * @param string $usuario Authentication user.
     */
    public function definirUsuario($usuario)
    {
        $usuario = new BString($usuario);
        $usuario->replace(array("\n", "\t", "\r"), "");
        $usuario->trim();
        $this->Username = $usuario;
    }
    
    /**
     * Gets the user used for authentication.
     *
     * @return string User used.
     */
    public function obterUsuario()
    {
        return $this->Username->getString();
    }

    /**
     * Sets the authentication password.
     *
     * @param string $senha Password required for authentication.
     */
    public function definirSenha($senha)
    {
        $this->Password = new BString($senha);
    }
    
     /**
     * Gets the authentication password.
     *
     * @return string Authentication password.
     */
    public function obterSenha()
    {
        return $this->Password->getString();
    }

    /**
     * Sets the server address.
     *
     * @param string $host Server address.
     */
    public function definirHost($host)
    {
        $this->Host = $host;
    }
    
    /**
     * Gets the host/server used for sending the email.
     *
     * @return string Server address.
     */
    public function obterHost()
    {
        return $this->Host;
    }

    /**
     * Sets the connection port.
     *
     * @param Integer $porta Port number.
     */
    public function definirPorta($porta)
    {
        $this->Port = $porta;
    }
    
    /**
     * Gets the port used for the connection.
     *
     * @return Integer Port number.
     */
    public function obterPorta()
    {
        return $this->Port;
    }

    /**
     * Sets the email sender.
     *
     * @param string $remetente Email sender.
     */
    public function definirRemetente($remetente)
    {
        $remetente = new BString($remetente);
        $remetente->replace(array("\n", "\t", "\r"), "");
        $remetente->trim();
        $this->From = $remetente;
    }

    /**
     * Gets the sender address.
     *
     * @return string Sender address.
     */
    public function obterRemetente()
    {
        return $this->From->getString();
    }
    
    /**
     * Sets the sender name.
     *
     * @param string $nomeRemetente Sender name.
     */
    public function definirNomeRemetente($nomeRemetente)
    {
        $this->FromName = new BString($nomeRemetente);
    }
    
    /**
     * Gets the sender name.
     *
     * @return string Sender name.
     */
    public function obterNomeRemetente()
    {
        return $this->FromName->getString();
    }
    
    /**
     *
     * Sets whether the server connection method is authenticated or not
     *
     * @param boolean $autenticacao If true, uses authentication when sending email.
     */
    public function definirNecessidadeAutenticacao($autenticacao)
    {
        $this->SMTPAuth = $autenticacao;
    }
    
    /**
     * Gets the need to use authentication for sending email.
     *
     * @return boolean Returns true if authentication is required.
     */
    public function obterNecessidadeAutenticacao()
    {
        return $this->SMTPAuth;
    }

    /**
     * Sets the content type to be sent (HTML|TEXT).
     *
     * @param boolean $html Defines whether email will be in HTML format.
     */
    public function definirEmailFormatoHTML($html=TRUE)
    {
        $this->IsHTML($html);
    }
    
    /**
     * returns whether the text type is html (isHTML true|false)
     *
     * @return (Boolean)
     */
    public function obterEHtml()
    {
        return $this->ContentType == 'text/html';
    }

    /**
     * Defines the content language.
     *
     * @param string $tipo Email content type.
     */
    public function definirTipoConteudo($tipo = 'html')
    {
        switch ($tipo)
        {
            default:
                $this->definirEmailFormatoHTML(TRUE);
        }
    }

    /**
     * Gets the email content type.
     * 
     * @return Content type.
     */
    public function obterTipoConteudo()
    {
        return $this->ContentType;
    }

    /**
     * Sends email.
     *
     * @return boolean Returns true if the email was sent.
     */
    public function enviar()
    {
        $enviou = parent::send();
        $this->gravarLog($enviou);
        
        return $enviou;
    }

    /**
     * Writes the log file.
     *
     * @param boolean Email sending result.
     */
    private function gravarLog($resultado)
    {
        $this->definirCaminhoCompletoLog();

        if(!strlen($this->arquivoDeLog))
        {
            return;
        }

        $recordSeparator    = "+----------------------------------------------------------------+";

        $content = new BString("\n{$recordSeparator}\n");
        $content.= ($result) ? "E-mail foi enviado com sucesso!\n" : "Não foi possível enviar o e-mail.\n";
        $content.= "Destino: '". (!is_null($this->obterEnderecos()) ? implode(",\n\t", $this->obterEnderecos()) : "null") ."'\n";
        $content.= "Data/Hora: '". date("d/m/Y H:i:s") ."'\n";
        $content.= "ContentType: '". $this->ContentType ."'\n";
        $content.= "Authenticate: '". $this->obterNecessidadeAutenticacao() ."'\n";
        $content.= "Host: '". $this->obterHost() .":". $this->obterPorta() ."'\n";
        $content.= "User: '". $this->obterUsuario() ."'\n";
        $content.= "Password: '". $this->obterSenha() ."'\n";
        $content.= "From: '". $this->obterRemetente() ."'\n";
        $content.= "From Name: '". $this->obterNomeRemetente() ."'\n";
        $content.= "Subject: '". $this->obterAssunto() ."'\n";
        $content.= "Content: '". $this->obterConteudo() ."'\n";
        
        if(!$result)
        {
            $content.=  "\n". $this->ErrorInfo ."\n";
        }

        file_put_contents($this->arquivoDeLog, $content, FILE_APPEND);
    }
    
    /**
     * Sets the SMTPSecure variable
     * 
     * @param type $SMTPSecure
     */
    public function definirSMTPSecure($SMTPSecure)
    {
        $this->SMTPSecure = $SMTPSecure;
    }
}

?>