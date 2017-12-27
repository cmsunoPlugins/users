var Users=0; // Check this array : e:email, n:name, d:registration date (unix)
//
function f_usersClic(f){
	var a=document.getElementById('usersBox'),b=document.getElementById(f==1?'usersBo':'usersBl');
	if(a.className.indexOf('w3-hide')!=-1)a.className=a.className.replace('w3-hide','w3-show');
	else{
		a.className=a.className.replace('w3-show','w3-hide');
		if(a.className.indexOf('w3-hide')==-1)a.className+=' w3-hide';
	}
	f_usersNone('');b.className=b.className.replace('w3-hide','w3-show');
	return false;
}
function f_usersLog(){
	var x,n,a,ne=document.getElementById('usersNe').value,pw=document.getElementById('usersPw').value,b=document.getElementById('usersBox');
	x=f_usersAjax(true);
	x.onreadystatechange=function(){
		if(x.readyState==4&&x.status==200){
			a=x.responseText;
			if(a.substr(0,1)=="|")f_usersAlert(a.substr(1),0);
			sessid=/SESS\w*ID=([^;]+)/i.test(document.cookie)?RegExp.$1:false;
			if(a==sessid){
				f_usersOn(ne);
				b.className=b.className.replace('w3-show','w3-hide');
				if(b.className.indexOf('w3-hide')==-1)b.className+=' w3-hide';
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
	var a=document.getElementById('usersOff'),b=document.getElementById('usersOn');
	a.className=a.className.replace('w3-show','w3-hide');
	if(a.className.indexOf('w3-hide')==-1)a.className+=' w3-hide';
	b.className=b.className.replace('w3-hide','w3-show');
	if(b.className.indexOf('w3-show')==-1)b.className+=' w3-show';
	document.getElementById('usersHello').innerHTML=f;
}
function f_usersOut(f){
	var a=document.getElementById('usersOn'),b=document.getElementById('usersOff'),x=f_usersAjax(true);
	a.className=a.className.replace('w3-show','w3-hide');
	if(a.className.indexOf('w3-hide')==-1)a.className+=' w3-hide';
	b.className=b.className.replace('w3-hide','w3-show');
	if(b.className.indexOf('w3-show')==-1)b.className+=' w3-show';
	document.getElementById('usersHello').innerHTML='';
	if(f!=0)x.onreadystatechange=function(){
		if(x.readyState==4&&x.status==200){
			document.cookie='PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
			f_usersAlert(x.responseText,1);
		}
	};
	x.send('a=out');
}
function f_usersReg(){
	var em=document.getElementById('usersEm').value,un=document.getElementById('usersUn').value,x=f_usersAjax(false);
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
			f_usersOut(0);
		}
	};
	x.send('a=unsub');
}
function f_usersPass(){
	var x=f_usersAjax(false);
	x.onreadystatechange=function(){if(x.readyState==4&&x.status==200){f_usersAlert(x.responseText,1);}};
	x.send('a=pass&c='+document.getElementById('usersPc').value+'&n='+document.getElementById('usersPn').value+'&g='+document.getElementById('usersPa').value);
}
function f_usersNone(f){
	var a=['usersBl','usersBr','usersBf','usersBo'],b=['usersPw','usersNe','usersEm','usersUn','usersEf','usersPc','usersPn','usersPa'],v;
	for(v=0;v<a.length;++v)document.getElementById(a[v]).className=document.getElementById(a[v]).className.replace('w3-show','w3-hide');
	if(f!='')document.getElementById(f).className=document.getElementById(f).className.replace('w3-hide','w3-show');
	for(v=0;v<b.length;++v)document.getElementById(b[v]).value="";
}
function f_usersAlert(f,r){
	var a=document.getElementById('usersAlert');
	if(f.substr(0,1)=="!"){
		a.className=a.className.replace('w3-green','');
		if(a.className.indexOf('w3-red')==-1)a.className+=' w3-red';
		f=f.substr(1);b=1;}
	else{
		a.className=a.className.replace('w3-red','');
		if(a.className.indexOf('w3-green')==-1)a.className+=' w3-green';
	}
	a.className=a.className.replace('w3-hide','w3-show');
	a.innerHTML=f;
	setTimeout(function(){
		a.innerHTML='';
		a.className=a.className.replace('w3-show','w3-hide');
		if(a.className.indexOf('w3-hide')==-1)a.className+=' w3-hide';
		if(r)location.reload();
	},2100);
}
function f_usersAjax(f){
	var x=new XMLHttpRequest();
	x.open("POST","uno/plugins/users/usersInc.php",f);
	x.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	x.setRequestHeader('X-Requested-With','XMLHttpRequest');
	return x;
}
function f_usersGravatar(e,s){
	var s=s||80;return '//www.gravatar.com/avatar/'+e+'.jpg?s='+s;
}
//
f_usersCheck();
