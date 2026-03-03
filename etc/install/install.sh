#!/bin/sh
#
# Installation Script
#
# Author: Vilson Cristiano GÃ¤rtner <vilson@miolo.org.br> - 27 Aug. 2004
# Updated: 30 Set. 2005
#
# If you found any problem executing this program, please try commenting 
# this line bellow: [-n "$DISPLAY".... (line 14 of the file)
#
# Se vocÃª encontrar algum problema durante a execuÃ§Ã£o do programa,
# comente a segunda linha abaixo: [-n "$DISPLAY".... (linha 14 do arquivo)
#

DIALOG="dialog"
[ -n "$DISPLAY" ] && [ -x ./Xdialog ] && DIALOG="./Xdialog" 


# Program Constants
PROGRAM_NAME="MIOLO 2";
PROGRAM_VERSION="Beta 1";
INSTALL_PATH="/usr/local/miolo2";
CONFIG_PATH="/usr/local/miolo2/etc/";
CONFIG_FILE="miolo.conf";
CONFIG_NAME="$CONFIG_PATH$CONFIG_FILE";
PREVIOUS_VERSION="1.0";
CHANGELOG_EN="README.english";
CHANGELOG_BR="README";
LICENSE_FILE="LICENSE"
OPT_DIR="miolo2/"

# translations
if [ "$1" = "en" ]; then
    CHANGELOG_FILE="$CHANGELOG_EN";
    trINSTALATION="Installation:";
    trINFORM_PATH="Please, inform the directory where I can\nfind the programs of";
    trEXAMPLE="For Example:";
    trPROGS_NOT_FOUND="Programs NOT found in directory";
    trBASE_NOT_RUNNING1="DATABASE SERVER IS NOT RUNNING! \n\nThe ";
    trBASE_NOT_RUNNING2="server is not running.\nIt'll not be possible to create the database and tables.\n\nCorrect this and try again.";
    trAPACHE_NOT_RUNNING="APACHE IS NOT  RUNNING! \n\nThe Apache Server must be installed and running\nin order to be able to access";
    trAPACHE_SUGGESTION1="We present here an example of VirtualHost that";
    trAPACHE_SUGGESTION2="you could use for Apache.";
    trDONT_CREATE_DB="Don't create Database";
    trCREATE_DB1="Creation of the Database";
    trCREATE_DB2="Now we'll create the database and tables of";
    trCREATE_DB3="Wich database do you want to use?";
    trINSTALL1="This program will install";
    trINSTALL2="in your computer.\nDo you want to continue?";
    trPROGRAM_FINISHED_USER="Installation aborted by user.";
    trDIR_EXISTS1="Directory already exists";
    trDIR_EXISTS2="The program has detected that the directory\n";
    trDIR_EXISTS3="already exists.\n\nIf you are upgrading your version of";
    trDIR_EXISTS4="we advice you\nto read the documentation about how to proceed in this case.\n\nWhat do you want to do?";
    trDIR_EXISTS_OP1="Continue with the installation (existent files will be replaced)";
    trDIR_EXISTS_OP2="Update the database of version";
    trONLY="only";
    trDIR_EXISTS_OP3="Only create database and exit";
    trDIR_EXISTS_OP4="Abort the installation now";
    trFINISHED="Finished";
    trINFORM_INSTALL_PATH1="Inform the directory where the";
    trINFORM_INSTALL_PATH2="will be installed.\nThe default installation will be done in";
    trGENERATING="Generating configuration...";
    trLOADING="Loading installer configuration...";
    trINSTALLING="Installing";
    trCREATING_DIR="Creating directory";
    trCOPYING="Copying";
    trTO="to";
    trERROR="ERROR";
    trEND1="The program has finished the installation of";
    trEND2="Please, verify the configuration in";
    trINVALID_USER="Invalid User";
    trMUST_BE_ROOT="This script must be run by root user";
else
    CHANGELOG_FILE="$CHANGELOG_BR";
    trINSTALATION="InstalaÃ§Ã£o"
    trINFORM_PATH="Informe o diretÃ³rio onde estÃ£o localizados os\nbinÃ¡rios (programas) ";
    trEXAMPLE="Por exemplo:";
    trPROGS_NOT_FOUND="Programas nÃ£o encontrados no diretÃ³rio";
    trBASE_NOT_RUNNING1="BASE NÃO ESTÃ ATIVA! \n\nO banco de dados";
    trBASE_NOT_RUNNING2="nÃ£o estÃ¡ rodando.\nNÃ£o serÃ¡ possÃ­vel criar a Base e as tabelas. \n\nCorrija este problema e execute novamente a instalaÃ§Ã£o.";
    trAPACHE_NOT_RUNNING="APACHE NÃO ESTÃ RODANDO! \n\nO Servidor Apache nÃ£o estÃ¡ sendo executado.\nEste programa deve estar instalado e rodando\npara que vocÃª consiga acessar o";
    trAPACHE_SUGGESTION1="NÃ£o esqueÃ§a que Ã© necessÃ¡rio fazer as devidas configuraÃ§Ãµes no servidor Apache.";
    trAPACHE_SUGGESTION2="Aqui apresentamos uma sugestÃ£o de VirtualHost.";
    trDONT_CREATE_DB="NÃ£o criar Base de Dados";
    trCREATE_DB1="CriaÃ§Ã£o da Base de Dados";
    trCREATE_DB2="Agora serÃ¡ criada a base de dados e as tabelas do";
    trCREATE_DB3="Qual base de dados deseja utilizar?";
    trINSTALL1="Este script instalarÃ¡ o";
    trINSTALL2="em seu computador.\nDeseja continuar com a instalaÃ§Ã£o?";
    trPROGRAM_FINISHED_USER="Programa finalizado pelo usuÃ¡rio.";
    trDIR_EXISTS1="DiretÃ³rio JÃ¡ Existe";
    trDIR_EXISTS2="O programa de instalaÃ§Ã£o detectou que o diretÃ³rio informado\n";
    trDIR_EXISTS3="jÃ¡ existe.\n\nSe vocÃª estiver atualizando a sua versÃ£o do";
    trDIR_EXISTS4="aconselhamos que \nvocÃª leia a documentaÃ§Ã£o para saber como proceder nesse caso.\n\nO que vocÃª deseja fazer?";
    trDIR_EXISTS_OP1="Continuar com a instalaÃ§Ã£o (arquivos existentes serÃ£o sobrescritos)";
    trDIR_EXISTS_OP2="Atualizar a base de dados da versÃ£o";
    trONLY="somente";
    trDIR_EXISTS_OP3="Apenas criar a base de dados e sair";
    trDIR_EXISTS_OP4="Cancelar a instalaÃ§Ã£o agora";
    trFINISHED="ConcluÃ­do";
    trINFORM_INSTALL_PATH1="Informe o diretÃ³rio onde o";
    trINFORM_INSTALL_PATH2="serÃ¡ instalado.\nPor padrÃ£o, a instalaÃ§Ã£o Ã© feita no diretÃ³rio";
    trGENERATING="Gerando configuraÃ§Ãµes...";
    trLOADING="Carregando dados de configuraÃ§Ã£o do instalador...";
    trINSTALLING="Instalando";
    trCREATING_DIR="Criando diretÃ³rio";
    trCOPYING="Copiando";
    trTO="para";
    trERROR="ERRO";
    trEND1="O programa finalizou a instalaÃ§Ã£o do";
    trEND2="Verifique as configuraÃ§Ãµes no arquivo";
    trINVALID_USER="UsuÃ¡rio InvÃ¡lido";
    trMUST_BE_ROOT="Ã necessÃ¡rio executar este script como usuÃ¡rio root.\nUse, por exemplo: sudo ./install.sh";
fi

# create postgresql database
create_base_pgsql()
{
    DATABASE="postgresql";
    PGSQL_PATH=`whereis psql | cut -d" " -f2`;

    tempfile=`tempfile 2>/dev/null` || tempfile=/tmp/test$$
    trap "rm -f $tempfile" 0 1 2 5 15

    while [ 0 ] ; do  

        $DIALOG --title "$trINSTALATION $PROGRAM_NAME" --clear \
                --inputbox "$trINFORM_PATH Postgresql.\n\n $trEXAMPLE /usr/local/pgsql/bin" 12 51 $PGSQL_PATH  2> $tempfile

        if [ $? = 1 -o $? = 255 ]; then
            exit;
        fi

        choice=`cat $tempfile`;
        PGSQL_PATH=$choice;

        FILE_NAME=${choice}/psql;

        if [ -x $FILE_NAME ]; then
            break;
        fi

        echo "$trPROGS_NOT_FOUND $PGSQL_PATH";

    done

    verify_base_running;

    if [ "$JOB_DB" = "udpate" ]; then
        ./update_db_pgsql $PGSQL_PATH
    else
        ./create_db_pgsql $PGSQL_PATH
    fi

}


# create mysql database
create_base_mysql()
{
    DATABASE="mysql";
    MYSQL_PATH=`whereis mysql | cut -d" " -f2`;

    tempfile=`tempfile 2>/dev/null` || tempfile=/tmp/test$$
    trap "rm -f $tempfile" 0 1 2 5 15

    while [ 0 ] ; do  

        $DIALOG --title "$trINSTALATION $PROGRAM_NAME" --clear \
                --inputbox "$trINFORM_PATH Mysql.\n\n $trEXAMPLE /usr/local/mysql/bin" 12 51 $MYSQL_PATH  2> $tempfile

        if [ $? = 1 -o $? = 255 ]; then
            exit;
        fi

        choice=`cat $tempfile`;
        MYSQL_PATH=$choice;

        FILE_NAME=${choice}/mysqladmin;  

        if [ -x $FILE_NAME ]; then
            break;
        fi

        echo "$trPROGS_NOT_FOUND $MYSQL_PATH";
    done

    verify_base_running;

    ./create_db_mysql $MYSQL_PATH
}


# verify if the base is running
verify_base_running()
{
    BASE_OK="yes";

    if [ $DATABASE = "postgresql" ]; then
        PID=`/sbin/pidof postmaster`;
        COMMAND="create_db_pgsql";
    elif [ $DATABASE = "mysql" ]; then
        PID=`/sbin/pidof mysqld`;
        COMMAND="create_db_mysql";
    fi

    if [ -z "$PID" ]; then

        BASE_OK="no";

        $DIALOG --title "$trINSTALATION $PROGRAM_NAME" --clear \
                --msgbox "$trBASE_NOT_RUNNING1 $DATABASE $trBASE_NOT_RUNNING2" 15 55 
    fi

}


verify_apache_running()
{
    PID=`/sbin/pidof httpd`;

    if [ -z "$PID" ]; then
        $DIALOG --title "$trINSTALATION $PROGRAM_NAME" --clear \
                --msgbox "$trAPACHE_NOT_RUNNING $PROGRAM_NAME." 10 55 
    fi

    APACHE_TMP_FILE="./apache_suggestion";

    echo "" > $APACHE_TMP_FILE;
    echo $trAPACHE_SUGGESTION1 >> $APACHE_TMP_FILE;
    echo $trAPACHE_SUGGESTION2 >> $APACHE_TMP_FILE;
    echo " " >> $APACHE_TMP_FILE;
    echo "<VirtualHost *> " >> $APACHE_TMP_FILE;
    echo "    ServerAdmin webmaster@localhost " >> $APACHE_TMP_FILE;
    echo "    DocumentRoot $INSTALL_PATH/html " >> $APACHE_TMP_FILE;
    echo "    ServerName your.domain.name " >> $APACHE_TMP_FILE;
    echo "    ErrorLog logs/your.domain.name.error_log " >> $APACHE_TMP_FILE;
    echo "    CustomLog logs/your.domain.name.access_log common " >> $APACHE_TMP_FILE;
    echo " " >> $APACHE_TMP_FILE;
    echo "    <Directory \"$INSTALL_PATH/html\"> " >> $APACHE_TMP_FILE;
    echo "        Options FollowSymLinks " >> $APACHE_TMP_FILE;
    echo "        AllowOverride All " >> $APACHE_TMP_FILE;
    echo "        Order allow,deny " >> $APACHE_TMP_FILE;
    echo "        Allow from all " >> $APACHE_TMP_FILE;
    echo "    </Directory> " >> $APACHE_TMP_FILE;
    echo " " >> $APACHE_TMP_FILE;
    echo "</VirtualHost> " >> $APACHE_TMP_FILE;

    $DIALOG --title "APACHE" --no-cancel --tailbox $APACHE_TMP_FILE 30 70;

}


select_create_db()
{
    STOP=0;

    while [ $STOP = 0 ]; do

        tempfile=`tempfile 2>/dev/null` || tempfile=/tmp/test$$
        trap "rm -f $tempfile" 0 1 2 5 15

        $DIALOG --backtitle "$trCREATE_DB1" \
                --title "$trINSTALATION $PROGRAM_NAME" --clear \
                --menu "$trCREATE_DB2 $PROGRAM_NAME.\n\n$trCREATE_DB3" 15 55 3 \
"1"  "Postgresql"  \
"2"  "Mysql" \
"3"  "$trDONT_CREATE_DB"  2> $tempfile

        if [ $? = 1 -o $? = 255 ]; then
            exit;
        fi

        choice=`cat $tempfile`

        if [ "$choice" = "1" ]; then
            create_base_pgsql ;
        elif  [ "$choice" = "2" ]; then
            create_base_mysql;
        elif  [ "$choice" = "3" ]; then
            STOP=1;
        fi

        if [ "$BASE_OK" = "yes" ]; then
            STOP=1;
        fi
    done

}


ask_continue()
{
    $DIALOG --title "$trINSTALATION $PROGRAM_NAME" --clear \
            --yesno "$trINSTALL1 $PROGRAM_NAME $PROGRAM_VERSION $trINSTALL2" 10 60

    case $? in
        0)      main;;
        1)      echo "$trPROGRAM_FINISHED_USER"; exit;;
        255)    echo "$trPROGRAM_FINISHED_USER"; exit;;
    esac
}


verify_path_exists()
{
    if [ -d $INSTALL_PATH ]; then
        tempfile=`tempfile 2>/dev/null` || tempfile=/tmp/test$$
        trap "rm -f $tempfile" 0 1 2 5 15

#        $DIALOG --backtitle "$trDIR_EXISTS1" \
#                --title "$trINSTALATION $PROGRAM_NAME" --clear \
#                --menu "$trDIR_EXISTS2 $INSTALL_PATH $trDIR_EXISTS3 $PROGRAM_NAME, $trDIR_EXISTS4 " 20 75 4 \
#"1"  "$trDIR_EXISTS_OP1"  \
#"2"  "$trDIR_EXISTS_OP2 $PREVIOUS_VERSION ($trONLY Postgresql)"  \
#"3"  "$trDIR_EXISTS_OP3" \
#"4"  "$trDIR_EXISTS_OP4"  2> $tempfile

        $DIALOG --backtitle "$trDIR_EXISTS1" \
                --title "$trINSTALATION $PROGRAM_NAME" --clear \
                --menu "$trDIR_EXISTS2 $INSTALL_PATH $trDIR_EXISTS3 $PROGRAM_NAME, $trDIR_EXISTS4 " 20 75 4 \
"1"  "$trDIR_EXISTS_OP1"  \
"2"  "$trDIR_EXISTS_OP4"  2> $tempfile


        if [ $? = 1 -o $? = 255 ]; then
            exit
        fi

        choice=`cat $tempfile`

#        if [ "$choice" = "2" ]; then
#            JOB_DB="udpate";
#            create_base_pgsql;
#            echo ""; echo "$trFINISHED.";
#            exit;
        if [ "$choice" = "1" ]; then
            echo ""; # continuar com a instalaÃ§Ã£o
#        elif [ "$choice" = "3" ]; then
#            select_create_db;
#            show_changelog;
#            verify_apache_running;
#            exit;
        else
            echo "$trPROGRAM_FINISHED_USER";
            exit;
        fi

    fi
}


show_changelog()
{
    tempfile=`tempfile 2>/dev/null` || tempfile=/tmp/test$$
    trap "rm -f $tempfile" 0 1 2 5 15

#    cat << EOF > $tempfile 

#    Abaixo estÃ£o descritas as principais alteraÃ§Ãµes na versÃ£o.

#    EOF

#    tempfile=`tempfile 2>/dev/null` || tempfile=/tmp/test$$
#    trap "rm -f $tempfile" 0 1 2 5 15

    FILE_PATH="$OPT_DIR$CHANGELOG_FILE"

    TEXT=./$FILE_PATH
    test -f $TEXT


    if [ $DIALOG == "dialog" ]; then
        $DIALOG --title "README $PROGRAM_NAME" \
                --textbox $TEXT 30 85
    else
        $DIALOG --title "README $PROGRAM_NAME" \
                --textbox $TEXT 30 80
    fi


        if [ $? = 1 -o $? = 255 ]; then
            exit
        fi

}


show_licence()
{
    tempfile=`tempfile 2>/dev/null` || tempfile=/tmp/test$$
    trap "rm -f $tempfile" 0 1 2 5 15

    FILE_PATH="$OPT_DIR$LICENSE_FILE"

    TEXT=./$FILE_PATH
    test -f $TEXT


    if [ $DIALOG == "dialog" ]; then
        $DIALOG --title "License $PROGRAM_NAME" \
                --exit-label "ACCEPT" \
                --textbox $TEXT 30 90
    else
        $DIALOG --title "License $PROGRAM_NAME" \
                --ok-label "ACCEPT" \
                --cancel-label "DECLINE" \
                --textbox $TEXT 30 90
    fi

        if [ $? = 1 -o $? = 255 ]; then
            exit
        fi
}


main()
{
    show_licence;

    tempfile=`tempfile 2>/dev/null` || tempfile=/tmp/test$$
    trap "rm -f $tempfile" 0 1 2 5 15

    $DIALOG --title "$trINSTALATION $PROGRAM_NAME - v. $PROGRAM_VERSION" --clear \
            --inputbox "$trINFORM_INSTALL_PATH1 $PROGRAM_NAME $trINFORM_INSTALL_PATH2 $INSTALL_PATH" 12 51 $INSTALL_PATH  2> $tempfile

    retval=$?

    case $retval in
    0)
        INSTALL_PATH=`cat $tempfile`;;
    1)
        echo "$trPROGRAM_FINISHED_USER";exit;;
    255)
            echo "$trPROGRAM_FINISHED_USER"; exit;;
    esac

    verify_path_exists;

    echo "install=('miolo2/*=$INSTALL_PATH')" > ./install-data;

#    if [ "$DIALOG" = "Xdialog" ]; then
#        (echo -n "$trLOADING"
#
#        source install-data && echo " OK"
#        echo ""
#        echo -n "=> $trINSTALLING:"
#        echo ""
#        for item in ${install[@]}; do
#            orig=$(echo $item | cut -d'=' -f 1)
#            dest=$(echo $item | cut -d'=' -f 2)
#
#            echo -n " - $trCREATING_DIR '$dest' ... " &&\
#            install -d $dest 2>/dev/null && echo " OK" &&
#            ( echo -n " - $trCOPYING $orig $trTO $dest ... " &&\
#            cp -rv ${orig} $dest 2>&1 && echo " OK" 
#            ) || echo " **$trERROR!**"
#        done 
#        echo -n "- $trFINISHED";) | \
#        $DIALOG --title "$trINSTALLING" --no-cancel --tailbox "-" 20 100;    
#    else
        (echo "$trGENERATING" >$tempfile

        source install-data && echo " OK" >> $tempfile
        echo "" >> $tempfile
        echo -n "=> $trLOADING:" >> $tempfile
        echo "" >> $tempfile

        for item in ${install[@]}; do
            orig=$(echo $item | cut -d'=' -f 1)
            dest=$(echo $item | cut -d'=' -f 2)

            echo -n " - $trCREATING_DIR $dest ... " >> $tempfile &&\
            install -d $dest 2>>/dev/null && echo " OK">>$tempfile &&
            ( echo -n " - $trCOPYING $orig $trTO $dest ... ">>$tempfile &&\
                cp -r ${orig} $dest 2>>$tempfile && echo " OK" >>$tempfile
            ) || echo " **$trERROR!**">>$tempfile
        done 
        echo -n "=> $trFINISHED">>$tempfile;) | \
        $DIALOG --title "$trINSTALLING" --no-cancel --tailbox $tempfile 20 100;

#    fi

    # create database?
    #select_create_db;

    # is apache running?
    verify_apache_running;

    # show the CHANGELOG
    show_changelog;

    $DIALOG --title "$trINSTALATION $PROGRAM_NAME $PROGRAM_VERSION" --clear \
            --msgbox "$trEND1 $PROGRAM_NAME.\n\n$trEND2 $CONFIG_NAME" 10 60

    exit;
}


USER=`whoami`;

if test $USER != "root" ;
 then
     $DIALOG --beep --title "$trINVALID_USER" --clear \
             --msgbox "$trMUST_BE_ROOT" 10 50 
    exit;
else
    ask_continue;
fi

echo -n "Em caso de problemas na instalaÃ§Ã£o, altere o fonte deste script (veja as instruÃ§Ãµes no inÃ­cio deste arquivo)."
echo -n "In case of problems with this installer, please take a look at the source ;-)"
