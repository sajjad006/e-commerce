function func() {
	var x=document.getElementsByName('ordertype');
	if (x[0].checked) {
		document.getElementById('pendingorders').style.display="block";
		document.getElementById('confirmed').style.display="none";
		document.getElementById('shipped').style.display="none";
		document.getElementById('completed').style.display="none";
	}
	else if(x[1].checked){
		document.getElementById('pendingorders').style.display="none";
		document.getElementById('confirmed').style.display="block";
		document.getElementById('shipped').style.display="none";
		document.getElementById('completed').style.display="none";
	}
	else if(x[2].checked){
		document.getElementById('pendingorders').style.display="none";
		document.getElementById('confirmed').style.display="none";
		document.getElementById('shipped').style.display="block";
		document.getElementById('completed').style.display="none";
	}
	else if(x[3].checked){
		document.getElementById('pendingorders').style.display="none";
		document.getElementById('confirmed').style.display="none";
		document.getElementById('shipped').style.display="none";
		document.getElementById('completed').style.display="block";
	}
}