// +-----------------------------------------------------------------+
// | MIOLO - Miolo Development Team - UNIVATES Centro Universitário  |
// +-----------------------------------------------------------------+
// | CopyLeft (L) 2001,2002  UNIVATES, Lajeado/RS - Brasil           |
// +-----------------------------------------------------------------+
// | Licensed under GPL: see COPYING.TXT or FSF at www.fsf.org for   |
// |                     further details                             |
// |                                                                 |
// | Site: http://miolo.codigolivre.org.br                           |
// | E-mail: vgartner@univates.br                                    |
// |         ts@interact2000.com.br                                  |
// +-----------------------------------------------------------------+
// | Abstract: This file contains the javascript functions           |
// |                                                                 |
// | Created: 2001/08/14 Vilson Cristiano Gärtner [vg]               |
// |                     Thomas Spriestersbach    [ts]               |
// |                                                                 |
// | History: Initial Revision                                       |
// |          2001/12/14 [ts] Added MultiTextField support functions |
// +-----------------------------------------------------------------+

var autoCompleteId;
var autoCompleteInfo;

var __MIOLO_Validate_Errors = '';

/**
 *
 */
function ComboBox_onTextChange(label,textField,selectionList)
{
    var text = textField.value;
    
    for ( var i=0; i<selectionList.options.length; i++ )
    {
        if ( selectionList.options[i].value == text )
        {
            selectionList.selectedIndex = i;
            return;
        }
    }
    
    alert("!!! ATENÇÃO !!!\n\nNão existe uma opção correspondente ao valor '" + 
          text + "'\ndo campo '" + label + "'!");
    
    textField.focus();
}
 
/**
 *
 */
 function ComboBox_onSelectionChange(label,selectionList,textField)
 {
     var index = selectionList.selectedIndex;
     
     if ( index != -1 )
     {
         textField.value = String(selectionList.options[index].value);
     }
 } 

/**
 *
 */
function GotoURL(url)
{
    var prefix = 'javascript:';
    
    // alert(escape(url));
    
    if ( url.indexOf(prefix) == 0 )
    {
        eval(url.substring(11) + ';');
    }
    
    else
    {
        window.location = url;
    }
}

/**
 *
 */
function Lookup(url)
{
    window.open('lookup.php?' + url,'lookup',
                'toolbar=no,width='+screen.width+',height='+screen.height+',scrollbars=yes,' +
                'top=0,left=0,statusbar=yes,resizeable=yes');
}

/**
 *
 */
function AutoComplete(url,fieldId,fieldInfo)
{
    autoCompleteId   = fieldId;
    autoCompleteInfo = fieldInfo;
    
    url = 'autocomplete.php?' + url + '&hint=' + escape(fieldId.value);
    
    top.frames['util'].location = url;
}

/**
 *
 */
function SetResult(info)
{
    // alert(info);
    if ( autoCompleteInfo != null )
    {
        autoCompleteInfo.value = info;
    }
}

/**
 *
 */
function Deliver(id,text)
{
    var lookup_form  = null;
    var lookup_field = null;
    var lookup_text  = null;
    
    url = String(window.location);
    
    // alert(url);
    
    if ( text != null )
    { var pos;
        
        // alert(text);
        
        while ( (pos = text.indexOf('+')) != -1 )
        {
            text = text.substring(0,pos) + ' ' + text.substring(pos+1);
        }
        
        text = unescape(text);
        
        // alert(text);
    }
    
    // separar caminho da url dos parâmetros
    var a = url.split('?');
    
    if ( a.length == 2 )
    {
        // separar os parâmetros
        var b = a[1].split('&');
        
        for ( i=0; i<b.length; i++ )
        {
            var c = b[i].split('=');
            
            if ( c.length == 2 )
            {
                var name  = c[0];
                var value = c[1];
                
                if ( name == 'lookup_form' )
                {
                    lookup_form = value;
                }
                else if ( name == 'lookup_field' )
                {
                    lookup_field = value;
                }
                else if ( name == 'lookup_text' )
                {
                    lookup_text = value;
                }
            }
        }
    }
    //echo lookup_form;
    if ( lookup_form != null )
    {
        if ( lookup_field != null )
        {
            eval("window.opener.document." + lookup_form + "." + 
                 lookup_field + ".value='" + id + "'");
        }
        
        if ( lookup_text != null )
        {
            eval("window.opener.document." + lookup_form + "." + 
                 lookup_text + ".value='" + text + "'");
        }
    }
    
    close();
}

/**
 * vai para a paginal tal de um tabbed form
 */
function _MIOLO_TabbedForm_GotoPage(frmName,pageName)
{
    // alert('_MIOLO_TabbedForm_GotoPage("' + frmName + "','" + pageName + '")');
    var form = eval('document.'+frmName);
    
    if ( form != null )
    {
        form.frm_currpage_.value = pageName;
//        form.frm_submit_.value   = 0;
        
        if ( eval(frmName+'_onSubmit()') )
        {
            form.submit();
        }
    }
    else
    {
        alert('MIOLO INTERNAL ERROR:\n\nForm ' + frmName + ' not found!');
    }
}

/**
 * Função que simplesmente seleciona todos os itens, para que
 * serão incluidos ao enviar o formulário
 */
function _MIOLO_MultiTextField_onSubmit(frmName,mtfName)
{
    var form = eval('document.'+frmName);
    var list = form[mtfName+'[]'];
    if ( list != null  && list.options != null )
    {
        for ( var i=0; i<list.length; i++ )
        {
            list.options[i].selected = true;
        }
    }
    return true;
}

/**
 * Função que intercepta a tecla Enter, para que o conteúdo do
 * campo de texto é adicionado a lista.
 */
function _MIOLO_MultiTextField_onKeyDown(source,frmObj,mtfName,event)
{
    // IE and compatibles use 'keyCode', NS and compatibles 'which'
    var key = ( document.all != null ) ? event.keyCode : event.which;
    
    if ( source.name == mtfName + '_text' )
    {
        if ( key == 13 ) // enter key
        {
            _MIOLO_MultiTextField_add(frmObj,mtfName);
            return false;
        }
    }
    
    else if ( source.name == mtfName + '[]' )
    {
        // alert(key);
        
        if ( key == 46 ) // delete key
        {
            _MIOLO_MultiTextField_remove(frmObj,mtfName);
            return false;
        }
    }
}

/**
 * Funcção que adiciona o conteúdo do campo de texto a lista.
 */
function _MIOLO_MultiTextField_add(frmObj,mtfName)
{
    var list = frmObj[mtfName+'[]'];
    var tf   = frmObj[mtfName+'_text'];
    if ( tf.value != '' )
    {
        var i = list.length;
        list.options[i] = new Option(tf.value);
        for ( var j=0; j<=i; j++ )
        {
            list.options[i].selected = (j==i);
        }
        tf.value = '';
    }
}

/**
 * Funcção que exclui o item atualmente selecionado
 */
function _MIOLO_MultiTextField_remove(frmObj,mtfName)
{
    var list = frmObj[mtfName+'[]'];
    
    for ( var i=0; i<list.length; i++ )
    {
        if ( list.options[i].selected )
        {
            list.options[i] = null;
            
            if ( i >= list.length )
            {
                i = list.length - 1;
            }
            
            if ( i >= 0 )
            {
                list.options[i].selected = true;
            }
            
            break;
        }
    }
}

var _MIOLO_MultiTextField2_separator = '] [';

/**
 *  
 */
function _MIOLO_MultiTextField2_Split(value)
{
    return value.substring(1,value.length-1).split(_MIOLO_MultiTextField2_separator);
}

/**
 *  
 */
function _MIOLO_MultiTextField2_Join(fields)
{
    var value = '[';
    
    for ( var i=0; i<fields.length; i++ )
    {
        if ( i > 0 )
        {
            value += _MIOLO_MultiTextField2_separator;
        }
        
        value += fields[i];
    }
    
    value += ']';
    
    return value;
}

/**
 *  
 */
function _MIOLO_MultiTextField2_onSubmit(frmName,mtfName)
{
  return _MIOLO_MultiTextField_onSubmit(frmName,mtfName);
}

/**
 *  
 */
function _MIOLO_MultiTextField2_onKeyDown(source,frmObj,mtfName,event,numFields)
{
  // IE and compatibles use 'keyCode', NS and compatibles 'which'
  var key  = ( document.all != null ) ? event.keyCode : event.which;
  var name = mtfName + '_text';
  var len  = name.length;
  
  if ( source.name.substring(0,len) == name )
  {
    if ( key == 13 ) // enter key
    {
      _MIOLO_MultiTextField2_add(frmObj,mtfName,numFields);
      return false;
    }
  }

  else if ( source.name == mtfName + '[]' )
  {
    // alert(key);

    if ( key == 46 ) // delete key
    {
      _MIOLO_MultiTextField2_remove(frmObj,mtfName,numFields);
      return false;
    }
  }
}

/**
 *  
 */
function _MIOLO_MultiTextField2_onSelect(frmObj,mtfName,numFields)
{
    var list = frmObj[mtfName+'[]'];
    
    var i = list.selectedIndex;
    
    if ( i != -1 )
    {
        var a = _MIOLO_MultiTextField2_Split(list.options[i].text);
        
        for ( var j=1; j<=numFields; j++ )
        {
            var tf = frmObj[mtfName+'_text'+j];
            
            if ( tf != null )
            {
                tf.value = a[j-1];
            }
            
            else
            {
                var op = frmObj[mtfName+'_options'+j];
                
                if ( op != null )
                {
                    // preselect option based on value
                    for ( var n=0; n<op.options.length; n++ )
                    {
                        if ( op.options[n].value == a[j-1] )
                        {
                            op.selectedIndex = n;
                            break;
                        }
                    }
                }
            }
        }
    }
    
    else
    {
        for ( var j=1; j<=numFields; j++ )
        {
            var tf = frmObj[mtfName+'_text'+j];
            
            if ( tf != null )
            {
                tf.value = '';
            }
            
            else
            {
                var op = frmObj[mtfName+'_text'+j];
                
                if ( op != null )
                {
                    op.selectedIndex = -1;
                }
            }
        }
    }
}

/**
 *  
 */
function _MIOLO_MultiTextField2_getInput(frmObj,mtfName,numFields)
{
    var list   = frmObj[mtfName+'[]'];
    var fields = new Array(numFields);
    
    for ( var i=1; i<=numFields; i++ )
    {
        var tf = frmObj[mtfName+'_text'+i];
        
        fields[i-1] = '';
        
        if ( tf != null )
        {
            if ( i > 1 )
            {
                value += _MIOLO_MultiTextField2_separator;
            }
            
            fields[i-1] = tf.value;
            
            tf.value = '';
        }
        
        else 
        {
            var list = frmObj[mtfName+'_options'+i];
            
            if ( list != null )
            {
                if ( i > 1 )
                {
                    value += _MIOLO_MultiTextField2_separator;
                }
                
                fields[i-1] = list.options[list.selectedIndex].value;
            }
        }
    }

    return _MIOLO_MultiTextField2_Join(fields); 
}

/**
 *  
 */
function _MIOLO_MultiTextField2_add(frmObj,mtfName,numFields)
{
    var list  = frmObj[mtfName+'[]'];
    var i     = list.length;
    
    list.options[i] = new Option(_MIOLO_MultiTextField2_getInput(frmObj,mtfName,numFields));
    
    for ( var j=0; j<=i; j++ )
    {
        list.options[i].selected = (j==i);
    }
}

/**
 * Funcção que exclui o item atualmente selecionado
 */
function _MIOLO_MultiTextField2_remove(frmObj,mtfName,numFields)
{
    _MIOLO_MultiTextField_remove(frmObj,mtfName);	
}

/**
 * 
 */
function _MIOLO_MultiTextField2_modify(frmObj,mtfName,numFields)
{
    var list  = frmObj[mtfName+'[]'];
    
    var i = list.selectedIndex;
    
    if ( i != -1 )
    {
        list.options[i].text = _MIOLO_MultiTextField2_getInput(frmObj,mtfName,numFields);
    }
    
    else
    {
        alert('É preciso selecionar o item a ser modificado!');
    }
}

/**
 * 
 */
function _MIOLO_MultiTextField2_moveUp(frmObj,mtfName,numFields)
{
    var list  = frmObj[mtfName+'[]'];
    
    var i = list.selectedIndex;
    
    if ( i != -1 )
    {
	if ( i > 0 )
	{
	    var u = list.options[i-1].text;
	    
            list.options[i-1].text = list.options[i].text;
	    list.options[i-1].selected = true;
	    
	    list.options[i].text = u;
	    list.options[i].selected = false;
	    
	    list.selectedIndex = i - 1;
	}
    }
    
    else
    {
        alert('É preciso selecionar o item a ser modificado!');
    }
}

/**
 * 
 */
function _MIOLO_MultiTextField2_moveDown(frmObj,mtfName,numFields)
{
    var list  = frmObj[mtfName+'[]'];
    
    var i = list.selectedIndex;
    
    if ( i != -1 )
    {
	if ( i < list.options.length - 1 )
	{
	    var u = list.options[i+1].text;
	    
            list.options[i+1].text = list.options[i].text;
	    list.options[i+1].selected = true;
	    
	    list.options[i].text = u;
	    list.options[i].selected = false;
	    
	    list.selectedIndex = i + 1;
	}
    }
    
    else
    {
        alert('É preciso selecionar o item a ser modificado!');
    }
}

/**
 *
 */
function toggleLayer(name)
{
    if ( document.all != null )
    {
        // alert('document.all');
        
        var theLayer = document.all[name];
        
        if ( theLayer != null )
        {
            if ( theLayer.style.visibility != 'visible' )
            {
                theLayer.style.visibility = 'visible';
            }
            else
            {
                theLayer.style.visibility = 'hidden';
            }
        }
    }
    
    // Netscape e compativeis
    else if ( document.layers != null )
    {
        // alert('document.layers');
        
        var theLayer = document.layers[name];
        
        if ( theLayer != null )
        {
            if ( theLayer.visibility != 'show' )
            {
                theLayer.visibility = 'show';
            }
            else
            {
                theLayer.visibility = 'hidden';
            }
        }
    }
    
    // Konqueror e compativeis
    else if ( document.getElementById != null )
    {
        // alert('getElementById');
        
        var theLayer = document.getElementById(name);
        
        if ( theLayer != null )
        {
            if ( theLayer.style.visibility != 'visible' )
            {
                theLayer.style.visibility = 'visible';
            }
            else
            {
                theLayer.style.visibility = 'hidden';
            }
        }
    }
    
    /*
    else
        alert('Layer ' + name + ' not found!');
    */
}

function LookupContext()
{
}

function MIOLO_AutoComplete(lookup)
{
    var url = 'autocomplete.php' +
              '?module='  + escape(lookup.module) +
              '&item='    + escape(lookup.item) +
              '&related=' + escape(lookup.related) +
              '&form='    + escape(lookup.form.name) +
              '&field='   + escape(lookup.field) +
              '&value='   + escape(lookup.form[lookup.field].value);
              
    top.frames['util'].location = url;
}

function MIOLO_Lookup(lookup)
{
    var url = 'lookup.php' +
              '?module='  + escape(lookup.module) +
              '&item='    + escape(lookup.item) +
              '&related=' + escape(lookup.related) +
              '&form='    + escape(lookup.form.name) +
              '&field='   + escape(lookup.field) +
              '&value='   + escape(lookup.form[lookup.field].value);

    window.open(url,'lookup',
                'toolbar=no,width='+screen.width+',height='+screen.height+',scrollbars=yes,' +
                'top=0,left=0,statusbar=yes,resizeable=yes');
}

function MIOLO_Deliver()
{   
    var related = lookup.related.split(',');
    var count   = MIOLO_Deliver.arguments.length;

    lookup.form[lookup.field].value = MIOLO_Deliver.arguments[0];
    
    if ( lookup.isajax == true )
    {
        lookup.form[lookup.field].onchange();
    }
    else
    {
        for( var i=1; i<count; i++ )
        {
            var value = MIOLO_Deliver.arguments[i];
            var field = lookup.form['frm_'+related[i-1]];

            if ( field != null )
            {
                field.value = value;
            }
        }
    }
    
    close();
}

function MIOLO_Validator()
{
}

/**
 * MIOLO Form Validation Handler
 */
function MIOLO_Validate_Input(validations)
{
    var error = '';
    var count = 0;
    var field = null;

    for ( var i=0; i<validations.length; i++)
    {
        var e = MIOLO_Validate(validations[i]);

        if ( e != null && e != '' )
        {
            if ( error != '' )
            {
                error += '\n';
            }
            error += '- ' + e;
            if ( field == null )
            {
                field = eval('document.'+validations[i].form+'.'+validations[i].field);
            }
            count++;
        }
    }

    if ( error != '' )
    {
        if ( count > 1 )
        {
            error = 'Os seguintes erros foram detectados:\n' + error;
        }
        else
        {
            error = 'O seguinte erro foi detectado:\n' + error;
        }

        alert(error);

        if ( field != null )
        {
            field.focus();
        }
    }

    return error == '';
}

function MIOLO_Validate(validator)
{
    if ( validator.type == 'ignore')
    {
        return null;
    }
    
    var req      = '';
    var field    = eval('document.'+validator.form+'.'+validator.field);
    var value    = field.value;
    var error    = null;
    
    if ( validator.type == 'required' )
    {
        req = 'yes';
    }
    
    if ( (req != '' || value.length > 0) && value.length < validator.min )
    {
        error = 'O campo "' + validator.label + '" deve conter no mínimo ' +
                validator.min + ' caracteres';
    }
    
    if ( value.length > validator.max )
    {
        error = 'O campo "' + validator.label + '" deve conter no máximo ' +
                validator.max + ' caracteres';
    }
    
    if ( validator.chars != 'ALL')
    {
        for ( var i=0; i<value.length; i++ )
        {
            var c = value.charAt(i);
            
            if ( validator.chars.indexOf(c) == -1 )
            {
                error = 'O carater "' + c + '" é inválido para o campo "' + validator.label + '"';
            }
        }
    }
    
    if ( (value.length > 0 || req != '') && validator.mask != '' )
    {
        if ( value.length != validator.mask.length )
        {
            error = 'O campo "' + validator.label + '" deve conter ' +
                    validator.mask.length + ' caracteres';
        }
        else
        {
            for ( var i=0; i<value.length; i++ )
            {
                var m = validator.mask.charAt(i);
                var c = value.charAt(i);
                
                if ( m == '9' )
                {
                    if ( c < '0' || c > '9' )
                    {
                        error = 'O campo "' + validator.label + '" deve conter um dígito numérico na posição ' + (i+1);
                    }
                }
                else if ( m != 'a' )
                {
                    if ( m != c )
                    {
                        error = 'O campo "' + validator.label + '" deve conter o caractere "' + m + '" na posição ' + (i+1);
                    }
                }
            }
        }
    }
    
    if ( (value.length > 0 ||  req != '') && error == null && validator.checker != null )
    {
        if ( ! eval(validator.checker + '(value)') )
        {
            error = 'O conteúdo do campo "' + validator.label + '" está inválido!';
        }
    }
    
    return error;
}

function MIOLO_Validate_Mask(validator, event)
{
    var field = eval('document.'+validator.form+'.'+validator.field);
    var value = field.value;
    var mask  = validator.mask;

    if ( value.length == 0 )
    {
        var i = 0;
        
        while ( i < mask.length )
        {
            var m = mask.charAt(i++);
            
            if ( m == '9' || m == 'a' )
            {
                break;
            }
            
            value += m;
        }
    }

    if ( event != null )
    {
        var key   = event.which ? event.which : event.keyCode;
        var chr = String.fromCharCode( key);

        // alert(event.modifiers);
        
        if ( chr != '' && chr >= ' ' && key !=37)
        {
            if ( validator.max > 0 && value.length > validator.max - 1 )
            {
                value = value.substring(0,validator.max - 1);
            }
            
            value += chr;
            
            var i = value.length;
            
            while ( i < mask.length )
            {
                var m = mask.charAt(i++);
                
                if ( m == '9' || m == 'a' )
                {
                    break;
                }
                
                value += m;
            }
        }
        
        else
        {
            return true;
        }
    }
    
    field.value = value;
    
    return false;
}


function isDigit(chr)
{
    return "0123456789".indexOf(chr) != -1;
}

function returnNumbers(str)
{
    var rs='';
    
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


/*
** Validador CNPJ
** Baseado no script original no CodigoLivre
** http://codigolivre.org.br/snippet/detail.php?type=snippet&id=22
*/
function MIOLO_Validate_Check_CNPJ(CNPJ)
{
    CNPJ = returnNumbers(CNPJ);
    
    if ( CNPJ.length == 14 && CNPJ != '00000000000000' )
    {
        var g = CNPJ.length - 2;
        
        if ( MIOLO_Validate_Verify_CNPJ(CNPJ,g) == 1 )
        {
            g = CNPJ.length - 1;
            
            if( MIOLO_Validate_Verify_CNPJ(CNPJ,g) == 1 )
            {	
                return true;
            }
        }
    }
    
    return false;
}

function MIOLO_Validate_Verify_CNPJ(CNPJ,g)
{
    var VerCNPJ=0;
    var ind=2;
    var tam;
    
    for( f = g; f > 0; f-- )
    {
        VerCNPJ += parseInt(CNPJ.charAt(f-1)) * ind;
        if(ind>8)
        {
            ind=2;
        }
        else
        {
            ind++;
        }
    }
    
    VerCNPJ%=11;
    
    if( VerCNPJ==0 || VerCNPJ==1 )
    {
        VerCNPJ=0;
    }
    else
    {
        VerCNPJ=11-VerCNPJ;
    }
    if( VerCNPJ!=parseInt(CNPJ.charAt(g)) )
    {
        return(0);
    }
    else
    {
        return(1);
    }
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

    if ( ( numcpf == '00000000000') ||
         ( numcpf == '11111111111') ||
         ( numcpf == '22222222222') ||
         ( numcpf == '33333333333') ||
         ( numcpf == '44444444444') ||
         ( numcpf == '55555555555') ||
         ( numcpf == '66666666666') ||
         ( numcpf == '77777777777') ||
         ( numcpf == '88888888888') ||
         ( numcpf == '99999999999')  )
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
    
    len = numcpf.length; x = len -1;
    
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
    x = 11; soma = 0;
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


function MIOLO_Validate_Check_EMAIL(email)
{
    var achou_ponto=false;
    var achou_arroba=false;
    var achou_caracter=false;
    
    for (var i=0; i<email.length; i++) 
    {
        if (email.charAt(i)=="@")
        { 
            if (email.charAt(i+1)==".")
            {
                achou_arroba=false;
            }
            else
            {
                achou_arroba=true;
            }
        }
        else if (email.charAt(i)==".") 
        {
            achou_ponto=true;
        }
        else if (email.charAt(i)!=" ") 
        {
            achou_caracter=true;
        }
    }
    
    if ((email.charAt(0)=="W" || email.charAt(0)=="w") &&
        (email.charAt(1)=="W" || email.charAt(1)=="w") &&
        (email.charAt(2)=="W" || email.charAt(2)=="w") &&
        (email.charAt(3)=="."))
        {
            achou_ponto=false;
            achou_caracter=false;
        }
        
        if(email.charAt(email.length-1)==".")
        {
            achou_ponto=false;
        }	
        
        return (achou_ponto && achou_arroba && achou_caracter);
}

function MIOLO_Validate_Check_DATEDMY(date)
{
    var pos1 = date.indexOf('/');
	var pos2 = date.indexOf('/', pos1+1);

	var strD = date.substring(0,pos1);
	var strM = date.substring(pos1+1,pos2);
	var strY = date.substring(pos2+1);
    
    return ( isDate( strM + '/' + strD + '/' + strY ) == true);
}


function MIOLO_Validate_Check_DATEYMD(date)
{
    var pos1 = date.indexOf('/');
	var pos2 = date.indexOf('/', pos1+1);

	var strY = date.substring(0,pos1);
	var strM = date.substring(pos1+1,pos2);
	var strD = date.substring(pos2+1);
    
    return ( isDate( strM + '/' + strD + '/' + strY ) == true );
}


function MIOLO_Validate_Check_TIME(time)
{
    var h = parseInt( time.substring(0,2) );
    var m = parseInt( time.substring(3,5) );
    
    return ( h >= 0 && h < 24 ) && ( m >= 0 && m < 60 );
}

 /*
 ** DHTML date validation script. 
 ** Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/)
 */
 function isInteger(s)
 {
	var i;
    for ( i = 0; i < s.length; i++ )
    {   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9")))
        {
            return false;
        }
    }
    // All characters are numbers.
    return true;
}


function stripCharsInBag(s, bag)
{
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++)
    {   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year)
{
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}

function DaysArray(n)
{
	for ( var i = 1; i <= n; i++ ) 
    {
		this[i] = 31;
		if ( i==4 || i==6 || i==9 || i==11 ) 
        {
            this[i] = 30;
        }
		if ( i == 2 )
        {
            this[i] = 29;
        }
   } 
   return this;
}

function isDate(dtStr)
{
    dtCh= "/";
    minYear=1900;
    maxYear=2100;

	var daysInMonth = DaysArray(12);
	var pos1        = dtStr.indexOf(dtCh);
	var pos2        = dtStr.indexOf(dtCh,pos1+1);
	var strMonth    = dtStr.substring(0,pos1);
	var strDay      = dtStr.substring(pos1+1,pos2);
	var strYear     = dtStr.substring(pos2+1);
	var strYr       = strYear;
    
	if ( strDay.charAt(0) == "0" && strDay.length>1 ) 
    {
        strDay=strDay.substring(1);
    }
	
    if ( strMonth.charAt(0) == "0" && strMonth.length>1 ) 
    {
        strMonth=strMonth.substring(1);
    }
    
	for ( var i = 1; i <= 3; i++ )
    {
		if ( strYr.charAt(0) == "0" && strYr.length>1 ) 
        {
            strYr=strYr.substring(1);
        }
	}
	
    var month = parseInt(strMonth);
	var day   = parseInt(strDay);
	var year  = parseInt(strYr);
    
	if ( pos1==-1 || pos2==-1 )
    {
		return "The date format should be : mm/dd/yyyy";
	}
    
  	if ( strDay.length < 1 || day < 1 || day > 31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month] )
    {
		return "O Dia informado é inválido. \n(Please enter a valid day.)";
	}
    
	if ( strMonth.length < 1 || month < 1 || month > 12 )
    {
		return "O Mês informado é inválido. \n(Please enter a valid month.)";
	}
    
	if ( strYear.length != 4 || year==0 || year<minYear || year>maxYear )
    {
		return "O Ano deve conter 4 dígitos e estar entre "+minYear+" e "+maxYear+"\n(Please enter a valid 4 digit year between "+minYear+" and "+maxYear+")";
	}
    
	if ( dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false )
    {
		return "Informe uma data válida.";
	}
    
    return true;
}

