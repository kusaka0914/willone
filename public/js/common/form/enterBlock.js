function BlockEnter(evt){
	evt = (evt) ? evt : event;
	var charCode=(evt.charCode) ? evt.charCode :((evt.which) ? evt.which : evt.keyCode);
	if ( Number(charCode) == 13 || Number(charCode) == 3) {
		return false;
	} else {
		return true;
	}
}