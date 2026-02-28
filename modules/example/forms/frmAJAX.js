//
// Javascript code for frmAjax.class
//

// receives a HTML code as result and updates a element automatically
ajaxSelection = new Miolo.Ajax({
	updateElement: 'm_secondSelection',
	response_type: 'TEXT',
	remote_method: "ajax_btnSel",
	parameters: function(){
       sel = miolo.getElementById("firstSelection");
	   return {value: sel.value, option: sel.options[sel.selectedIndex].text};
	}
});

// receives a HTML code as result and updates a element automatically
var ajaxHandlerSample = new Miolo.Ajax({
	updateElement: 'divSample',
	response_type: 'TEXT',
	remote_method: "ajax_sample",
	parameters: function(){
       sel = miolo.getElementById("selSample");
	   return sel.options[sel.selectedIndex].text;
	}
});

// receives a XML code as result and updates a element manually
var ajaxCursoSelection = new Miolo.Ajax({
	response_type: 'XML',
	remote_method: "ajax_btnCurso",
	parameters: function(){
       sel = miolo.getElementById("selCurso");
	   return sel.value;
	},
	callback_function: function(result, xmlText) {
        var nomes = result.getElementsByTagName('nome');
        var disp  = '';
        for (i = 0; i < nomes.length; i++) {
           disp += nomes[i].firstChild.nodeValue + "<br>\n";
        }
		miolo.getElementById('sel3').innerHTML = '<b>Alunos</b><br>' + disp;
        var xml = xmlText.replace( /</g,'&lt;').replace(/>/g,'&gt;');
        xml = xml.replace( /&lt;row/g,'<br>&lt;row');
        miolo.getElementById('sel4').innerHTML = '<br><b>XML Code:</b><br>' + xml;
	}
});

// receives a JSON object as result and updates a element manually
var ajaxCursoSelectionJSON = new Miolo.Ajax({
	response_type: 'JSON',
	remote_method: "ajax_btnCurso",
	parameters: function(){
       sel = miolo.getElementById("selCursoJSON");
	   return sel.value;
	},
	callback_function: function(result) {
        var disp  = '';
        for (i = 0; i < result.aluno[0].nome.length; i++) {
           disp += result.aluno[0].nome[i].data + '<br >';
        }
        miolo.getElementById('selJSON').innerHTML = '<b>Alunos</b><br>' + disp;
	}
});

// receives a JSON object as result from a PHP backend object and updates a element manually
var ajaxCursoSelectionPHPJSON = new Miolo.Ajax({
	response_type: 'JSON',
	remote_method: "ajax_btnGetCurso",
	parameters: function(){
       sel = miolo.getElementById("selCursoPHPJSON");
	   return sel.value;
	},
	callback_function: function(result,text) {
        var curso = result.data;
        miolo.getElementById('selPHPJSON').innerHTML = 'Curso: <b>'+curso.nome+'</b><br>'
		+'Sala: <b>'+curso.sala+'</b><br>';
	}
});

// receives a Javascript object as result and updates a element manually
var ajaxCursoSelectionObject = new Miolo.Ajax({
	response_type: 'OBJECT',
	remote_method: "ajax_btnCurso",
	parameters: function(){
       sel = miolo.getElementById("selCursoObject");
	   return sel.value;
	},
	callback_function: function(result) {
		var alunos = result.ajaxResponse[0].aluno; 
        var disp  = '';
        for (i = 0; i < alunos[0].nome.length; i++) {
           disp += alunos[0].nome[i].data + '<br >';
        }
        miolo.getElementById('selObject').innerHTML = '<b>Alunos</b><br>' + disp;
	}
});

// receives a HTML code as result and updates a element automatically
var ajaxImage = new Miolo.Ajax({
	updateElement: 'sel5',
	response_type: 'TEXT',
	remote_method: "onSelectImage",
	parameters: function(){
	   return miolo.getElementById("selImage").value;
	}
});
