<?php
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])!='xmlhttprequest') {sleep(2);exit;} // ajax request
$q = file_get_contents('../../data/busy.json'); $a = json_decode($q,true); $Ubusy = $a['nom'];
include('../../config.php');
include('lang/lang.php');
// ********************* actions *************************************************************************
if (isset($_POST['a']))
	{
	switch($_POST['a'])
		{
		case 'reg':
		if(isset($_POST['e']) && isset($_POST['u']))
			{
			if(!filter_var(strip_tags($_POST['e']),FILTER_VALIDATE_EMAIL))
				{
				echo '!'._("Bad email format");
				break;
				}
			if(!file_exists('../../data/_sdata-'.$sdata.'/users.json')) $a=array();
			else
				{
				$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
				$a = json_decode($q,true);
				if(is_array($a)) foreach($a['user'] as $r)
					{
					if(strip_tags($_POST['e']==$r['e']) || strip_tags($_POST['u']==$r['n']))
						{
						echo '!'._("Name or email already assigned");
						die();
						}
					}
				if(is_array($a)) foreach($a['black'] as $r)
					{
					if(strip_tags($_POST['e']==$r['e']))
						{
						echo '!'._("email blacklisted");
						die();
						}
					}
				}
			$pass = f_newPass();
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json'); $b = json_decode($q,true);
			$a['user'][strip_tags($_POST['u'])] = array("e"=>strip_tags($_POST['e']), "n"=>strip_tags($_POST['u']), "p"=>crypt($pass), "s"=>time());
			$out = json_encode($a);
			if(file_put_contents('../../data/_sdata-'.$sdata.'/users.json', $out))
				{
				include '../../template/mailTemplate.php';
				$bottom= str_replace('[[unsubscribe]]',"", $bottom); // template
				if(file_exists('../../data/'.$Ubusy.'/site.json'))
					{
					$q = file_get_contents('../../data/'.$Ubusy.'/site.json');
					$a = json_decode($q,true);
					$rn = "\r\n";
					$boundary = "-----=".md5(rand());
					$body = _("Welcome on")." <a href='".$a['url']."/".$a['nom'].".html'>".$a['tit']."</a><br /><br />".$rn;
					$body .= _("Your login is").": <b>".strip_tags($_POST['u'])."</b><br />".$rn;
					$body .= _("Your password is").": <b>".$pass."</b><br />".$rn;
					$msgT = strip_tags($body);
					$msgH = $top . $body . $bottom;
					$sujet = $a['tit'].' - '. _("Registration");
					$fm = preg_replace("/[^a-zA-Z ]+/", "", $a['tit']);
					$header  = "From: ".$fm."<".$b['mel'].">".$rn."Reply-To:".$fm."<".$b['mel'].">";
					$header.= "MIME-Version: 1.0".$rn;
					$header.= "Content-Type: multipart/alternative;".$rn." boundary=\"$boundary\"".$rn;
					$msg= $rn."--".$boundary.$rn;
					$msg.= "Content-Type: text/plain; charset=\"utf-8\"".$rn;
					$msg.= "Content-Transfer-Encoding: 8bit".$rn;
					$msg.= $rn.$msgT.$rn;
					$msg.= $rn."--".$boundary.$rn;
					$msg.= "Content-Type: text/html; charset=\"utf-8\"".$rn;
					$msg.= "Content-Transfer-Encoding: 8bit".$rn;
					$msg.= $rn.$msgH.$rn;
					$msg.= $rn."--".$boundary."--".$rn;
					$msg.= $rn."--".$boundary."--".$rn;
					if(mail(strip_tags($_POST['e']), stripslashes($sujet), stripslashes($msg),$header))
						{
						echo _("You will receive an email with your password");
						break;
						}
					}
				}
			}
		echo '!'._("Error");
		break;
		// ********************************************************************************************
		case 'log':
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json') && isset($_POST['n']) && isset($_POST['p']))
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			foreach($a['user'] as $r)
				{
				if(($r['e']==$_POST['n'] || $r['n']==$_POST['n']) && $r['p']==crypt(strip_tags($_POST['p']), $r['p']))
					{
					// connect
					session_start();
					session_regenerate_id();
					$_SESSION['name'] = $r['n'];
					// Store some data in the session
					echo session_id();
					die();
					}
				}
			foreach($a['black'] as $r)
				{
				if($r['e']==$_POST['n'] || $r['n']==$_POST['n'])
					{
					echo '|!'._("email blacklisted");
					die();
					}
				}
			echo '|!'._("Unknown user");
			break;
			}
		else echo '|!'._("Error");
		break;
		// ********************************************************************************************
		case 'unsub':
		session_start();
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json') && isset($_SESSION['name']))
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			foreach($a['user'] as $k=>$v)
				{
				if($v['n']==$_SESSION['name'])
					{
					unset($a['user'][$k]);
					$out = json_encode($a);
					if(file_put_contents('../../data/_sdata-'.$sdata.'/users.json', $out))
						{
						echo _("User deleted");
						$_SESSION = array();
						session_destroy();
						die();
						}
					}
				}
			}
		echo '!'._("Error");
		break;
		// ********************************************************************************************
		case 'pass':
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json') && isset($_POST['c']) && isset($_POST['n']) && isset($_POST['g']))
			{
			if($_POST['n']!=$_POST['g'])
				{
				echo '!'._("New passwords different");
				break;
				}
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			foreach($a['user'] as $k=>$v)
				{
				if($v['p']==crypt(strip_tags($_POST['c']), $v['p']) && strlen($_POST['n'])>3)
					{
					$a['user'][$k]['p'] = crypt(strip_tags($_POST['n']));
					$out = json_encode($a);
					if(file_put_contents('../../data/_sdata-'.$sdata.'/users.json', $out))
						{
						echo _("Password changed");
						die();
						}
					}
				}
			}
		echo '!'._("Error");
		break;
		// ********************************************************************************************
		case 'check':
		session_start();
		if(isset($_POST['s']) && session_id()==$_POST['s'] && isset($_SESSION['name']))
			{
			$a1 = array();
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			foreach($a['user'] as $r)
				{
				if($r['n']==$_SESSION['name'])
					{
					$a1['e'] = $r['e'];
					$a1['n'] = $r['n'];
					$a1['s'] = $r['s'];
					echo json_encode($a1);
					exit;
					}
				}
			}
		echo false;
		break;
		// ********************************************************************************************
		case 'out':
		session_start();
		$_SESSION = array();
		session_destroy();
		echo _("See you soon");
		break;
		// ********************************************************************************************
		case 'rec':
		if(file_exists('../../data/_sdata-'.$sdata.'/users.json') && isset($_POST['e']))
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/users.json');
			$a = json_decode($q,true);
			foreach($a['user'] as $k=>$r)
				{
				if($r['e']==$_POST['e'])
					{
					$pass = f_newPass();
					$a['user'][$k]['p'] = crypt($pass);
					$out = json_encode($a);
					include '../../template/mailTemplate.php';
					$bottom= str_replace('[[unsubscribe]]',"", $bottom); // template
					if(file_exists('../../data/'.$Ubusy.'/site.json') && file_put_contents('../../data/_sdata-'.$sdata.'/users.json', $out))
						{
						$q = file_get_contents('../../data/_sdata-'.$sdata.'/ssite.json'); $b = json_decode($q,true);
						$q = file_get_contents('../../data/'.$Ubusy.'/site.json');
						$a = json_decode($q,true);
						$rn = "\r\n";
						$boundary = "-----=".md5(rand());
						$body = _("Welcome on")." <a href='".$a['url']."/".$a['nom'].".html'>".$a['tit']."</a><br /><br />".$rn;
						$body .= _("Your login is").": <b>".$r['n']."</b><br />".$rn;
						$body .= _("Your new password is").": <b>".$pass."</b><br />".$rn;
						$msgT = strip_tags($body);
						$msgH = $top . $body . $bottom;
						$sujet = $a['tit'].' - '. _("Recover Password");
						$fm = preg_replace("/[^a-zA-Z ]+/", "", $a['tit']);
						$header  = "From: ".$fm."<".$b['mel'].">".$rn."Reply-To:".$fm."<".$b['mel'].">";
						$header.= "MIME-Version: 1.0".$rn;
						$header.= "Content-Type: multipart/alternative;".$rn." boundary=\"$boundary\"".$rn;
						$msg= $rn."--".$boundary.$rn;
						$msg.= "Content-Type: text/plain; charset=\"utf-8\"".$rn;
						$msg.= "Content-Transfer-Encoding: 8bit".$rn;
						$msg.= $rn.$msgT.$rn;
						$msg.= $rn."--".$boundary.$rn;
						$msg.= "Content-Type: text/html; charset=\"utf-8\"".$rn;
						$msg.= "Content-Transfer-Encoding: 8bit".$rn;
						$msg.= $rn.$msgH.$rn;
						$msg.= $rn."--".$boundary."--".$rn;
						$msg.= $rn."--".$boundary."--".$rn;
						if(mail(strip_tags($_POST['e']), stripslashes($sujet), stripslashes($msg),$header))
							{
							echo _("You will receive an email with your password");
							die();
							}
						}
					break;
					}
				}
			}
		echo '!'._("Unknown email");
		break;
		// ********************************************************************************************
		case 'del':
		if(file_exists('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/users.json'))
			{
			$q = file_get_contents('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/users.json');
			$a = json_decode($q,true);
			$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
			$c = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, substr(strip_tags($a['pass']),0,30), base64_decode($_GET['c']), MCRYPT_MODE_ECB, $iv);
			$c = rtrim($c, "\0");
			if(($c==$_GET['m']) && ($k=array_search(strip_tags($_GET['m']),$a['list']))!==false)
				{
				unset($a['list'][$k]);
				$out = json_encode($a);
				if(file_put_contents('../../data/_sdata-'.$sdata.'/'.$Ubusy.'/users.json', $out))
					{
					echo "<script language='JavaScript'>setTimeout(function(){document.location.href='".strip_tags($_GET['b'])."';},2000);</script>";
					echo "<html><head><meta charset='utf-8'></head><body><h3 style='text-align:center;margin-top:50px;'>"._('Email deleted')."</h3></body></html>";
					break;
					}
				}
			}
		echo "<script language='JavaScript'>document.location.href='".strip_tags($_GET['b'])."';</script>";
		break;
		// ********************************************************************************************
		}
	clearstatcache();
	exit;
	}
// ********************* functions *************************************************************************
function f_newPass ($t=6)
	{
	$pass = "";
	$s = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
	$max = strlen($s);
	if ($t>$max) $t = $max;
	$i = 0; 
	while($i<$t)
		{ 
		$c = substr($s, mt_rand(0, $max-1), 1);
		if (!strstr($pass, $c))
			{ 
			$pass .= $c;
			++$i;
			}
		}
	return $pass;
	}
?>
