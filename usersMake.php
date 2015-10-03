<?php
if (!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(file_exists('data/_sdata-'.$sdata.'/users.json'))
	{
	include('plugins/users/lang/lang.php');
	$integ = 'shortcode';
	$align = 'left';
	$color = '';
	$q = file_get_contents('data/_sdata-'.$sdata.'/users.json');
	$a = json_decode($q,true);
	if(isset($a['i'])) $integ = $a['i'];
	if(isset($a['a'])) $align = $a['a'];
	if(isset($a['c'])) $color = $a['c'];
	$a1='<div id="usersSession" class="usersSession">
		<a id="usersOff" href="JavaScript:void(0);" onClick="f_usersClicOff();">'._("Login").'</a>
		<a id="usersOn" href="JavaScript:void(0);" onClick="f_usersClicOn();" style="display:none;">'._("Hello").'&nbsp;<span id="usersHello"></span>&nbsp;&nbsp;<span style="font-size:120%;">&equiv;</span></a>
		<div id="usersBox" class="usersBox" style="'.$align.':0;'.($color?'background-color:'.$color.';':'').'">
			<div class="usersAlert" id="usersAlert"></div>
			<div id="usersBl" style="display:none;">
				<fieldset>
					<label>'._("Username or email").'</label>
					<input id="usersNe" name="usersNe" value="" type="text" />
					<label>'._("Password").'</label>
					<input id="usersPw" name="usersPw" value="" type="password" />
				</fieldset>
				<input type="button" class="button" value="'._("Login").'" onClick="f_usersLog();" />
				<div class="usersLink">
					<a href="JavaScript:void(0);" onClick="f_usersNone();document.getElementById(\'usersBf\').style.display=\'block\';">'._("Forgot your password").'</a><br />
					<a href="JavaScript:void(0);" onClick="f_usersNone();document.getElementById(\'usersBr\').style.display=\'block\';">'._("Register").'</a>
				</div>
			</div>
			<div id="usersBr" style="display:none;">
				<fieldset>
					<label>'._("Email").'</label>
					<input id="usersEm" name="usersEm" value="" type="text" />
					<label>'._("Username").'</label>
					<input id="usersUn" name="usersUn" value="" type="text" />
				</fieldset>
				<input type="button" class="button" value="'._("Register").'" onClick="f_usersReg();" />
			</div>
			<div id="usersBf" style="display:none;">
				<fieldset>
					<label>'._("Email").'</label>
					<input id="usersEf" name="usersEf" value="" type="text" />
				</fieldset>
				<input type="button" class="button" value="'._("Recover").'" onClick="f_usersRec();" />
			</div>
			<div id="usersBo" style="display:none;">
				<div class="usersLink">
					<a href="JavaScript:void(0);" onClick="f_usersOut();">'._("Logout").'</a><br />
					<a href="JavaScript:void(0);" onClick="f_usersNone();document.getElementById(\'usersBp\').style.display=\'block\';">'._("Change Password").'</a><br />
					<a href="JavaScript:void(0);" onClick="f_usersUnsub();">'._("Unsubscribe").'</a>
				</div>
			</div>
			<div id="usersBp" style="display:none;">
				<fieldset>
					<label>'._("Current Password").'</label>
					<input id="usersPc" name="usersPc" value="" type="password" />
					<label>'._("New Password").'</label>
					<input id="usersPn" name="usersPn" value="" type="password" />
					<label>'._("New Password again").'</label>
					<input id="usersPa" name="usersPa" value="" type="password" />
				</fieldset>
				<input type="button" class="button" value="'._("Save").'" onClick="f_usersPass();" />
			</div>
		</div><!-- #usersBox -->
	</div><!-- #usersSession -->'."\r\n";
	$Uhead .= '<link rel="stylesheet" href="uno/plugins/users/usersInc.css" type="text/css" />'."\r\n";
	$Ufoot .= '<script type="text/javascript" src="uno/plugins/users/usersInc.js"></script>'."\r\n";
	if($integ=='shortcode')
		{
		$Uhtml = str_replace('[[users]]',"\r\n".$a1, $Uhtml); // template
		$Ucontent = str_replace('[[users]]',"\r\n".$a1, $Ucontent); // editor
		}
	else if($integ=='menu') $Umenu .= '<li>'.$a1.'</li>';
	}
?>
