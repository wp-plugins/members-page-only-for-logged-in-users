<?php
/*
Plugin Name: Members page only for logged in users
Description: Only logged in users can view the members page. Non logged in users will be redirected to either register/login page.
Version: 1.3.0
Author: Miverve
Author URI: https://miverve.com/
Plugin URI: https://miverve.com/

Copyright 2015  Miverve  (email : admin@miverve.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
ob_start();
add_action('admin_menu', 'members_page_only_for_logged_in_users_option_menu');

function members_page_only_for_logged_in_users_option_menu()
{

   add_menu_page(__('Members page only for logged in users', 'BPMO'), __('Members page only for logged in users', 'BPMO'), 10, 'bpmemberonly', 'members_page_only_for_logged_in_users');
   add_submenu_page('bpmemberonly', __('Members page only for logged in users','BPMO'), __('Members page only for logged in users','BPMO'), 10, 'bpmemberonly', 'members_page_only_for_logged_in_users_setting');
}

function members_page_only_for_logged_in_users_setting()
{
		global $wpdb;
		$m_bpmoregisterpageurl = get_option('registerpageurl');

		if (isset($_POST['submitnew']))
		{
			if (isset($_POST['registerpageurl']))
			{
				$m_registerpageurl = $wpdb->escape($_POST['registerpageurl']);
			}
				
				update_option('registerpageurl',$m_registerpageurl);
			
			members_page_only_for_logged_in_users_message("Changes saved.");
		}
		echo "<br />";

		$saved_register_page_url = get_option('registerpageurl');
		?>

<div style='margin:10px 5px;'>
<div style='float:left;margin-right:10px;'>

</div> 
<div style='padding-top:5px; font-size:22px;'> <i></>Members Page Only For Logged In Users Settings:</i></div>
</div>
<div style='clear:both'></div>		
		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:90%;">
								<div class="postbox">
									<h3 class='hndle'><span>
										Option Panel:
									</span>
									</h3>
								
									<div class="inside" style='padding-left:5px;'>
										<br />
										<form id="form" name="form" action="" method="POST">
										<table id="table" width="100%">
										<tr>
										<td width="30%">
										Register Page URL:
										</td>
										<td width="70%">
										<input type="text" id="registerpageurl" name="registerpageurl" size="70" value="<?php  echo $saved_register_page_url; ?>">
										</td>
										</tr>
										</table>
										<br />
										<input type="submit" id="submitnew" name="submitnew" value=" Submit ">
										</form>
										
										<br />
									</div>
								</div>
							</div>
						</div>
					</div>
		    	</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<br />
		
		<?php
		}				

	
function members_page_only_for_logged_in_users_message($p_message)
{

	echo "<div id='message' class='updated fade'>";

	echo $p_message;

	echo "</div>";

}

function members_page_only_for_logged_in_users()
{
	if (is_front_page()) return;
	if (function_exists('bp_is_register_page') && function_exists('bp_is_activation_page') )
	{
		if ( bp_is_register_page() || bp_is_activation_page() )
		{
			return;
		}
	}
	$current_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
         if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 

	       $current_url = str_ireplace('https://','',$current_url);
         }else{
                 $current_url = str_ireplace('http://','',$current_url);
         }
	$current_url = str_ireplace('www.','',$current_url);
	$saved_register_page_url = get_option('registerpageurl');
         if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 

	      $saved_register_page_url = str_ireplace('https://','',$saved_register_page_url);

        }else{
               $saved_register_page_url = str_ireplace('http://','',$saved_register_page_url);

      }
	$saved_register_page_url = str_ireplace('www.','',$saved_register_page_url);
	
	if (stripos($current_url,$saved_register_page_url) === false)
	{

	}
	else 
	{
		return;
	}
	//Naren - start
	
	if ( is_user_logged_in() == false && ( bp_is_activity_component() || bp_is_groups_component() || bp_is_forums_component() || bp_is_blogs_component() || bp_is_page( BP_MEMBERS_SLUG ) || strpos($current_url,'/profile/')==true ))
	{
		if (empty($saved_register_page_url))
		{
			$current_url = $_SERVER['REQUEST_URI'];
			//$redirect_url = wp_login_url( get_option('siteurl').$current_url );
			$redirect_url = wp_login_url( );
			header( 'Location: ' . $redirect_url );
			die();			
		}
		else 
		{
                         if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 

			$saved_register_page_url = 'https://'.$saved_register_page_url;

                          }else{
                            $saved_register_page_url = 'http://'.$saved_register_page_url;
                         }
			header( 'Location: ' . $saved_register_page_url );
			die();
		}
	}
}

if (function_exists('bp_is_register_page') && function_exists('bp_is_activation_page') )
{
	add_action('wp','members_page_only_for_logged_in_users');
}
else 
{
	add_action('wp_head','members_page_only_for_logged_in_users');
}
