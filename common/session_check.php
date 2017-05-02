<? 
	session_start();

	if (!isset($_SESSION['cl_basket'])) {
		$_SESSION['cl_basket'] = Array();
	}

#---------------------------------------------------------------------------------------------------
# USER SESSION
#---------------------------------------------------------------------------------------------------
# check whether user is logged in
function checkUser() {
	global $_SESSION;

    return isset($_SESSION['cl_user']) ? $_SESSION['cl_user'] : false;
}

#---------------------------------------------------------------------------------------------------
# clears session data
function clearSession() {
	global $_SESSION;

	unset($_SESSION['cl_user']);
}

#---------------------------------------------------------------------------------------------------
# checks whether user has permission
function userHasPermission($permission) {
	global $_SESSION;

	if (!isset($_SESSION['cl_user'])) {
		return false;
	}

	return $_SESSION['cl_user']['u_type'] & $permission;
}

#---------------------------------------------------------------------------------------------------

define('PERM_ADD_CATEGORY',		0b00000001);
define('PERM_ADD_GOOD',			0b00000010);
define('PERM_EDIT_GOOD',		0b00000100);
define('PERM_EDIT_NETPRICE',	0b00001000);
define('PERM_ADD_SELL',			0b00010000);
define('PERM_EDIT_USER', 		0b00100000);
define('PERM_SEE_CLIENT', 		0b01000000);
define('PERM_VIEW_ORDERS',		0b10000000);


#---------------------------------------------------------------------------------------------------

	$user = checkUser();

	define('ROOT_PATH', '/var/www/clothing/');
	define('ROOT_SHIFT', '/clothing');

	ini_set('display_errors', 1);
?>