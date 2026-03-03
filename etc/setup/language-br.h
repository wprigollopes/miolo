/*************************************************
*                                                * 
*          MIOLO Installation Program            *
*                                                *
*    Author: Vilson Cristiano Gartner  -         *
*            MIOLO Development Coordinator       *
*    E-mail: vgartner@univates.br                *
*                                                *
*    Date: August/2002                           *
*                                                *
*    $Id: language.h,v 1.2 2005/04/03 15:51:48 ematos Exp $
*                                                *
*************************************************/

// MIOLO Default Language: Portuguese
#define LANGUAGE "pt_BR"

// Wizard
#define CANCEL "Cancelar"
#define NEXT "PrÃ³ximo >>"
#define BACK "<< Anterior"
#define FINISH "Finalizar"
#define INSTALL "Instalar"

// Page 1
#define WELCOME "Bem Vindo"
#define PAGE1_INFO "\n\n       PROGRAMA DE INSTALAÃÃO DO MIOLO\n\n\n\n\n\n                        MIOLO versÃ£o 1.0 RC4\n\n\n\n\n\n\n\nAutor: Vilson Cristiano GÃ¤rtner - vgartner@univates.br\n\nhttp://miolo.codigolivre.org.br"

// Page 2
#define FILE_PATH "LocalizaÃ§Ã£o dos Arquivos"
#define PAGE2_INFO  "\n Informe as configuraÃ§Ãµes \n de localizaÃ§Ã£o dos arquivos.  \n\n Todos os campos devem ser  \n informados.  \n Por padrÃ£o os arquivo sÃ£o \n instalados nesses diretÃ³rios. \n\n
 ObservaÃ§Ã£o: Ã aconselhÃ¡-\n vel que vocÃª efetue a insta-\n laÃ§Ã£o utilizando o usuÃ¡rio\n root. TambÃ©m Ã© possÃ­vel uti- \n lizar outro usuÃ¡rio, mas lem-\n bre que serÃ¡ necessÃ¡rio ter\n permissÃ£o de gravaÃ§Ã£o nos\n diretÃ³rios."
#define LBL_MODULES " MÃ³dulos: "
#define LBL_THEMES " Temas: "
#define LBL_URL_THEMES " URL Temas: "

// Page 3
#define DB_SETTINGS "ConfiguraÃ§Ãµes de Base de Dados"
#define PAGE3_INFO "\n Informe as configuraÃ§Ãµes  \n de Base de Dados.  \n\n Informe o tipo de base, o \n nome da base, usuÃ¡rio e\n senha para acessÃ¡-la.\n\n O ideal Ã© que vocÃª mante-\n nha essas duas configura-\n Ã§Ãµes iguais.\n\n A configuraÃ§Ã£o common\n mantÃ©m as tabelas:\n cmn_users e cmn_access\n utilizadas pelo MIOLO pa-\n ra controle de usuÃ¡rios  e\n senhas."
#define BASE_TYPE " Tipo de Base: "
#define HOST_IP " IP Host: "
#define BASE_NAME " Nome Base: "  
#define BASE_USER " UsuÃ¡rio: "
#define BASE_PASSWD " Senha: "

// Page 4
#define LOGIN_SETTINGS "ConfiguraÃ§Ãµes de Login"
#define PAGE4_INFO "\n Informe as configuraÃ§Ãµes\n para o controle de Login\n no MIOLO.\n\n Para obter maiores explica-\n Ã§Ãµes, utilize a ajuda sensi-\n tiva ao campo: \n -clique sobre o botÃ£o com\n o ponto de interrogaÃ§Ã£o e\n cursor do mouse e em se-\n guida sobre o campo."
 
#define ALWAYS_CHECK_LOGIN "Sempre Ã© necessÃ¡rio efetuar Login"
#define MIOLO_CONTROLS_LOGIN "O Login Ã© controlado pelo MIOLO e nÃ£o pelo Banco de Dados"
#define AUTO_LOGIN "Utilizar um Login AutomÃ¡tico"
#define AUTO_LOGIN_ID " ID Login Auto: "
#define AUTO_LOGIN_PASS " Senha Login Auto: "
#define USER_NAME " Nome do UsuÃ¡rio: "

// Page4a
#define INSTALL_OPTIONS "OpÃ§Ãµes de InstalaÃ§Ã£o"
#define PAGE4A_INFO "\n Selecione quais opÃ§Ãµes\n deseja instalar.\n\n Se alguma opÃ§Ã£o estiver\n desabilitada, indica que a\n opÃ§Ã£o nÃ£o acompanha\n o instalador. Isso po-\n de ocorrer em caso de\n atualizaÃ§Ãµes (quando nÃ£o\n Ã© necessÃ¡rio instalar todos\n os arquivos) ou em caso do\n arquivo nÃ£o estar no diretÃ³-\n rio do instalador.\n\n Importante: os arquivos\n existentes serÃ£o sobrescritos por isso Ã© aconselhÃ¡vel\n manter uma cÃ³pia dos ar-\n quivos atuais."
#define INSTALL_MIOLO_CLASSES "Instalar classes do MIOLO"
#define INSTALL_COMMON "Instalar MÃ³dulo Common (Login e Tela/Menu Principal)"
#define INSTALL_EXAMPLES "Instalar MÃ³dulos de Exemplos e Tutorial"
#define INSTALL_THEMES "Instalar Temas"
#define CREATE_CONF_FILE "Criar arquivo de configuraÃ§Ã£o: miolo.conf"
#define SHOW_APACHE_EXAMPLE "Mostrar sugestÃ£o de VirtualHost para Apache"

// Page 5
#define APACHE_EXAMPLE "ConfiguraÃ§Ã£o do Apache" 
#define PAGE5_INFO "\n  ConfiguraÃ§Ã£o do Apache.  \n\n  De acordo com as configu-\n raÃ§Ãµes indicadas anterior-\n mente, apresentamos aqui\n um exemplo de Virtual Host\n que vocÃª poderia utilizar\n para o Apache.\n\n  Dica: VocÃª pode copiar e \n colar o exemplo.\n\n  ObservaÃ§Ã£o: \n se jÃ¡ existir uma configura-\n Ã§Ã£o para este domÃ­nio, nÃ£o \n Ã© necessÃ¡rio criar outra."
#define SUGESTION_APACHE "SugestÃ£o de Virtual Host para Apache: \n"

// Page 6
#define WAITING_INSTALL_TO_START "AGUARDANDO INÃCIO DA INSTALAÃÃO DO MIOLO: "
#define PAGE6_INFO "\n   O instalador do MIOLO\n estÃ¡ pronto para iniciar\n a InstalaÃ§Ã£o. \n\n Pressione o botÃ£o para\n iniciar o processo...\n"
#define INSTALL_PROCESS "Processo de InstalaÃ§Ã£o"
#define BTN_START_INSTALL "Iniciar InstalaÃ§Ã£o do MIOLO"

// Methods
#define SELE_DIR "Selecione o DiretÃ³rio..."
#define CREATING_DIRS "Criando diretÃ³rios..."
#define INSTALLING_MIOLO_FILES "Instalando arquivos do MIOLO..."
#define MSG_MIOLO_FILE_NOT_FOUND "DiretÃ³rio: miolo (classes do MIOLO) nÃ£o encontrado."
#define MSG_LOCALE_FILE_NOT_FOUND "DiretÃ³rio: locale (traduÃ§Ãµes) nÃ£o encontrado."
#define INSTALLING_HTDOCS "Instalando arquivos htdocs..."
#define MSG_HTDOCS_FILE_NOT_FOUND "DiretÃ³rio: html (arquivo necessÃ¡rio pelo MIOLO) nÃ£o encontrado."
#define INSTALLING_COMMON "Instalando arquivos mÃ³dulo common..."
#define MSG_COMMON_FILE_NOT_FOUND "DiretÃ³rio: common (Login, Menu/Tela Principal) nÃ£o encontrado."
#define INSTALLING_EXAMPLES "Instalando arquivos mÃ³dulo exemplos..."
#define MSG_EXAMPLES_FILE_NOT_FOUND "DiretÃ³rio: sample (Exemplos de mÃ³dulos/programas) nÃ£o encontrado."
#define INSTALLING_THEMES "Instalando Temas do MIOLO..."
#define MSG_THEMES_FILE_NOT_FOUND "DiretÃ³rio: themes (Temas do MIOLO) nÃ£o encontrado."
#define MSG_MIOLOCONF_EXISTS "O arquivo <b>miolo.conf</b> jÃ¡ existe.<br><br>Ã aconselhÃ¡vel que vocÃª faÃ§a uma cÃ³pia do arquivo atual ou desmarque esta opÃ§Ã£o, pois o arquivo existente serÃ¡ sobrescrito.<br><br>LocalizaÃ§Ã£o: "
#define CREATING_MIOLOCONF "Criando arquivo de configuraÃ§Ã£o miolo.conf..."
#define INSTALLATION_FINISHED "InstalaÃ§Ã£o ConcluÃ­da.  Arquivo de log criado em /tmp/miolo_install.log"
#define INSTALL_END "InstalaÃ§Ã£o concluÃ­da."
#define MSG_ERROR_CREATING_MIOLOCONF "NÃ£o foi possÃ­vel criar o arquivo miolo.conf com as \nconfiguraÃ§Ãµes informadas.\nNÃ£o esqueÃ§a que vocÃª deve executar o instalador \ncomo root (preferencialmente) ou ter permissÃ£o de\ngravaÃ§Ã£o no diretÃ³rio."

// WhatsThis Help
  // Page 2
#define WT_DIRBUTTON "Clique aqui para selecionar e/ou criar um diretÃ³rio. <br> <b>Importante:</b> para criar um diretÃ³rio, vocÃª deve ter permissÃ£o de gravaÃ§Ã£o.";
#define WT_EDTHTML "Informe o diretÃ³rio que estarÃ¡ visÃ­vel na WEB. <br> VocÃª tambÃ©m deverÃ¡ configurar corretamente o <b>Apache</b>, para que o <em>DocumentRoot</em> (ou <em>Virtual Host</em>) apontem para este diretÃ³rio e o browser encontre os arquivos corretamente.";
#define WT_EDTMIOLO "Neste campo informe o diretÃ³rio onde deverÃ£o ser instalados os arquivos do <b>MIOLO</b>.";
#define WT_EDTMODULES "Aqui, informe o diretÃ³rio onde estarÃ£o localizados os arquivos dos MÃ³dulos/Sistemas desenvolvidos com o MIOLO.";
#define WT_EDTLOCALE "Informe o diretÃ³rio onde serÃ£o instalados os arquivos de traduÃ§Ãµes das mensagens dos MÃ³dulos/Sistemas e do MIOLO."
#define WT_EDTLOGS "Informe onde o MIOLO deverÃ¡ gravar os arquivos de logs dos MÃ³dulos/Sistemas. <br> <b>Muito Importante:</b> o <em>Apache</em> deverÃ¡ ter direito de gravaÃ§Ã£o nesse diretÃ³rio.";
#define WT_EDTTHEMES "DiretÃ³rio onde serÃ£o instalados os temas dos MÃ³dulos/Sistemas e MIOLO"
#define WT_EDTURL "Informe aqui o endereÃ§o URL do site.<br><b>Importante:</b> o endereÃ§o aqui informado deve ser corretamente configurado no <em>Apache</em> (DocumentRoot ou Virtual Host)"
#define WT_EDTURL_THEMES "Informe o endereÃ§o WEB que aponte para o diretÃ³rio dos temas. Este endereÃ§o estarÃ¡ abaixo do URL do Site identificado no item anterior"
#define WT_EDTTRACE_PORT "AtravÃ©s desta porta, o MIOLO envia informaÃ§Ãµes como erros, sqls,... InformaÃ§Ãµes essas que podem ser capturadas por programas com o objetivo de debug. Um exemplo disso Ã© o plugin MIOLO para o editor JEdit, que recebe essas informaÃ§Ãµes."

  // Page 3
#define WT_BASE_TIPO "Tipo de Base de Dados que serÃ¡ utilizado"
#define WT_BASE_HOST "IP da mÃ¡quina onde estÃ¡ localizada a Base."
#define WT_BASE_BASE "Nome da Base que armazenarÃ¡ as tabelas do MIOLO"
#define WT_BASE_USER "Nome do usuÃ¡rio para acessar a Base"
#define WT_BASE_PASSWD "Senha do usuÃ¡rio para acesso Ã  Base"

  // Page 4
#define WT_MKLOGIN "Ative esta opÃ§Ã£o para que o MIOLO sempre solicite o Login ao usuÃ¡rio. <br>Nesta situaÃ§Ã£o, o MIOLO sempre abrirÃ¡ a tela de login quando alguÃ©m acessar o site."
#define WT_MIOLOLOGIN "Existem duas maneiras de fazer o controle de login no sistema.<br> A primeira delas Ã© deixar que o MIOLO controle este processo e para tal, o usuÃ¡rio e a senha devem ser cadastrados na tabela cmn_users. <br>Na segunda, a prÃ³pria Base se encarrega de validar o usuÃ¡rio que deverÃ¡ estar <em>obrigatoriamente</em> cadastrado na mesma. Mesmo nessa situaÃ§Ã£o, o usuÃ¡rio deverÃ¡ constar na cmn_users, com a diferenÃ§a que a senha nÃ£o serÃ¡ necessÃ¡ria.<br>Para deixar o MIOLO controlar o acesso dos usuÃ¡rios (<em>padrÃ£o</em>) ative esta opÃ§Ã£o."
#define WT_AUTOLOGIN "O MIOLO oferece a possibilidade de criar login's automÃ¡ticos. Estes logins podem ser, em conjunto com as permissÃµes atribuÃ­das na tabela cmn_access, utilizadas para permitir o acesso a certas pÃ¡ginas e opÃ§Ãµes de um sistema para as quais nÃ£o Ã© necessÃ¡rio efetuar login de forma explÃ­cita. <br>Em outras palavras, o MIOLO efetua login no sistema utilizando o usuÃ¡rio e senhas definidas no login automÃ¡tico."
#define WT_LOGINID "Nome do usuÃ¡rio para login automÃ¡tico. As permissÃµes deste usuÃ¡rio devem ser colocadas posteriormente na tabela cmn_access"
#define WT_LOGINPWD "A senha que serÃ¡ utilizada para o login automÃ¡tico"
#define WT_LOGINNAME "O nome por extenso do usuÃ¡rio"

  // Page 4a
#define WT_INSTALL_MIOLO "Ative esta opÃ§Ã£o para instalar as classes do MIOLO."
#define WT_INSTALL_COMMON "Ative esta opÃ§Ã£o para instalar o mÃ³dulo common. <br> O mÃ³dulo common Ã© utilizado pelo MIOLO para as tarefas de login, alÃ©m da criaÃ§Ã£o do Menu e Tela Principal. "
#define WT_INSTALL_EXAMPLES "Com esta opÃ§Ã£o marcada, serÃ¡ instalado o mÃ³dulo de exemplos e tutoriais."
#define WT_INSTALL_THEMES "Ative esta opÃ§Ã£o para instalar os Temas. <br> Para alterar o tema padrÃ£o utilizado nos sistemas, altere a configuraÃ§Ã£o no arquivo miolo.conf.<br> Para criar ou alterar temas, dÃª uma olhada nos diretÃ³rios dos temas (abaixo de themes)."
#define WT_CREATE_CONF "O miolo.conf Ã© o arquivo que mantÃ©m todas as configuraÃ§Ãµes do MIOLO, portanto, Ã© necessÃ¡rio que ele seja criado. <br> <em>Importante:</em> em caso de atualizaÃ§Ã£o do MIOLO, <b>nÃ£o</b> Ã© necessÃ¡rio criÃ¡-lo novamente."
#define WT_SHOW_APACHE "Para ver uma sugestÃ£o para configuraÃ§Ã£o de VirtualHost  no Apache de acordo com os dados informados para a instalaÃ§Ã£o, ative esta opÃ§Ã£o."
