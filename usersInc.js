var Users=0; // Check this array : e:email, n:name, d:registration date (unix)
//
function f_usersClicOff(){
	if(document.getElementById('usersBox').className=='usersBox boxOn'&&document.getElementById('usersBl').style.display=='block'){
		document.getElementById('usersBox').className='usersBox boxOff';
	}
	else document.getElementById('usersBox').className='usersBox boxOn';
	f_usersNone();document.getElementById('usersBl').style.display='block';
	return false;
}
function f_usersClicOn(){
	if(document.getElementById('usersBox').className=='usersBox boxOn'&&document.getElementById('usersBo').style.display=='block'){
		document.getElementById('usersBox').className='usersBox boxOff';
	}
	else document.getElementById('usersBox').className='usersBox boxOn';
	f_usersNone();document.getElementById('usersBo').style.display='block';
	return false;
}
function f_usersLog(){
	var x,ne,pw,n,a;
	ne=document.getElementById('usersNe').value;pw=document.getElementById('usersPw').value;
	x=f_usersAjax(true);
	x.onreadystatechange=function(){
		if (x.readyState==4 && x.status==200){
			a=x.responseText;
			if(a.substr(0,1)=="|")f_usersAlert(a.substr(1),0);
			sessid=/SESS\w*ID=([^;]+)/i.test(document.cookie)?RegExp.$1:false;
			if(a==sessid){
				f_usersOn(ne);
				document.getElementById('usersBox').style.display='none';
				location.reload();
			}
		}
	};
	x.send('a=log&n='+ne+'&p='+pw);
}
function f_usersCheck(){
	if(sessid=/SESS\w*ID=([^;]+)/i.test(document.cookie)?RegExp.$1:false){
		var x=f_usersAjax(true);
		x.onreadystatechange=function(){if(x.readyState==4&&x.status==200&&x.responseText!=false){
			Users=eval('('+x.responseText+')');
			f_usersOn(Users['n']);};
		}
		x.send('a=check&s='+sessid);
	}
}
function f_usersOn(f){
	document.getElementById('usersOff').style.display='none';
	document.getElementById('usersOn').style.display='inline';
	document.getElementById('usersHello').innerHTML=f;
}
function f_usersOut(){
	document.getElementById('usersOff').style.display='inline';
	document.getElementById('usersOn').style.display='none';
	document.getElementById('usersHello').innerHTML='';
	var x=f_usersAjax(true);
	x.onreadystatechange=function(){
		if (x.readyState==4&&x.status==200){
			document.cookie='PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
			f_usersAlert(x.responseText,1);
		}
	};
	x.send('a=out');
}
function f_usersReg(){
	var x,em,un;
	em=document.getElementById('usersEm').value;un=document.getElementById('usersUn').value;
	x=f_usersAjax(false);
	x.onreadystatechange=function(){if(x.readyState==4&&x.status==200)f_usersAlert(x.responseText,0);};
	x.send('a=reg&e='+em+'&u='+un);
}
function f_usersRec(){
	var x=f_usersAjax(false);
	x.onreadystatechange=function(){if(x.readyState==4&&x.status==200)f_usersAlert(x.responseText,0);};
	x.send('a=rec&e='+document.getElementById('usersEf').value);
}
function f_usersUnsub(){
	var x=f_usersAjax(true);
	x.onreadystatechange=function(){
		if (x.readyState==4&&x.status==200){
			f_usersAlert(x.responseText,1);
			document.getElementById('usersOff').style.display='inline';
			document.getElementById('usersOn').style.display='none';
		}
	};
	x.send('a=unsub');
}
function f_usersPass(){
	var x=f_usersAjax(false);
	x.onreadystatechange=function(){if(x.readyState==4&&x.status==200){f_usersAlert(x.responseText,1);}};
	x.send('a=pass&c='+document.getElementById('usersPc').value+'&n='+document.getElementById('usersPn').value+'&g='+document.getElementById('usersPa').value);
}
function f_usersNone(){
	document.getElementById('usersBl').style.display='none';
	document.getElementById('usersBr').style.display='none';
	document.getElementById('usersBf').style.display='none';
	document.getElementById('usersBo').style.display='none';
	document.getElementById('usersBp').style.display='none';
	document.getElementById('usersPw').value="";document.getElementById('usersNe').value="";
	document.getElementById('usersEm').value="";document.getElementById('usersUn').value="";document.getElementById('usersEf').value="";
	document.getElementById('usersPc').value="";document.getElementById('usersPn').value="";document.getElementById('usersPa').value="";
}
function f_usersAlert(f,r){
	var a=document.getElementById('usersAlert');b=0;
	if(f.substr(0,1)=="!"){a.style.color="red";f=f.substr(1);b=1;}
	else a.style.color="green";
	a.innerHTML=f;
	setTimeout(function(){a.innerHTML="";if(b==0&&f.length>1)document.getElementById('usersAlert').innerHTML='';if(r)location.reload();},2100);
}
function f_usersAjax(f){
	var x=new XMLHttpRequest();
	x.open("POST","uno/plugins/users/usersInc.php",f);
	x.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	x.setRequestHeader('X-Requested-With','XMLHttpRequest');
	return x;
}
function f_usersGravatar(e,s){
	var s=s||80;return 'http://www.gravatar.com/avatar/'+e+'.jpg?s='+s;
}
//
f_usersCheck();
