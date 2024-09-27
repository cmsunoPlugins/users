//
// CMSUno
// Plugin Users
//
function f_saveConfig_users(){
	let x=new FormData();
	x.set('action','saveConfig');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('g',document.getElementById("usersLang").options[document.getElementById("usersLang").selectedIndex].value);
	x.set('i',document.getElementById("usersInt").options[document.getElementById("usersInt").selectedIndex].value);
	x.set('a',document.getElementById("usersAli").options[document.getElementById("usersAli").selectedIndex].value);
	x.set('c',document.getElementById("usersCol").value);
	fetch('uno/plugins/users/users.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(r=>f_alert(r));
}
function f_save_users(){
	let x=new FormData();
	x.set('action','saveUser');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('n',document.getElementById('usersEditN').value);
	x.set('e',document.getElementById('usersEditE').value);
	x.set('p',document.getElementById('usersEditP').value);
	fetch('uno/plugins/users/users.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		document.getElementById('usersEdit').style.display="none";
		f_load_users();
	});
}
function f_load_users(){
	let x=new FormData();
	x.set('action','load');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	fetch('uno/plugins/users/users.php?r='+Math.random(),{method:'post',body:x})
	.then(r=>r.json())
	.then(function(r){
		if(r.i){
			t=document.getElementById("usersInt");
			to=t.options;
			for(v=0;v<to.length;v++){if(to[v].value==r.i){to[v].selected=true;v=to.length;}}
		}
		if(r.a){
			t=document.getElementById("usersAli");
			to=t.options;
			for(v=0;v<to.length;v++){if(to[v].value==r.a){to[v].selected=true;v=to.length;}}
		}
		if(r.c!=undefined)document.getElementById('usersCol').value=r.c;
		if(r.user){
			t=document.createElement('table');
			for(let k in r.user){
				let v=r.user[k];
				d=f_timeConvert(v.s);
				tr=document.createElement('tr');
				td=document.createElement('td');td.innerHTML=v.n;td.onclick=function(){f_psw_users(v.n,this.parentElement)};tr.appendChild(td); // name
				td=document.createElement('td');td.innerHTML=v.e;tr.appendChild(td); // mail
				td=document.createElement('td');td.innerHTML=d;tr.appendChild(td); // date
				td=document.createElement('td');td.onclick=function(){f_bl_users(v.n,1)};td.className='jail';td.title='Black List';tr.appendChild(td);
				td=document.createElement('td');td.onclick=function(){f_del_users(v.n)};td.className='del';tr.appendChild(td);
				t.appendChild(tr);
			}
			document.getElementById("usersML").innerHTML='';
			document.getElementById('usersML').appendChild(t);
		}
		if(r.black){
			t=document.createElement('table');
			for(let k in r.black){
				let v=r.black[k];
				d=f_timeConvert(v.s);
				tr=document.createElement('tr');
				td=document.createElement('td');td.innerHTML=v.n;tr.appendChild(td); // name
				td=document.createElement('td');td.innerHTML=v.e;tr.appendChild(td); // mail
				td=document.createElement('td');td.innerHTML=d;tr.appendChild(td); // date
				td=document.createElement('td');td.onclick=function(){f_bl_users(v.n,0)};td.className='free';td.title='Free';tr.appendChild(td);
				td=document.createElement('td');td.onclick=function(){f_del_users(v.n)};td.className='del';tr.appendChild(td);
				t.appendChild(tr);
			}
			document.getElementById("usersBL").innerHTML='';
			document.getElementById('usersBL').appendChild(t);
		}
		colorPick("#usersConfig .color");
	});
}
function f_del_users(l){
	let x=new FormData();
	x.set('action','del');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('del',l);
	fetch('uno/plugins/users/users.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		f_load_users();
	});
}
function f_bl_users(l,m){
	let x=new FormData();
	x.set('action','black');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('black',l);
	x.set('mod',m);
	fetch('uno/plugins/users/users.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		f_alert(r);
		f_load_users();
	});
}
function f_black_users(){
	document.getElementById('usersConfig').style.display="none";
	document.getElementById('usersList').style.display="none";
	document.getElementById('usersBlack').style.display="block";
	document.getElementById('usersC').className="bouton fr";
	document.getElementById('usersL').className="bouton fr";
	document.getElementById('usersB').className="bouton fr current";
}
function f_list_users(){
	document.getElementById('usersConfig').style.display="none";
	document.getElementById('usersList').style.display="block";
	document.getElementById('usersBlack').style.display="none";
	document.getElementById('usersC').className="bouton fr";
	document.getElementById('usersL').className="bouton fr current";
	document.getElementById('usersB').className="bouton fr";
}
function f_config_users(){
	document.getElementById('usersConfig').style.display="block";
	document.getElementById('usersList').style.display="none";
	document.getElementById('usersBlack').style.display="none";
	document.getElementById('usersC').className="bouton fr current";
	document.getElementById('usersL').className="bouton fr";
	document.getElementById('usersB').className="bouton fr";
}
function f_new_users(){
	document.getElementById('usersEditN').value='';
	document.getElementById('usersEditE').value='';
	document.getElementById('usersEditP').value='';
	if(document.getElementById('usersEdit').style.display=="block")document.getElementById('usersEdit').style.display="none";
	else document.getElementById('usersEdit').style.display="block";
}
function f_psw_users(f,g){
	var a=document.createElement('div'),b=document.createElement('input');
	b.id='chpsw';b.type='text';b.placeholder='pass';a.appendChild(b);
	b=document.createElement('button');b.innerHTML='save';
	b.onclick=function(){
		let x=new FormData();
		x.set('action','psw');
		x.set('unox',Unox);
		x.set('ubusy',Ubusy);
		x.set('name',f);
		x.set('psw',document.getElementById('chpsw').value);
		fetch('uno/plugins/users/users.php',{method:'post',body:x})
		.then(r=>r.text())
		.then(function(r){
			f_alert(r);
			document.getElementById('chpsw').parentElement.remove();
		});
	}
	a.appendChild(b);g.parentElement.insertBefore(a,g);
}
function f_checkN(f){
	let x=new FormData();
	x.set('action','checkN');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('name',f);
	fetch('uno/plugins/users/users.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		document.getElementById('checkN').innerHTML=r;
		if(r.length>1)document.getElementById('usersSave').style.display='none';
		else document.getElementById('usersSave').style.display='';
	});
}
function f_checkE(f){
	let x=new FormData();
	x.set('action','checkE');
	x.set('unox',Unox);
	x.set('ubusy',Ubusy);
	x.set('mail',f);
	fetch('uno/plugins/users/users.php',{method:'post',body:x})
	.then(r=>r.text())
	.then(function(r){
		document.getElementById('checkE').innerHTML=r;
		if(r.length>1)document.getElementById('usersSave').style.display='none';
		else document.getElementById('usersSave').style.display='';
	});
}
function f_timeConvert(Timestamp){
	var a=new Date(Timestamp*1000); // ms
	var months=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	var year=a.getFullYear();
	var month=months[a.getMonth()];
	var date=a.getDate();
	var hour=a.getHours();
	var min=a.getMinutes();
	var t=date+' '+month+' '+year+' '+hour+':'+min;
	return t;
}
function f_del_usersColor(f){
	var g=f.parentNode.firstChild;
	document.getElementById(g).parentElement.innerHTML='<input type="text" class="input color" name="usersCol" id="usersCol" style="width:100px;" /><span class="del" onclick="f_del_usersColor(this);"></span>';
}
//
f_load_users();
