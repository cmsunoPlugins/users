<?php
session_start(); 
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
if(!isset($_POST['unox']) || $_POST['unox']!=$_SESSION['unox']) {sleep(2);exit;} // appel depuis uno.php
?>
<?php
include('../../config.php');
include('lang/lang.php');
$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true); $Ubusy = $a['nom'];
// ********************* actions *************************************************************************
if (isset($_POST['action']))
	{
	switch ($_POST['action'])
		{
		// ********************************************************************************************
		case 'plugin': ?>
		<link rel="stylesheet" type="text/css" media="screen" href="uno/plugins/users/users.css" />
		<style>
		.del{background:transparent url(<?php echo $_POST['udep']; ?>includes/img/close.png) no-repeat center center;cursor:pointer;padding:0 20px;margin-left:10px}
		</style>
		<div class="blocForm">
			<div id="usersB" class="bouton fr" onClick="f_black_users();" title="<?php echo _("Edit Users in Blacklist");?>"><?php echo _("Blacklist");?></div>
			<div id="usersL" class="bouton fr" onClick="f_list_users();" title="<?php echo _("Edit users list");?>"><?php echo _("Users List");?></div>
			<div id="usersC" class="bouton fr current" onClick="f_config_users();" title="<?php echo _("Configure the plugin");?>"><?php echo _("Config");?></div>
			<h2><?php echo _("users");?></h2>
			<div id="usersConfig">
				<p>
					<?php echo _("If you require that visitors are connected to access some features, this plugin is for you.")." ";?>
					<?php echo _("It adds the login form and allows new members registration.");?>
				</p>
				<p>
					<?php echo _("Just add the shortcode");?>&nbsp;<code>[[users]]</code>&nbsp;<?php echo _("in your template or in your page to display the login/registration link.")." ";?>
				</p>
				<h3><?php echo _("Settings");?></h3>
				<table class="hForm">
					<tr>
						<td><label><?php echo _("Integration");?></label></td>
						<td>
							<select name="usersInt" id="usersInt">
								<option value="shortcode"><?php echo _("Shortcode");?></option>
								<option value="menu"><?php echo _("Menu");?></option>
							</select>
						</td>
						<td><em><?php echo _("Use the shortcode [[users]] or use auto integration in the menu.");?></em></td>
					</tr>
					<tr>
						<td><label><?php echo _("Box alignment");?></label></td>
						<td>
							<select name="usersAli" id="usersAli">
								<option value="left"><?php echo _("Left");?></option>
								<option value="right"><?php echo _("Right");?></option>
							</select>
						</td>
						<td><em><?php echo _("Use Right if the login appears to the right of the window.");?></em></td>
					</tr>
					<tr>
						<td><label><?php echo _("Box color");?></label></td>
						<td><input type="text" class="input color" name="usersCol" id="usersCol" style="width:100px;" /><span class="del" onclick="f_del_usersColor(this);"></span></td>
						<td><em><?php echo _("Background color for the dialog box. HTML format (ex : #9f9f9f). Leave blank for automatic choice.");?></em></td>
					</tr>
				</table>
				<div class="bouton fr" onClick="f_saveConfig_users();" title="<?php echo _("Save settings");?>"><?php echo _("Save");?></div>
				<div class="clear"></div>
			</div>
			<div id="usersList" class="usersList" style="display:none;">
				<div class="bouton" onClick="f_new_users();" title="<?php echo _("Add a new User");?>"><?php echo _("New User");?></div>
				<div id="usersEdit" style="display:none;">
					<table>
						<tr>
							<td><label><?php echo _("Pseudo");?></label></td>
							<td><input name="usersEditN" id="usersEditN" size="20" type="text" onkeyup="f_checkN(this.value)" /><span id="checkN"></span></td>
						</tr>
						<tr>
							<td><label><?php echo _("Mail");?></label></td>
							<td><input name="usersEditE" id="usersEditE" size="20" type="text" onkeyup="f_checkE(this.value)" /><span id="checkE"></span></td>
						</tr>
						<tr>
							<td><label><?php echo _("Password");?></label></td>
							<td>
								<input name="usersEditP" id="usersEditP" size="20" type="password" />
							</td>
						</tr>
						<tr>
							<td>
								<div id="usersSave" class="bouton" onClick="f_save_users();" title="<?php echo _("Save this user");?>"><?php echo _("Save");?></div>
							</td>
							<td></td>
						</tr>
					</table>
				</div>
				<h3><?php echo _("Users List");?></h3>
				<div id="usersML" class="userTab"></div>
			</div>
			<div id="usersBlack" style="display:none;">
				<h3><?php echo _("Users in Blacklist");?></h3>
				<div id="usersBL" class="userTab"></div>
			</div>
			<div class="clear"></div>
		</div>
		<?php break;
		// ********************************************************************************************
		case 'load':
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json'))
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			echo stripslashes($q);
			}
		else echo 0;
		exit;
		break;
		// ********************************************************************************************
		case 'del':
		$l = $_POST['del'];
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json') && $l)
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			$b = 0;
			if(isset($a['user']))
				{
				foreach($a['user'] as $k=>$v) { if($v['n']==$l) {unset($a['user'][$k]); $b = 1; }}
				if($b==0) echo '!'._('Error');
				else
					{
					$out = json_encode($a);
					if(file_put_contents('../../data/_sdata-'.$sdata.'/users.json', $out)) echo _('User deleted');
					else echo '!'._('Undeletable');
					}
				}
			}
		else echo '!'._('No data');
		break;
		// ********************************************************************************************
		case 'black':
		$l = $_POST['black'];
		$m = $_POST['mod'];
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json') && $l)
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			$b = 0;
			if($m==1 && isset($a['user']))
				{
				foreach($a['user'] as $k=>$v)
					{
					if($v['n']==$l)
						{
						$a['black'][$k] = $a['user'][$k];
						unset($a['user'][$k]);
						$b = 1;
						}
					}
				}
			if(!$m && isset($a['black']))
				{
				foreach($a['black'] as $k=>$v)
					{
					if($v['n']==$l)
						{
						$a['user'][$k] = $a['black'][$k];
						unset($a['black'][$k]);
						$b = 1;
						}
					}
				}
			if($b==0) echo '!'._('Error');
			else
				{
				$out = json_encode($a);
				if(file_put_contents('../../data/_sdata-'.$sdata.'/users.json', $out)) echo _('Done');
				else echo '!'._('Error');
				}
			}
		else echo '!'._('No data');
		break;
		// ********************************************************************************************
		case 'saveConfig':
		if(strip_tags($_POST['i']) && strip_tags($_POST['a']))
			{
			if(file_exists('../../data/_sdata-'.$sdata.'/users.json'))
				{
				$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
				$a = json_decode($q,true);
				}
			else $a = array();
			$a['i'] = strip_tags($_POST['i']);
			$a['a'] = strip_tags($_POST['a']);
			$a['c'] = strip_tags($_POST['c']);
			$out = json_encode($a);
			if(file_put_contents('../../data/_sdata-'.$sdata.'/users.json', $out)) echo _('Saved');
			}
		else echo '!'._('Error');
		break;
		// ********************************************************************************************
		case 'saveUser':
		if(strip_tags($_POST['e']) && strip_tags($_POST['n']) && strip_tags($_POST['p']))
			{
			if(!filter_var(strip_tags($_POST['e']),FILTER_VALIDATE_EMAIL))
				{
				echo '!'._("Bad email format");
				break;
				}
			if(file_exists('../../data/_sdata-'.$sdata.'/users.json'))
				{
				$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
				$a = json_decode($q,true);
				}
			else $a = array();
			$a['user'][strip_tags($_POST['n'])] = array("e"=>strip_tags($_POST['e']), "n"=>strip_tags($_POST['n']), "p"=>crypt($_POST['p']), "s"=>time());
			$out = json_encode($a);
			if(file_put_contents('../../data/_sdata-'.$sdata.'/users.json', $out)) echo _('User added');
			}
		else echo '!'._('Error');
		break;
		// ********************************************************************************************
		case 'checkN':
		$l = $_POST['name'];
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json') && $l)
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			if(isset($a['user']))
				{
				foreach($a['user'] as $k=>$v)
					{
					if($v['n']==$l) { echo _('Already exist'); die(); }
					}
				}
			}
		echo "";
		break;
		// ********************************************************************************************
		case 'checkE':
		$l = $_POST['mail'];
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json') && $l)
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			if(isset($a['user']))
				{
				foreach($a['user'] as $k=>$v)
					{
					if($v['e']==$l) { echo _('Already exist'); die(); }
					}
				}
			}
		echo "";
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
?>
