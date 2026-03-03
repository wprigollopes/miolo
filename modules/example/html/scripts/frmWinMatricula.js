//
// Javascript code for frmWinMatricula.class
//
ajaxSelAluno = new Miolo.Ajax({
	updateElement: 'm_selAluno',
	response_type: 'TEXT',
	remote_method: "ajax_btnSelAluno",
	parameters: function(){
       sel = miolo.getElementById("letterSelection");
	   return {value: sel.value};
	}
});

ajaxSelCurso = new Miolo.Ajax({
	updateElement: 'm_selCurso',
	response_type: 'TEXT',
	remote_method: "ajax_btnSelCurso"
});

ajaxSelSala = new Miolo.Ajax({
	updateElement: 'm_selSala',
	response_type: 'TEXT',
	remote_method: "ajax_btnSelSala"
});