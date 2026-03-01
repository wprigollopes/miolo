<?php

global $autoload, $mioloClassesPath, $themePath, $autoloadFile;

//echo $_SERVER['SERVER_SOFTWARE'];
$scriptFileName = $_SERVER["SCRIPT_FILENAME"];
$installerRoot  = substr($scriptFileName, 0,  strrpos( $scriptFileName, '/') + 1 );

$mioloDir = $installerRoot . '../../';
$mioloDir = realPath($mioloDir);

$mioloClassesPath = $mioloDir . '/classes/';

$controlsClassesPath = $mioloClassesPath . 'ui/controls/';
$controlsClassesPath = realPath($controlsClassesPath) . '/';

$themePath    = $mioloClassesPath . '/ui/themes/miolo2/';
$autoloadFile = $mioloClassesPath . 'etc/autoload.xml';

require_once($installerRoot . '/mautoload.class');
require_once($installerRoot . 'miolo.class');

//$MIOLO = MIOLO::GetInstance();

//MIOLO::vd($MIOLO);

$jsScript = "
var xOffset = 300;
var yOffset = -5;

function getMousePosition( e )
{
    //is_ie = ( /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent) );
    
    var is_ie = document.all ? true : false;

    
    var posX;
    var posY;
    if ( is_ie ) 
    {
        posY = window.event.clientY + document.body.scrollTop;
        posX = window.event.clientX + document.body.scrollLeft;
    } else {
        posY = e.clientY + window.scrollY;
        posX = e.clientX + window.scrollX;
    }
    
    return new Array( posX, posY);
}


function showPopup (targetObjectId, eventObj) {
    if(eventObj) {
    // hide any currently-visible popups
    
    if ( window.currentlyVisiblePopup == targetObjectId )
    {
        hideCurrentPopup();
        return;
    }

    hideCurrentPopup();

    // stop event from bubbling up any farther
    eventObj.cancelBubble = true;
    // move popup div to current cursor position 
    // (add scrollTop to account for scrolling for IE)

    //var newXCoordinate = (eventObj.pageX)?eventObj.pageX + xOffset:eventObj.x + xOffset + ((document.body.scrollLeft)?document.body.scrollLeft:0);
    //var newYCoordinate = (eventObj.pageY)?eventObj.pageY + yOffset:eventObj.y + yOffset + ((document.body.scrollTop)?document.body.scrollTop:0);
    
    var mousePosition = getMousePosition(eventObj)
    
    // ho knows why, but it's needed otherwise it goes out of the expected bounds
    var newXCoordinate = mousePosition[0]-125;
    var newYCoordinate = mousePosition[1]-56;
    
//    document.getElementById(targetObjectId).style.left = newXCoordinate + 'px'; 
//    document.getElementById(targetObjectId).style.top  = newYCoordinate + 'px';
//    document.getElementById(targetObjectId).style.visibility  = 'visible';
    
    moveObject(targetObjectId, newXCoordinate, newYCoordinate);

    // and make it visible
    if( changeObjectVisibility(targetObjectId, 'visible') ) {
        // if we successfully showed the popup
        // store its Id on a globally-accessible object
        window.currentlyVisiblePopup = targetObjectId;
        return true;
    } else {
        // we couldn't show the popup, boo hoo!
        return false;
    }
    } else {
    // there was no event object, so we won't be able to position anything, so give up
    return false;
    }
} // showPopup

function hideCurrentPopup() {
    // note: we've stored the currently-visible popup on the global object window.currentlyVisiblePopup
    if(window.currentlyVisiblePopup) {
    changeObjectVisibility(window.currentlyVisiblePopup, 'hidden');
    window.currentlyVisiblePopup = false;
    }
} // hideCurrentPopup

function getStyleObject(objectId) {
    // cross-browser function to get an object's style object given its id
    if(document.getElementById && document.getElementById(objectId)) {
    // W3C DOM
    return document.getElementById(objectId).style;
    } else if (document.all && document.all(objectId)) {
    // MSIE 4 DOM
    return document.all(objectId).style;
    } else if (document.layers && document.layers[objectId]) {
    // NN 4 DOM.. note: this won't find nested layers
    return document.layers[objectId];
    } else {
    return false;
    }
} // getStyleObject

function changeObjectVisibility(objectId, newVisibility) {
    // get a reference to the cross-browser style object and make sure the object exists
    var styleObject = getStyleObject(objectId);
    if(styleObject) {
    styleObject.visibility = newVisibility;
    return true;
    } else {
    // we couldn't find the object, so we can't change its visibility
    return false;
    }
} // changeObjectVisibility

function moveObject(objectId, newXCoordinate, newYCoordinate) {
    // get a reference to the cross-browser style object and make sure the object exists
    var styleObject = getStyleObject(objectId);
    if(styleObject) {
    styleObject.left = newXCoordinate+'px';
    styleObject.top = newYCoordinate+'px';
    return true;
    } else {
    // we couldn't find the object, so we can't very well move it
    return false;
    }
} // moveObject

";


$MIOLO->page->addJsCode($jsScript);

$MIOLO->getTheme();

//$MIOLO->theme = new Theme();
//$MIOLO->theme->setLayout('default');
//$MIOLO->theme->setModule('miolo');
//$MIOLO->theme->setPath(dirname($themePath));
//$MIOLO->theme->init();
$MIOLO->getPainter();

$theme = $MIOLO->theme;



?>