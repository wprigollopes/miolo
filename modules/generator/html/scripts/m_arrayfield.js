dojo.declare ("MArrayField", null, {

    context: null,
	
    constructor: function()
    {
	    
    },
    
    parseLookup : function( id )
    {
        newElement = dojo.byId(id );

        dojo.query('div', newElement).forEach(
        
            function ( element )
            {
                dojo.query('div', element).forEach(
                
                    function ( innerElement )
                    {
                        if ( innerElement.className == 'mLookupField') //lookup
                        {
                            //trabalha o id, pegando o id do campo para definir o indice
                            id      = innerElement.childNodes[1].childNodes[1].id;
                            tmp     = id.split('[');
                            tmp     = tmp[1].split(']');
                            index   = tmp[0];
                            marrayfield._parseLookup(innerElement, index);
                        }
                    }
                    );
            }
            );
    },
	
    _parseLookup: function element(innerElement, newValue)
    {
        buttonFind  = innerElement.childNodes[3].childNodes[1];

        //monta o nome do lookup baseado no valor do botão
        lookupName  = buttonFind.onclick.toString().split(':');
        lookupName  = lookupName[1].split('.')[0];
        lookupName  = lookupName.replace(/\n/g,''); // tira linha nova
        lookupName  = lookupName.replace(/\s/g, '' ); //tira espaços
        lookupName  = lookupName.replace(/\r/g,''); //tira outro tipo de linha nova
        lookupName  = lookupName.replace(/\t/g,''); //tira tavb
        lookupName  = lookupName.replace('[0]',''); //tira p [0] limpando a string
        
        newLookupname = lookupName + '['+newValue+']';                                                  // novo nome de acordo com contador
       
        eval( newLookupname + ' = dojo.clone(' + lookupName+');' );                                     //cria a nova variavel do lookup usando o clone
        eval('lookup = ' + newLookupname+';');                                                          //aponta a nova variavel clonada para lookup (pra evitar um monte de eval)
        lookup.context.name     = newLookupname;                                                        //define o nome
        inputField              = buttonFind.parentNode.parentNode.childNodes[1].childNodes[1]          //pega o element input do ALookupField
        lookup.context.filter   = lookup.context.filter+ '['+newValue+']';                              //adiciona valor ao filter
        lookup.context.field    = lookup.context.field.replace( '0', newValue );
        related                 = lookup.context.related.split(',');                                    //explode o related que são os campos para preencher
        
        //passa por todos related adicionando o valor
        for (i = 0; i<related.length ; i++)
        {
            related[i] += '[' + newValue + ']';
        }
      
        lookup.context.related = related.join(',');                                                     //remonta a string
        buttonFind.setAttribute('onclick',"javascript:"+newLookupname+".start(); return false;");       //troca o onlick do botão do lookup
        inputField.setAttribute('onchange',"javascript:"+newLookupname+".start(true); return false;");  //seta o onchange do campo input
    },
	
    add: function (id)
    {
        element 		= dojo.byId(id);
        elementInner 	= dojo.byId(id+'_container');
        newElement 		= dojo.clone(elementInner);
        counter         = dojo.byId( id + '_counter' );
        newValue        = ( counter.value*1) + 1;
        counter.value   = newValue;
        
        //troca id, name, e limpa valor dos inputs
        dojo.query('input' , newElement).forEach(
		
            function ( element )
            {
                element.value   = '';
                element.id      = element.id.replace('0', newValue);
                element.name    = element.name.replace('0', newValue);
            }
		  
            );
		
        //troca id, name, e limpa valor dos selects
        dojo.query('select', newElement).forEach(

            function ( element )
            {
                element.value = '';
                element.id      = element.id.replace('0', newValue);
                element.name    = element.name.replace('0', newValue);
            }
		  
            );
		
        //esconde e mostra o add
        dojo.query('a', newElement).forEach(
    	
            function ( element )
            {
                if ( element.id == id + "_add")
                {
                    element.style.visibility = "hidden";
                }
                else
                {
                    element.style.visibility = "";
                }
            }
    	
            );
    	
        // Percorre os lookups e executa o _parseLookup em cada um
        dojo.query('.mLookupField', newElement).forEach(
        
            function ( innerElement )
            {
                marrayfield._parseLookup(innerElement, newValue);
            }
            );
        
        // Percorre os MDateBox (MCalendarField) e os recria com novo id
        dojo.query('.dijitDateTextBox' , newElement).forEach(
            
            function ( element )
            {
                var newId = dijit.byNode(element).id.replace('0',newValue);
                var calendar = new MDateTextBox( {
                    id: newId
                }, element );
                calendar.valueNode.name = newId;
            }
            );
    	
        //seta valor do campo contador
        counter.value = newValue;
        element.appendChild( newElement );
    },
	
    remove : function (element, id)
    {
        element.parentNode.parentNode.parentNode.removeChild(element.parentNode.parentNode);
    }
});

marrayfield = new MArrayField;