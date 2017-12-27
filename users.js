//
// CMSUno
// Plugin Users
//
function f_saveConfig_users(){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/users/users.php',{
			'action':'saveConfig','unox':Unox,
			'g':document.getElementById("usersLang").options[document.getElementById("usersLang").selectedIndex].value,
			'i':document.getElementById("usersInt").options[document.getElementById("usersInt").selectedIndex].value,
			'a':document.getElementById("usersAli").options[document.getElementById("usersAli").selectedIndex].value,
			'c':document.getElementById("usersCol").value
			},function(r){f_alert(r);}
		);
	});
}
function f_save_users(){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/users/users.php',{
			'action':'saveUser','unox':Unox,
			'n':document.getElementById('usersEditN').value,
			'e':document.getElementById('usersEditE').value,
			'p':document.getElementById('usersEditP').value
			},function(r){f_alert(r);document.getElementById('usersEdit').style.display="none";f_load_users();}
		);
	});
}
function f_load_users(){
	jQuery(document).ready(function(){
		jQuery.ajax({type:'POST',url:'uno/plugins/users/users.php',data:{'action':'load','unox':Unox},dataType:'json',async:true,success:function(r){
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
				jQuery.each(r.user,function(k,v){
					d=f_timeConvert(v.s);
					tr=document.createElement('tr');
					td=document.createElement('td');td.innerHTML=v.n;td.onclick=function(){f_psw_users(v.n,this.parentElement)};tr.appendChild(td); // name
					td=document.createElement('td');td.innerHTML=v.e;tr.appendChild(td); // mail
					td=document.createElement('td');td.innerHTML=d;tr.appendChild(td); // date
					td=document.createElement('td');td.onclick=function(){f_bl_users(v.n,1)};td.className='jail';td.title='Black List';tr.appendChild(td);
					td=document.createElement('td');td.onclick=function(){f_del_users(v.n)};td.className='del';tr.appendChild(td);
					t.appendChild(tr);
				});
				jQuery('#usersML').empty();
				document.getElementById('usersML').appendChild(t);
			}
			if(r.black){
				t=document.createElement('table');
				jQuery.each(r.black,function(k,v){
					d=f_timeConvert(v.s);
					tr=document.createElement('tr');
					td=document.createElement('td');td.innerHTML=v.n;tr.appendChild(td); // name
					td=document.createElement('td');td.innerHTML=v.e;tr.appendChild(td); // mail
					td=document.createElement('td');td.innerHTML=d;tr.appendChild(td); // date
					td=document.createElement('td');td.onclick=function(){f_bl_users(v.n,0)};td.className='free';td.title='Free';tr.appendChild(td);
					td=document.createElement('td');td.onclick=function(){f_del_users(v.n)};td.className='del';tr.appendChild(td);
					t.appendChild(tr);
				});
				jQuery('#usersBL').empty();
				document.getElementById('usersBL').appendChild(t);
			}
		jQuery('#usersConfig .color').colorPicker();
		}});
	});
}
function f_del_users(l){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/users/users.php',{'action':'del','unox':Unox,'del':l},function(r){f_alert(r);f_load_users();});
	});
}
function f_bl_users(l,m){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/users/users.php',{'action':'black','unox':Unox,'black':l,'mod':m},function(r){f_alert(r);f_load_users();});
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
	b.onclick=function(){jQuery.post('uno/plugins/users/users.php',{'action':'psw','unox':Unox,'name':f,'psw':document.getElementById('chpsw').value},function(r){
		f_alert(r);jQuery('#chpsw').parent().remove();
	});}
	a.appendChild(b);g.parentElement.insertBefore(a,g);
}
function f_checkN(f){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/users/users.php',{'action':'checkN','unox':Unox,'name':f},function(r){
			document.getElementById('checkN').innerHTML=r;
			if(r.length>1)jQuery('#usersSave').hide();
			else jQuery('#usersSave').show();
		});
	});
}
function f_checkE(f){
	jQuery(document).ready(function(){
		jQuery.post('uno/plugins/users/users.php',{'action':'checkE','unox':Unox,'mail':f},function(r){
			document.getElementById('checkE').innerHTML=r;
			if(r.length>1)jQuery('#usersSave').hide();
			else jQuery('#usersSave').show();
		});
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
	jQuery(g).parent().empty().append('<input type="text" class="input color" name="usersCol" id="usersCol" style="width:100px;" /><span class="del" onclick="f_del_usersColor(this);"></span>');
}
//
f_load_users();
