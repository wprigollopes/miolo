//
// Javascript code for frmWizard2.class
//

ajaxAdd = new Miolo.Ajax({
	response_type: 'TEXT',
	remote_method: "ajax_btnAdd",
	callback_function: function(result) {
    	var d =  miolo.getElementById('divAdd'); 
		r = document.createElement('DIV');
		r.innerHTML = result;
		d.appendChild(r);
//		alert(d.innerHTML);
//		alert(result);
//    	d.innerHTML = d.innerHTML + result ;
	}
});
