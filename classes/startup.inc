<?php
#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# @title
#   MIOLO instantiation script
#
# @description
#   This file instantiates the MIOLO framework.
#
# @topics   miolo
#
# @created
#   2001/08/14
#
# @organisation
#   MIOLO - Miolo Development Team - UNIVATES Centro Universitario
#
# @legal
#   CopyLeft (L) 2001-2002 UNIVATES, Lajeado/RS - Brasil
#   Licensed under GPL (see COPYING.TXT or FSF at www.fsf.org for
#   further details)
#
# @contributors
#   Thomas Spriestersbach    [author] [ts@interact2000.com.br]
#   Vilson Cristiano Gartner [author] [vgartner@univates.br]
# 
# @maintainers
#   Thomas Spriestersbach    [author] [ts@interact2000.com.br]
#   Vilson Cristiano Gartner [author] [vgartner@univates.br]
#
# @history
#   See history in CVS repository.
#
# @id $Id: startup.inc,v 1.6 2004/08/12 18:15:32 vgartner Exp $
#---------------------------------------------------------------------
include_once $MIOLOCONF['home']['miolo'].'/login.class';
include_once $MIOLOCONF['home']['miolo'].'/miolo.class';

#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# Support for gettext()
#---------------------------------------------------------------------
if ( function_exists('_') )
{
    $TEXTDOMAIN = 'miolo';
    
    setlocale(LC_ALL, $MIOLOCONF['i18n']['language']);
    bindtextdomain ($TEXTDOMAIN, $MIOLOCONF['i18n']['locale']);
    textdomain ($TEXTDOMAIN);
}

#+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
# This function is a wrapper for the gettext support. In addition to
# the standard gettext() function, it provides support for up to three
# positional parameters represented by the placeholders <code>@1</code>,
# <code>@2</code> and <code>@3</code>.
#
# @example
# echo _M('@1 is a romance written by @2 in @3','foo','Tom Sawyer','Mark Twain','1890');
# // will result in:
# //   "Tom Sawyer is a romance written by Mark Twain in 1890" 
#
# echo _M('@2 escreveu o romance @1 em @3','foo','Tom Sawyer','Mark Twain','1890');
# //
# // will result in:
# //   "Mark Twain escreveu o romance Tom Sawyer em 1890" 
#---------------------------------------------------------------------
function _M($msg,$dom='miolo',$p1=null,$p2=null,$p3=null)
{   global $TEXTDOMAIN,$MIOLOCONF;
    
    if ( $msg=='')
    {   
        return $msg;
    }
    
    if ( function_exists('_') )
    {
        if ( $dom != $TEXTDOMAIN )
        {
            $TEXTDOMAIN = $dom;
            bindtextdomain($TEXTDOMAIN, $MIOLOCONF['i18n']['locale']);
            textdomain($TEXTDOMAIN);
        }
        
        $msg = _($msg);
    }
    
    if ( $p1 !== null )
    {
        $msg = str_replace('@1',$p1,$msg);
    }
    
    if ( $p2 !== null )
    {
        $msg = str_replace('@2',$p2,$msg);
    }
    
    if ( $p3 !== null )
    {
        $msg = str_replace('@3',$p3,$msg);
    }
    
    return $msg;
}

// keep login variable in session settings
session_start();

// create master instance of MIOLO object
$MIOLO = new MIOLO;

// reset MIOLO trace log (JEdit Plugin, MIOLO Trace)
$MIOLO->LogMessage('[RESET_LOG_MESSAGES]');

// globas used in all the modules
$theme  =& $MIOLO->GetTheme();
$menu   =& $theme->GetMainMenu();
$navbar =& $theme->GetNavigationBar();

// register default JavaScript source 
$theme->AddScript("common.js");

// obtain request context object
$context = new Context();

// do initial access control
$MIOLO->CheckLogin();

?>
