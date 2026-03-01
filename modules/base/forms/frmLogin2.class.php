<?php

/**
 * Authentication form.
 *
 * @author Daniel Hartmann [daniel@solis.coop.br]
 *
 * \b Maintainers: \n
 * Armando Taffarel Neto [taffarel@solis.coop.br]
 * Daniel Hartmann [daniel@solis.coop.br]
 *
 * @since
 * Creation date 2012/06/20
 *
 * \b Organization: \n
 * SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b Copyright: \n
 * Copyright (c) 2012 SOLIS - Cooperativa de Soluções Livres \n
 *
 * \b License: \n
 * Licensed under GPLv2 (for further details read the COPYING file or http://www.gnu.org/licenses/gpl.html)
 */

$MIOLO->uses('classes/prtUsuario.class.php', 'portal');

class frmLogin extends MForm
{
    public $auth;

    public function frmLogin()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        if ( MIOLO::_REQUEST('return_to') == 'AVALIACAO' )
        {
            $tituloLogin = SAGU::getUnitParameter('AVINST', 'TITULO_LOGIN_DA_AVALIACAO_INSTITUCIONAL', SAGU::getParameter('BASIC', 'DEFAULT_UNIT_ID'));

            $label = new MLabel($tituloLogin);

            $label->addStyle('font-weight', 'bold');
            $label->addStyle('font-size', '20px');
            $label->addStyle('margin-left', '12px');
            $label->addStyle('height', '60px');
            $label->addStyle('color', '#1F72BF');
        }
        else
        {
            //$tituloLogin = SAGU::getUnitParameter('PORTAL', 'TITULO_LOGIN_DO_PORTAL', SAGU::getParameter('BASIC', 'DEFAULT_UNIT_ID'));

            //$label = new MLabel($tituloLogin);

            //$label->setId("tituloLogin");

        }
        
        parent::__construct($label);
        
        //$this->setClass('frmLogin');
        $this->setClass('hold-transition login-page');

        //$this->setIcon($this->manager->getUI()->getImage('admin', 'login-16x16.png'));
        if ( $this->page->isPostBack() )
        {
            $this->eventHandler();
        }
    }

    public function createFields()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();

        $temaLTE = $this->manager->getConf('theme.main');



        if ( MUtil::getBooleanValue(SAGU::getParameter('PORTAL', 'HABILITA_MASCARA_CPF_NO_LOGIN_PORTAL')) )
        {
            $this->page->addJsCode('
                 function mascara(o,f){
                     v_obj=o
                     v_fun=f
                     setTimeout("execmascara()",1)
                 }

                 function execmascara(){
                     v_obj.value=v_fun(v_obj.value)
                 }
 
                function cpf_mask(v){
                     v=v.replace(/\D/g,"")                 //Remove everything that is not a digit
                     v=v.replace(/(\d{3})(\d)/,"$1.$2")    //Places a dot between the third and fourth digits
                     v=v.replace(/(\d{3})(\d)/,"$1.$2")    //Places a dot between the seventh and eighth digits
                     v=v.replace(/(\d{3})(\d)/,"$1-$2")   //Places a dash between the eleventh and twelfth digits
                     return v
                }

                function returnNumbers(str)
                {
                    var rs=\'\';

                    for ( var i=0; i<str.length; i++)
                    {
                        var chr = str.charAt(i);
                        if ( isDigit(chr) )
                        {
                            rs += chr;
                        }
                    }

                    return rs;
                }
                
                function isDigit(chr)
                {
                    return "0123456789".indexOf(chr) != -1;
                }

                function MIOLO_Validate_Check_CPF(value)
                {
                    var i;
                    var c;

                    var x = 0;
                    var soma = 0;
                    var dig1 = 0;
                    var dig2 = 0;
                    var texto = "";
                    var numcpf1="";
                    var numcpf = "";

                    var numcpf = returnNumbers(value);

                    if ( ( numcpf == \'00000000000\') ||
                         ( numcpf == \'11111111111\') ||
                         ( numcpf == \'22222222222\') ||
                         ( numcpf == \'33333333333\') ||
                         ( numcpf == \'44444444444\') ||
                         ( numcpf == \'55555555555\') ||
                         ( numcpf == \'66666666666\') ||
                         ( numcpf == \'77777777777\') ||
                         ( numcpf == \'88888888888\') ||
                         ( numcpf == \'99999999999\')  )
                    {                    
                        return false;
                    }

                /*    for (i = 0; i < value.length; i++) 
                    {
                        c = value.substring(i,i+1);
                        if ( isDigit(c) )
                        {
                            numcpf = numcpf + c;
                        }
                    }
                */    
                    if ( numcpf.length != 11 ) 
                    {
                        return false;
                    }

                    len = numcpf.length;x = len -1;

                    for ( var i=0; i <= len - 3; i++ ) 
                    {
                        y     = numcpf.substring(i,i+1);
                        soma  = soma + ( y * x);
                        x     = x - 1;
                        texto = texto + y;
                    }

                    dig1 = 11 - (soma % 11);
                    if (dig1 == 10) 
                    {
                        dig1 = 0 ;
                    }

                    if (dig1 == 11) 
                    {
                        dig1 = 0 ;
                    }

                    numcpf1 = numcpf.substring(0,len - 2) + dig1 ;
                    x = 11;soma = 0;
                    for (var i=0; i <= len - 2; i++) 
                    {
                        soma = soma + (numcpf1.substring(i,i+1) * x);
                        x = x - 1;
                    }

                    dig2 = 11 - (soma % 11);

                    if (dig2 == 10)
                    {
                        dig2 = 0;
                    }
                    if (dig2 == 11) 
                    {
                        dig2 = 0;
                    }
                    if ( (dig1 + "" + dig2) == numcpf.substring(len,len-2) ) 
                    {
                        return true;
                    }

                    return false;
                }

                /**
                * Validates onBlur whether the CPF is valid or not,
                * in case one is typed.
                *
                * param input element.
                */
               function validateOnBlurCPF(element)
               {
                   var cpf = element.value;
                   var len = element.value.length;

                   if ( len > 0 )
                   {
                        if ( len == 11 ) // Without mask
                        {
                            if ( parseInt(element.value) )
                            {
                                var maskcpf = cpf.substring(3,0) + "." + cpf.substring(3,6) + "." + cpf.substring(6,9) + "-" + cpf.substring(9,11);

                                if ( MIOLO_Validate_Check_CPF(maskcpf) )
                                {
                                    element.value = maskcpf;
                                }
                                else
                                {
                                     alert("O CPF informado não é válido.");
                                     element.value = "";
                                }
                            }
                            else
                            {
                                 alert("O CPF informado não é válido.");
                                 element.value = "";
                            }
                        }
                        else if ( len == 14 ) // With mask.
                        {
                            if ( cpf.replace("-", "") )
                            {
                                cpf = cpf.replace("-", "");
                                var splt = cpf.split(".");

                                if ( splt.length == 3 )
                                {
                                    if ( !MIOLO_Validate_Check_CPF(element.value) )
                                    {
                                        alert("CPF Inválido");
                                        element.value = "";
                                    }
                                }
                            }
                        }
                        else
                        {
                             alert("O CPF informado não é válido.");
                             element.value = "";
                        }
                    }                    
               }
            ');
        }
        
        $fields[] = $divErro = new MDiv('divErroConexao', '','mMessage mMessage Error');
        $divErro->addAttribute('style', 'display:none');
        
        // Checks if the preference is enabled to verify and block access if the browser is not approved
        $validaNavegador = SAGU::getParameter('BASIC', 'VALIDACAO_NAVEGADORES_HOMOLOGADOS');
        $alerta = SAGU::getParameter('BASIC', 'MENSAGEM_NAVEGADORES_NAO_HOMOLOGADOS');
        
        $browser = MUtil::getBrowser();  
        
        if ( $browser != 'Firefox' && $browser != 'Google Chrome' && $browser != 'Android' && $browser != 'iPhone' && $validaNavegador == 2 )
        {
            $MIOLO->error(_M($alerta));
        }
        else if ( $browser != 'Firefox' && $browser != 'Google Chrome' && $browser != 'Android' && $browser != 'iPhone' && $validaNavegador == 1 )
        {
            $fields[] = MMessage::getStaticMessage('infoAlerta', _M($alerta), MMessage::TYPE_INFORMATION);
            $fields[] = new MDiv();
        }

        $this->auth = $this->manager->auth;
        $return_to = $this->getFormValue('return_to', MIOLO::_Request('return_to'));

        if ( !$return_to )
        {
            $return_to_module = $this->manager->getConf('options.common');
            $return_to = $MIOLO->getActionURL($return_to_module, 'main');
        }
        
        $fields[] = MMessage::getMessageContainer();
        
        $imagemLoginPortal = SAGU::getUnitParameter('PORTAL', 'IMAGEM_LOGIN_DA_TELA_DO_PORTAL', SAGU::getParameter('BASIC', 'DEFAULT_UNIT_ID'));

        if(strlen($imagemLoginPortal))
        {
            $img = new MImage('imagemLoginPortal','', $imagemLoginPortal );
            $img->addStyle('max-width', '100%');
            
            $fields['logo'] = new MDiv('', array('',$img));
            $fields['logo']->addStyle('width', '100%');
            $fields['logo']->addStyle('text-align', 'center');
            
//            if ( MIOLO::_REQUEST('return_to') == 'AVALIACAO' )
//            {
//                $fields['logo']->addStyle('padding-top', '8px');
//                $fields['logo']->addStyle('height', '90px');
//            }
        }

        $label = new MLabel(_M('Usuário'));
        $label->setBold();
        $inputs[] = $usuario = new MTextField('uid', $this->auth->login->iduser, $label, 20);
        $usuario->setClass('mTextField form-control textFieldLogin');

        if ( MUtil::getBooleanValue(SAGU::getParameter('PORTAL', 'HABILITA_MASCARA_CPF_NO_LOGIN_PORTAL')) )
        {
            $usuario->addAttribute("onBlur", "validateOnBlurCPF(this);");
            $usuario->addAttribute("onKeyPress", "mascara(this, cpf_mask);");
            $usuario->setJsHint(_M('Digite apenas os números do CPF.'));
        }
        
        $label = new MLabel(_M('Senha'));
        $label->setBold();
        $inputs[] = $senha = new PasswordField('pwd', '', $label, 20);
        $senha->setClass('mTextField form-control textFieldLogin');

        if ( $temaLTE !='adminLTE')
        {
            $usuario->addStyle('border', '1px solid');
            $usuario->addStyle('font-size', '16px');
            $usuario->addStyle('height', '26px');
            $usuario->addStyle('width', '100%');

            $senha->addStyle('border', '1px solid');
            $senha->addStyle('font-size', '16px');
            $senha->addStyle('height', '26px');
            $senha->addStyle('width', '100%');
        }
        if ( sMultiUnidade::estaHabilitada() )
        {   
            $unit = sMultiUnidade::obterCombo(null, false, true, 'm-caption-required', true);
            //$unit->setIsRequired(true);
            $inputs[] = $unit;            
            $validators[] = new MRequiredValidator('unitId', _M("Unidade"));
        }
        $fields[] = $form = new MFormContainer('mioloFrmLogin', $inputs);
        $form->addStyle('float','center');

        $fields[] = new TextLabel('username', $this->auth->login->user, _M('Nome', $module), 40);
        $fields[] = new HiddenField('return_to', $return_to);

        $fields[] = new MSeparator();
        if ( !$this->isAuthenticated() )
        {
            $buttons[] = $btnEntrar = new MButton('btnLogin', _M('Entrar', $module), null);
            if ( $temaLTE !='adminLTE')
            {

                $btnEntrar->setClass('btnLogin');
                $btnEntrar->addStyle('height', '50px');
                $btnEntrar->addStyle('float', 'center');
                $btnEntrar->addStyle('padding', '2px 8px');
                $btnEntrar->addStyle('vertical-align', 'top');

            }
            $buttons[] = new MSpacer();
            $buttons[] = $btnSenha = new MLinkButton('btnSenha', _M('Esqueci a senha'), MUtil::getAjaxAction('novaSenha'));
            if ( $temaLTE !='adminLTE')
            {
                $btnSenha->setClass('btnLogin');
                $btnSenha->addStyle('height', '50px');
                $btnSenha->addStyle('padding', '2px 8px');
                $btnSenha->addStyle('float', 'center');

            }

        }
        else
        {
            $buttons[] = new FormButton('btnLogout', _M('Sair', $module));
        }

        $fields[] = MUtil::centralizedDiv($buttons);

        if ( $temaLTE =='adminLTE')
        {
            $loginBoxBoby = new MDiv('divMen1', $fields, 'login-box-body');
            $loginBox = new MDiv('divMen2', $loginBoxBoby, 'login-box');
            $holdTransition = new MDiv('divMen3', $loginBox, 'hold-transition login-page');

        }

        if ( $temaLTE =='adminLTE')
        {
            $fields = $holdTransition;
        }
        $this->setFields($fields);
        $this->setShowPostButton(FALSE);

        $this->setFieldAttr('uid', 'readonly', $this->isAuthenticated());
        $this->setFieldAttr('pwd', 'visible', !$this->isAuthenticated());
        if ( $temaLTE !='adminLTE')
        {
            $this->getField('uid')->setClass('mTextUserField mTextField');
            $this->getField('pwd')->setClass('mTextPasswdField mTextField');
        }


        $this->setFieldAttr('username', 'visible', $this->isAuthenticated());

        $this->setFocus('uid');

        // Connect enter event
        $event = MUtil::getAjaxAction('btnLogin_click', NULL);
        $this->page->onload("handleEnterLogin = dojo.connect(dojo.byId('mioloFrmLogin'), 'onkeypress', function (event) { if (event.keyCode==dojo.keys.ENTER) { event.preventDefault(); dojo.disconnect(handleEnterLogin); {$event}; }});");
        
        // Validate authentication hash from webServicesBasic, wsLogin function
        if ( SAGU::validarHashDeAutenticacao() ) 
        {
            $this->btnLogin_click();
        }
    }
    
    public function novaSenha()
    {   
        $cpfLabel = new MLabel( _M('CPF').':');
        $cpfLabel->setWidth(60);
        $cpf = new MTextField('cpf', null);
        $cpf->setWidth(200);
        $cpf->addMask('###.###.###-##');
        $fields[] = new MHContainer('hctCPF', array($cpfLabel, $cpf));
        
        $emailLabel = new MLabel( _M('E-mail').':');
        $emailLabel->setWidth(60);
        $email = new MTextField('email', null);
        $email->setWidth(200);
        $fields[] = new MHContainer('hctEmail', array($emailLabel, $email));
        
        $fields[] = new MDiv(null, "<script>JQuery('#cpf').mask('999.999.999-99');</script>");
        
        $campos[] = $div = new MDiv('', $fields);
        $div->addStyle('width', '400px');
        
        $botoes[] = new MButton('botaoNovaSenha', _M('Solicitar uma nova senha por email'), ':solicitarSenha');
        $botoes[] = new MButton('botaoCancelar', _M('Cancelar', $this->modulo), "dijit.byId('dialogoNovaSenha').hide();");
        $campos[] = MUtil::centralizedDiv($botoes);
        
        $dialog = new MDialog('dialogoNovaSenha', _M('Dados para solicitar uma nova senha'), $campos);
        $dialog->show();
    }
    
    public function solicitarSenha($args=null)
    {
        MDialog::close('dialogoNovaSenha');
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $args = $this->getAjaxData();
        
        $MIOLO->Uses('classes/sendEmail.class', 'basic');
        
        if (!filter_var($args->email, FILTER_VALIDATE_EMAIL)) 
        {
            new MMessageError(_M('E-mail inválido.'));
        }
        
        if ( (strlen($args->cpf)>0) || (strlen($args->email)>0) )
        {
            // Physical person business
            $busPhysicalPerson = new BusinessBasicBusPhysicalPerson();

            // Searches for the person with the provided data
            $filters = new stdClass();
            $filters->content = $args->cpf;
            $filters->email = $args->email;
            
            $busPerson = $MIOLO->getBusiness('basic', 'BusPerson');
            
            $data->cpf = $args->cpf;
            $data->email = $args->email;
            $personData = $busPerson->searchPerson($data);
            
            if(!$personData)
            {
                $data->cpf = null;
                $personData = $busPerson->searchPerson($data);
            }
            
            $personId = $personData[0][0];

            // Checks if the person was found
            if( !is_array($personData) )
            {
                new MMessageError(_M('Não foi encontrado nenhum usuário para os dados informados.<br />Entre em contato com a secretaria acadêmica.'));
            }
            else
            {
                // Person
                $person = $busPhysicalPerson->getPhysicalPerson($personId);

                // Business user
                $busUser = new BusinessAdminBusUser();
                $mioloUser = $busUser->getUserByLogin($person->mioloUserName);

                // Checks if login was found
                if( !strlen($mioloUser->login)>0 )
                {
                    new MMessageError(_M('Não foi encontrado nenhum login para este usuário.<br />Entre em contato com a secretaria acadêmica.'));
                }
                else
                {
                    // Inserts hash into the basResetPassword table
                    $basHashValidate = new BusinessBasicBusHashValidate();

                    $dataHashValidate = new stdClass();
                    $dataHashValidate->idUser = $mioloUser->idUser;
                    $hash = $basHashValidate->insertHashValidate($dataHashValidate);

                    // Business email and company
                    $busEmail = new BusinessBasicBusEmail();
                    $busCompany = new BusinessBasicBusCompany();

                    //TODO email hardcode
                    $dataEmail = $busEmail->getEmail(SAGU::getParameter('basic','EMAIL_ID_CHANGE_PASSWORD'));
                    $dataCompany = $busCompany->getCompany(SAGU::getParameter('BASIC', 'DEFAULT_COMPANY_CONF'));

                    // Gets joomla URL if it exists
                    $urlJoomla = urldecode(MIOLO::_REQUEST('parentUrl'));
                    if( $urlJoomla )
                    {
                        $arrayUrlJoomla = explode('?', $urlJoomla);
                        $hostJoomla = 'http://'. $arrayUrlJoomla[0];
                        $changePassword = '?option=com_changePassword';

                        $data->linkChangePassword = $hostJoomla.$changePassword.'&id='.$hash;
                    }
                    else
                    {
                        if($this->manager->getConf('options.miolo2url'))
                        {
                            $hostSagu = $this->manager->getConf('options.miolo2url').'/index.php';
                        }
                        else
                        {
                            $urlSagu = $MIOLO->getActionURL('basic');
                            $arrayUrlSagu = explode('?',$urlSagu);
                            $hostSagu = $arrayUrlSagu[0];
                        }
                        $changePassword = '?module=admin&action=changePassword';

                        $data->linkChangePassword = $hostSagu.$changePassword.'&id='.$hash;
                    }

                    // Checks if the person has an email and sends the password change notification
                    if ( strlen($person->email) > 0 )
                    {
                        $tags = array( '$PERSONNAME' => $person->name,
                                       '$PERSONID' => $person->personId.' Login: '.$person->mioloUserName,
                                       '$LINK' => $data->linkChangePassword );

                        //Parameters
                        $from = strtolower($dataEmail->from);
                        $fromName = $dataCompany->acronym;
                        $recipient[$person->name] = strtolower($person->email);
                        $subject = $dataEmail->subject;
                        $body = strtr($dataEmail->body, $tags);

                        $mail = new sendEmail($from, $fromName, $recipient, $subject, $body, array());
                        $send = $mail->sendEmail();

                        $msgOk = _M('Um email para a recuperação de senha foi enviado para @1',$module, $person->email);
                        if ( $send )
                        {
                            new MMessageSuccess($msgOk);
                        }
                        else
                        {
                            $busMailServer = new BusinessBasicBusMailServer();
                            $mailServer = $busMailServer->getMailServer();
                            $from = $mailServer->smtpUser;
                            $mail = new sendEmail($from, $fromName, $recipient, $subject, $body, array());
                            $send = $mail->sendEmail();
                            if ( $send )
                            {
                                new MMessageSuccess($msgOk);
                            }
                            else
                            {
                                new MMessageError(_M('Erro com o servidor de email. Entre em contato com o responsável pelo sistema para resolver o problema', $module).'.');
                            }
                        }
                    }
                }
            }
        }
        else
        {
            new MMessageWarning(_M('É necessário informar pelo menos seu CPF ou email',$module).'.');
        }
        
    }
    
    public function generateNewPassword()
    {
        $length = 8; 
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr( str_shuffle( $chars ), 0, $length );
    }

    public function btnLogin_click()
    {
        $MIOLO = MIOLO::getInstance();
        $module = MIOLO::getCurrentModule();
        $url = $MIOLO->getCurrentURL();
        
        $urlErroLogin = NULL;
        
        if ( strlen(MIOLO::_REQUEST('return_to_erro')) )
        {
            $urlErroLogin = MIOLO::_REQUEST('return_to_erro') . '?erroAutenticacao=true';
        }

        $MIOLO->uses('/security/mauthmiolo.class.php');        
        $saguAuth = new MAuthMIOLO();
 
        $this->getData();

        $uid = $this->getFormValue('uid');
        $pwd = $this->getFormValue('pwd');
        $unitId = $this->GetFormValue('unitId');
        
        // Sets data when there is a valid hash
        if ( SAGU::validarHashDeAutenticacao() )
        {
            $userInformation = SAGU::obterDadosDeLoginAPartirDoHash();
            
            $uid = $userInformation->login;
            $pwd = $userInformation->password;
            $unitId = $userInformation->unitId;
        }

	## set pwd in session
	$MIOLO->getSession()->setValue('pwd', $pwd);
        
        $busPerson = $MIOLO->getBusiness('basic', 'BusPerson');
        $person = $busPerson->getPersonByMioloUserName($uid, true);
        $user = $person->personId;

        $MIOLO->logMessage('[LOGIN] Validating login information: ' . $uid);

        // Multi-unit
        if ( sMultiUnidade::estaHabilitada() && !sMultiUnidade::loginTemPermissao($uid, $unitId) && SAGU::allIsFilled($uid) )
        {
            if ( $urlErroLogin )
            {
                $this->page->redirect($urlErroLogin);
            }
            else
            {
                if( !$unitId )
                {
                    return new MMessageWarning(_M('Deve ser selecionado uma unidade.', $module));
                }

                $busUnit = new BusinessBasicBusUnit();
                $desc = $busUnit->getUnit($unitId)->description;

                return new MMessageWarning(_M('O usuário ou a senha ou a unidade está incorreta.', $module));
            }
        }
        
        if ( SAGU::getParameter('BASIC', 'AUTH_METHOD') == 'LDAP' )
        {
            $user = $uid;
        }

        if ( SAGU::authenticate($user, $pwd) )
        {
            $MIOLO->session->set("loginFrom", $module);
            $MIOLO->session->set("senhaADExpirada", DB_FALSE);
            $ldif = SAGU::getParameter('BASIC', 'LDIF_ATUALIZA_SENHA_DO_USUARIO');

            if ( strlen($ldif) > 0 )
            {
                $sAuthLdap = new sAuthLdap();

                if ( $sAuthLdap->verificaSeSenhaDoUsuarioExpirou($uid, $pwd) )
                {
                    $MIOLO->session->set("senhaADExpirada", DB_TRUE);
                }
            }
            
            $busGroup = $MIOLO->getBusiness('base', 'group');
            
            /**
             * Since ldap/ad is not case sensitive, the system should be able to find
             * the user in the system whether entered in lowercase or uppercase. Then use
             * the one found in the system for setting the session.
             */
            $dbuser = $this->manager->GetBusinessMAD('user');
            $dbuser->getByLogin($uid, 'ILIKE');
 
            if ( strlen($dbuser->login) > 0 )
            {
                $uid = $dbuser->login;
            }   
            
            $login = new MLogin($uid, $pwd, null, $person->mioloIdUser);
            $login->setGroups($busGroup->getGroups($uid));
            
            $MIOLO->auth->setLogin($login);

            SAGU::limparCacheDoUsuario();
            
            if ( sMultiUnidade::estaHabilitada() )
            {
                sMultiUnidade::definirUnidadeLogada( $unitId );
            }
            
            $botao = '<a href="#" data-role="button" data-theme="c" data-icon="delete" data-iconpos="notext" class="ui-btn-right" onclick="miolo.doPostBack(\'confirmarSair\',\'\',\'__mainForm\'); return false;"></a>';
            $this->setResponse($botao, 'divBotaoSair');
            
            $return_to = $this->getFormValue('return_to');

            if ( $return_to == 'AVALIACAO' || (substr_count($url, 'module=avinst') > 0 || substr_count($url, 'avaliacao') > 0) )
            {
                $url = $MIOLO->getActionURL('avinst', 'main');
            }
            else
            {
                if ( $return_to )
                {
                    $url = $return_to;
                }
                else
                {
                    $url = $MIOLO->getActionURL('portal', 'main');
                }
            }
            
            if ( strpos($MIOLO->_REQUEST('action'), 'perfil') )
            {
                $url = $MIOLO->getActionURL('portal', 'perfil');
            }
            
            // Checks if there is a password change configuration and redirects to the change screen
            if( BusinessBasicBusConfiguracaoTrocaDeSenha::verificaTrocaDeSenha() == DB_TRUE )
            {
                $this->page->redirect($MIOLO->getActionURL('portal', 'main'));
            }
            elseif (substr_count($url, 'module=avinst') > 0 ) // For the evaluation, it needs to be via javascript. Ticket #45668
            {
                $url = html_entity_decode($url);
                $this->page->onLoad(" window.location = '$url';");
            }
            else
            {
                $this->page->redirect($url);
            }
        }
        else
        {
            if ( $this->auth->errors )
            {
                $err = new MExpandDiv(NULL, _M('O usuário ou a senha está incorreta.') . '<br/>' . $this->auth->errors);
            }
            else
            {
                $err = _M('O usuário ou a senha está incorreta.');
            }

            if ( $urlErroLogin )
            {
                $this->page->redirect($urlErroLogin);
            }
            else
            {
                new MMessageWarning($err);
            }
        }
        
    }

    public function btnLogout_click()
    {
        $MIOLO = MIOLO::getInstance();
        $this->page->redirect($MIOLO->getActionURL($module, 'logout'));
    }

    public function isAuthenticated()
    {
        return $this->auth->isLogged();
    }
}

?>
