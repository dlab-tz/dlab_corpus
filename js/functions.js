
	//function to process search of the keywords
	function processInsideSearch(value){

		if(value=='' || value==' '){
			document.getElementById("results").innerHTML='';
			return;
			}

		if (window.XMLHttpRequest){
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
			}
		else{
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}


		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("results").innerHTML=xmlhttp.responseText;
				}
			}


		xmlhttp.open("POST","processform.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("searchWord="+value);
		}
		
		
	//function to process search of the keywords
	function processOutsideSearch(value){

		if(value=='' || value==' '){
			document.getElementById("results").innerHTML='';
			return;
			}

		if (window.XMLHttpRequest){
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
			}
		else{
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}


		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("results").innerHTML=xmlhttp.responseText;
				}
			}
		/*
		xmlhttp.open("GET","downloadSearch.php?searchWord="+value,true);
		xmlhttp.send();
		*/
		xmlhttp.open("POST","downloadSearch.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("searchWord="+value);
		}
		
		
	//showing only the first part of user management
	$(document).ready(function(){
		$('#searchUser').addClass('hide').end();
		$('#deleteUser').addClass('hide').end();
		});
	
	//function to automate display of user management contents
	function manageUser(choice){
		if(choice==1){
			$('#addUser').removeClass('hide');
			$('#addUser').addClass('shown').end();
			$('#searchUser').addClass('hide').end();
			$('#deleteUser').addClass('hide').end();
			}
		else if(choice==2){
			$('#searchUser').removeClass('hide');
			$('#searchUser').addClass('shown').end();
			$('#addUser').addClass('hide').end();
			$('#deleteUser').addClass('hide').end();
			}
		else{
			$('#deleteUser').removeClass('hide');
			$('#deleteUser').addClass('shown').end();
			$('#searchUser').addClass('hide').end();
			$('#addUser').addClass('hide').end();
			}
		}
	
		
	//function to process search of the system users to edit
	function searchUser(value){
		
		if (window.XMLHttpRequest){
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
			}
		else{
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}


		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("results").innerHTML=xmlhttp.responseText;
				}
			}
		
		xmlhttp.open("POST","processform.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("searchName="+value);
		}

	
	//function to process search of the system users to delete
	function deleteUser(value){
		
		if (window.XMLHttpRequest){
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
			}
		else{
			// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}


		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("deleteResults").innerHTML=xmlhttp.responseText;
				}
			}
		
		xmlhttp.open("POST","processform.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send("deleteName="+value);
		}
	
	
