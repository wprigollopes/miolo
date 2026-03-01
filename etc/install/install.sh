#!/bin/sh
#
# Installation Script
#
# Author: Vilson Cristiano Gärtner <vilson@miolo.org.br> - 27 Aug. 2004

# If you found any problem executing this program, please try commenting 
# this line bellow: [-n "$DISPLAY".... (line 14 of the file)

# Se você encontrar algum problema durante a execução do programa,
# comente a segunda linha abaixo: [-n "$DISPLAY".... (linha 14 do arquivo)

DIALOG="dialog"
[ -n "$DISPLAY" ] && [ -x ./Xdialog ] && DIALOG="./Xdialog" 


# Program Constants
PROGRAM_NAME="MIOLO";
PROGRAM_VERSION="1.0-Final";
INSTALL_PATH="/usr/local/miolo";
CONFIG_PATH="/etc/miolo/";
CONFIG_FILE="miolo.conf";
CONFIG_NAME="$CONFIG_PATH$CONFIG_FILE";
PREVIOUS_VERSION="RC5";
CHANGELOG_EN="CHANGELOG.english";
CHANGELOG_BR="CHANGELOG";

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
    trAPACHE_SUGGESTION="We present here an example of VirtualHost that you could use for Apache.";
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
    trINSTALATION="Instalação"
    trINFORM_PATH="Informe o diretório onde estão localizados os\nbinários (programas) ";
    trEXAMPLE="Por exemplo:";
    trPROGS_NOT_FOUND="Programas não encontrados no diretório";
    trBASE_NOT_RUNNING1="BASE NÃO ESTÁ ATIVA! \n\nO banco de dados";
    trBASE_NOT_RUNNING2="não está rodando.\nNão será possível criar a Base e as tabelas. \n\nCorrija este problema e execute novamente a instalação.";
    trAPACHE_NOT_RUNNING="APACHE NÃO ESTÁ RODANDO! \n\nO Servidor Apache não está sendo executado.\nEste programa deve estar instalado e rodando\npara que você consiga acessar o";
    trAPACHE_SUGGESTION="Não esqueça que é necessário fazer as devidas configurações no servidor Apache. Aqui apresentamos uma sugestão de VirtualHost.";
    trDONT_CREATE_DB="Não criar Base de Dados";
    trCREATE_DB1="Criação da Base de Dados";
    trCREATE_DB2="Agora será criada a base de dados e as tabelas do";
    trCREATE_DB3="Qual base de dados deseja utilizar?";
    trINSTALL1="Este script instalará o";
    trINSTALL2="em seu computador.\nDeseja continuar com a instalação?";
    trPROGRAM_FINISHED_USER="Programa finalizado pelo usuário.";
    trDIR_EXISTS1="Diretório Já Existe";
    trDIR_EXISTS2="O programa de instalação detectou que o diretório informado\n";
    trDIR_EXISTS3="já existe.\n\nSe você estiver atualizando a sua versão do";
    trDIR_EXISTS4="aconselhamos que \nvocê leia a documentação para saber como proceder nesse caso.\n\nO que você deseja fazer?";
    trDIR_EXISTS_OP1="Continuar com a instalação (arquivos existentes serão sobrescritos)";
    trDIR_EXISTS_OP2="Atualizar a base de dados da versão";
    trONLY="somente";
    trDIR_EXISTS_OP3="Apenas criar a base de dados e sair";
    trDIR_EXISTS_OP4="Cancelar a instalação agora";
    trFINISHED="Concluído";
    trINFORM_INSTALL_PATH1="Informe o diretório onde o";
    trINFORM_INSTALL_PATH2="será instalado.\nPor padrão, a instalação é feita no diretório";
    trLOADING="Carregando dados de configuração do instalador...";
    trINSTALLING="Instalando";
    trCREATING_DIR="Criando diretório";
    trCOPYING="Copiando";
    trTO="para";
    trERROR="ERRO";
    trEND1="O programa finalizou a instalação do";
    trEND2="Verifique as configurações no arquivo";
    trINVALID_USER="Usuário Inválido";
    trMUST_BE_ROOT="É necessário executar este script como usuário root";
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
    echo $trAPACHE_SUGGESTION >> $APACHE_TMP_FILE;
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
    echo "    </VirtualHost> " >> $APACHE_TMP_FILE;
    
    $DIALOG --title "$trINSTALATION $PROGRAM_NAME" --no-cancel --tailbox $APACHE_TMP_FILE 30 70;
    
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
            --yesno "$trINSTALL1 $PROGRAM_NAME $PROGRAM_VERSION $trINSTALL2" 10 50
    
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

        $DIALOG --backtitle "$trDIR_EXISTS1" \
                --title "$trINSTALATION $PROGRAM_NAME" --clear \
                --menu "$trDIR_EXISTS2 $INSTALL_PATH $trDIR_EXISTS3 $PROGRAM_NAME, $trDIR_EXISTS4 " 20 75 4 \
"1"  "$trDIR_EXISTS_OP1"  \
"2"  "$trDIR_EXISTS_OP2 $PREVIOUS_VERSION ($trONLY Postgresql)"  \
"3"  "$trDIR_EXISTS_OP3" \
"4"  "$trDIR_EXISTS_OP4"  2> $tempfile
        
        if [ $? = 1 -o $? = 255 ]; then
            exit
        fi
        
        choice=`cat $tempfile`
        
        if [ "$choice" = "2" ]; then
            JOB_DB="udpate";
            create_base_pgsql;
            echo ""; echo "$trFINISHED.";
            exit;
        elif [ "$choice" = "1" ]; then
            echo ""; # continuar com a instalação
        elif [ "$choice" = "3" ]; then
            select_create_db;
            show_changelog;
            verify_apache_running;
            exit;
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
    
#    Abaixo estão descritas as principais alterações na versão.
    
#    EOF

    TEXT=./$CHANGELOG_FILE
    test -f $TEXT || TEXT=./COPYING
    
    cat $TEXT | expand >> $tempfile
    
    $DIALOG --clear --title "ChangeLog $PROGRAM_NAME $PROGRAM_VERSION" --textbox "$tempfile" 30 90
    
}

   
main()
{    
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
    
    echo "install=('miolo/*=$INSTALL_PATH'\\" > ./install-data;
    echo "         'miolo/etc/*=$CONFIG_PATH')" >> ./install-data;
    
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
        (echo "$trLOADING" >$tempfile
        
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
    select_create_db;
    
    # is apache running?
    verify_apache_running;
      
    # show the CHANGELOG
    show_changelog;

    $DIALOG --title "$trINSTALATION $PROGRAM_NAME" --clear \
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

echo -n "Em caso de problemas na instalação, altere o fonte deste script. Veja as instruções no início do arquivo."
echo -n "In case of problems with the installer, please take a look at the source ;-)"
