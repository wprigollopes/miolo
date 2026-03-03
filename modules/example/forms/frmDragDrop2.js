//
// Javascript code for frmDragDrop2.class
//
ajaxPhrase = new Miolo.Ajax({
	updateElement: 'divText',
	response_type: 'TEXT',
	remote_method: "ajax_btnTest",
	parameters: function(){
	   var s = '';
	   for (i in ddm_dd2.dropped){
		   alert(i);
		   if ($(i))
		   {
    		   s = s + ((s == '') ? '' : '&') + i + '=' + $(i).innerHTML + ':' + $(i).offsetLeft;
		   }
	   }
	   return {phrase: s};
	}
});
