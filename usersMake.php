<?php
if (!isset($_SESSION['cmsuno'])) exit();
?>
<?php
if(file_exists('data/_sdata-'.$sdata.'/users.json'))
	{
	$lang0 = $lang;
	$q = file_get_contents('data/_sdata-'.$sdata.'/users.json');
	$a = json_decode($q,true);
	if(!empty($a['g'])) $lang = $a['g'];
	include('plugins/users/lang/lang.php');
	$integ = 'shortcode';
	$align = 'left';
	$color = (empty($Ua['w3'])?'#eee':'');
	if(!empty($a['i'])) $integ = $a['i'];
	if(!empty($a['a'])) $align = $a['a'];
	if(!empty($a['c'])) $color = $a['c'];
	$a1 = '<div class="'.(isset($Uw3['dropdown']['w3-dropdown-click'])?$Uw3['dropdown']['w3-dropdown-click']:'w3-dropdown-click').' usersSession" id="usersSession" style="z-index:3">
	<button class="'.(isset($Uw3['dropdown']['w3-button'])?$Uw3['dropdown']['w3-button']:'w3-button').'" id="usersOff" onClick="f_usersClic(0);">'.T_("Login").'</button>
	<button class="'.(isset($Uw3['dropdown']['w3-button'])?$Uw3['dropdown']['w3-button']:'w3-button').' w3-hide" id="usersOn" onClick="f_usersClic(1);">'.T_("Hello").'&nbsp;<span id="usersHello"></span>&nbsp;&nbsp;<span style="font-size:120%;">&equiv;</span></button>
	<div id="usersBox" class="'.(isset($Uw3['dropdown']['w3-dropdown-content'])?$Uw3['dropdown']['w3-dropdown-content']:'w3-dropdown-content').' '.(isset($Uw3['card']['w3-card'])?$Uw3['card']['w3-card']:'w3-card').' usersBox w3-hide" style="width:214px;'.$align.':0;'.($color?'background-color:'.$color.';':'').'">
		<div class="w3-container">
			<div class="w3-panel w3-hide usersAlert" id="usersAlert"></div>
			<div id="usersBl" class="w3-hide">
				<div class="w3-section">
					<label>'.T_("Username or email").'</label>
					<input class="w3-input" id="usersNe" name="usersNe" value="" type="text" />
					<label>'.T_("Password").'</label>
					<input class="w3-input" id="usersPw" name="usersPw" value="" type="password" />
				</div>
				<div class="w3-section">
					<button class="'.(isset($Uw3['card']['w3-button'])?$Uw3['card']['w3-button']:'w3-button').'" onClick="f_usersLog();">'.T_("Login").'</button>
					<div class="usersLink">
						<a href="JavaScript:void(0);" onClick="f_usersNone(\'usersBf\');">'.T_("Forgot your password").'</a><br />
						<a href="JavaScript:void(0);" onClick="f_usersNone(\'usersBr\');">'.T_("Register").'</a>
					</div>
				</div>
			</div>
			<div id="usersBr" class="w3-hide">
				<div class="w3-section">
					<label>'.T_("Email").'</label>
					<input class="w3-input" id="usersEm" name="usersEm" value="" type="text" />
					<label>'.T_("Username").'</label>
					<input class="w3-input" id="usersUn" name="usersUn" value="" type="text" />
				</div>
				<div class="w3-section">
					<button class="'.(isset($Uw3['card']['w3-button'])?$Uw3['card']['w3-button']:'w3-button').'" onClick="f_usersReg();">'.T_("Register").'</button>
				</div>
			</div>
			<div id="usersBf" class="w3-hide">
				<div class="w3-section">
					<label>'.T_("Email").'</label>
					<input class="w3-input" id="usersEf" name="usersEf" value="" type="text" />
				</div>
				<div class="w3-section">
					<button class="'.(isset($Uw3['card']['w3-button'])?$Uw3['card']['w3-button']:'w3-button').'" onClick="f_usersRec();">'.T_("Recover").'</button>
				</div>
			</div>
			<div id="usersBo" class="w3-hide">
				<div class="usersLink">
					<a href="JavaScript:void(0);" onClick="f_usersOut(1);">'.T_("Logout").'</a><br />
					<a href="JavaScript:void(0);" onClick="f_usersNone(\'usersBp\');">'.T_("Change Password").'</a><br />
					<a href="JavaScript:void(0);" onClick="f_usersUnsub();">'.T_("Unsubscribe").'</a>
				</div>
			</div>
			<div id="usersBp" class="w3-hide">
				<div class="w3-section">
					<label>'.T_("Current Password").'</label>
					<input class="w3-input" id="usersPc" name="usersPc" value="" type="password" />
					<label>'.T_("New Password").'</label>
					<input class="w3-input" id="usersPn" name="usersPn" value="" type="password" />
					<label>'.T_("New Password again").'</label>
					<input class="w3-input" id="usersPa" name="usersPa" value="" type="password" />
				</div>
				<div class="w3-section">
					<button class="'.(isset($Uw3['card']['w3-button'])?$Uw3['card']['w3-button']:'w3-button').'" onClick="f_usersPass();">'.T_("Save").'</button>
				</div>
			</div>
		</div>
	</div></div><!-- #usersSession -->'."\r\n";
	if(empty($Ua['w3'])) $Uhead .= '<link rel="stylesheet" href="uno/plugins/users/usersInc.css" type="text/css" />'."\r\n";
	$Ufoot .= '<script type="text/javascript" src="uno/plugins/users/usersInc.js"></script>'."\r\n";
	if($integ=='shortcode')
		{
		$Uhtml = str_replace('[[users]]',"\r\n".$a1, $Uhtml); // template
		$Ucontent = str_replace('[[users]]',"\r\n".$a1, $Ucontent); // editor
		}
	else if($integ=='menu') $Umenu .= '<li>'.$a1.'</li>';
	$lang = $lang0;
	}
?>
