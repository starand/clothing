<?
	if (!defined("ROOT_PATH")) die();
	include_once ROOT_PATH."/db/config.php";

    # connecting
	$conn = @mysql_pconnect($host, $user, $pswd) or die("Can not connect to database!!");
	mysql_select_db($db) or die("Can not select database!!");
	# set UTF8 as default connection
	mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', ".
		"character_set_connection='utf8',character_set_database='utf8',character_set_server='utf8'",
		$conn);

#---------------------------------------------------------------------------------------------------
# MySQL helper functions
#---------------------------------------------------------------------------------------------------
## send query to db	
function uquery($query) 
{
	$result = @mysql_query($query) or die("Can not send query to database!!");
	return $result;
}

#---------------------------------------------------------------------------------------------------
## convert mysql result into assosiate array
function res_to_array($res) {
	for($result=array(); $row=mysql_fetch_array($res); $result[]=$row);
	return $result;
}

#---------------------------------------------------------------------------------------------------
## convert one row result to assosiate array
function row_to_array($res) {
	return $res ? mysql_fetch_array($res) : false;
}

#---------------------------------------------------------------------------------------------------
# Good's functions
#---------------------------------------------------------------------------------------------------
# Good state constants
define('GOOD_STATE_PRESENT',	0);
define('GOOD_STATE_SOLD',		1);
define('GOOD_STATE_WAIT',		2);

#---------------------------------------------------------------------------------------------------
## add good
function add_good($cat, $title, $desc, $image, $count, $price) {
	$cat = (int)$cat;
	$count = (int)$count;
	$price = (float)$price;
	$title = addslashes($title);
	$desc = addslashes($desc);
	$image = addslashes($image);

	$sql = "INSERT INTO goods ".
		   "VALUES(NULL, $cat, '$title', '$desc', '$image', now(), now(), $count, $price, 0, ".
		   GOOD_STATE_WAIT.", '', 0)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## updates goods
function update_good($goodId, $cat, $title, $desc, $image, $count, $price) {
	$goodId = (int)$goodId;
	$cat = (int)$cat;
	$count = (int)$count;
	$price = (float)$price;
	$title = addslashes($title);
	$desc = addslashes($desc);
	$image = addslashes($image);

	$sql = "UPDATE goods SET g_cat=$cat, g_title='$title', g_desc='$desc', g_image='$image', ".
		   "g_count=$count, g_price=$price, g_mtime=now() ". 
		   "WHERE g_id=$goodId";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## updates good total price
function updateGoodTotalPrice($good, $price) {
	$good = (int)$good;
	$price = (float)$price;

	$sql = "UPDATE goods SET g_total_price=$price WHERE g_id=$good";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## updates good shows counter
function incrementGoodViewCounter($good) {
	$good = (int)$good;

	$sql = "UPDATE goods SET g_views=g_views+1 WHERE g_id=$good";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## updates good total price
function updateGoodState($good, $state) {
	$good = (int)$good;
	$state = (int)$state;

	$sql = "UPDATE goods SET g_state=$state WHERE g_id=$good";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## returns good
function get_good($id) {
	$id = (int)$id;

	$sql = "SELECT * FROM goods WHERE g_id=$id";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## returns good
function getPrevGood($good, $cat) {
	$good = (int)$good;
	$cat = (int)$cat;

	$sql = "SELECT g_id FROM goods WHERE g_cat=$cat AND g_id > $good ORDER BY g_id ASC LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## returns good
function getNextGood($good, $cat) {
	$good = (int)$good;
	$cat = (int)$cat;

	$sql = "SELECT g_id FROM goods WHERE g_cat=$cat AND g_id < $good ORDER BY g_id DESC LIMIT 1";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## get goods by category
function get_goods_by_cat($cat) {
	$cat = (int)$cat;

	$sql = "SELECT * FROM goods WHERE g_cat=$cat ORDER BY g_id DESC";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## get goods 
function get_goods($limit=20, $skip=0) {
	$skip = (int)$skip;
	$limit = (int)$limit;
	$sql = "SELECT * FROM goods ORDER BY g_id DESC LIMIT $skip, $limit";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## get latest goods
function get_latest_goods($limit = 20, $state=0) {
	$limit = (int)$limit;
	$state = (int)$state;
	$sql = "SELECT * FROM goods WHERE g_state=$state ORDER BY g_id DESC LIMIT $limit";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## get latest not sold goods
function get_latest_not_sold_goods($limit = 21) {
	$limit = (int)$limit;
	$sql = "SELECT * FROM goods WHERE g_state != 1 ORDER BY g_id DESC LIMIT $limit";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## get latest goods
function soldGoodIfAllDimsAreSold($goodId) {
	$goodId = (int)$goodId;
	$sql = "SELECT count(1) FROM dim_grid WHERE dg_good=$goodId AND dg_state=0";
	$count = row_to_array(uquery($sql))[0];

	if ($count == 1) {
		$sql = "UPDATE goods SET g_state=1 WHERE g_id=$goodId";
		return uquery($sql);
	}
	return false;
}

#---------------------------------------------------------------------------------------------------
# get total investment sum
function getTotalInvestment() {
	$sql = "SELECT sum(g_total_price) FROM goods";
	return row_to_array(uquery($sql))[0];
}

#---------------------------------------------------------------------------------------------------
# get total incomes
function getTotalIncomes() {
	$sql = "SELECT sum(g_total_price/g_count + s_earn) FROM goods, sales WHERE g_id=s_good";
	return floor(row_to_array(uquery($sql))[0]);
}

#---------------------------------------------------------------------------------------------------
# Category's functions
#---------------------------------------------------------------------------------------------------
# add category
function add_category($desc, $parent) {
	$parent = (int)$parent;
	$desc = addslashes($desc);

	$sql = "INSERT INTO categories VALUES(NULL, $parent, '$desc')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## get categories by idate
function get_categories($parent) {
	$parent = (int)$parent;

	$sql = "SELECT * FROM categories WHERE cat_parent=$parent";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## get category by idate
function get_category($id) {
	$id = (int)$id;

	$sql = "SELECT * FROM categories WHERE cat_id=$id";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
## User's functions
#---------------------------------------------------------------------------------------------------
# add user
function addUser($login, $pswd, $type, $phone, $loc) {
	$type = (int)$type;
	$login = addslashes($login);
	$phone = addslashes($phone);
	$loc = addslashes($loc);

    $sql = "INSERT INTO users ".
		   "VALUES(NULL, $type, '$login', '$pswd', '$phone', '$loc')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# get user by if
function getUser($id) {
	$id = (int)$id;
	$sql = "SELECT * FROM users WHERE u_id=$id";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# get user by login
function getUserByLogin($login) {
	$login = addslashes($login);
	$sql = "SELECT * FROM users WHERE u_login='$login'";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# Dimensional grid fucntion
#---------------------------------------------------------------------------------------------------
define('DG_PRESENT', 0);
define('DG_SOLD', 1);

# add dim grid row
function addDimGridRow($good, $data) {
	$good = (int)$good;
	$data = addslashes($data);

	$sql = "INSERT INTO dim_grid VALUES(NULL, $good, '$data', 0)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# get good's DG records
function getDimRow($dim) {
	$dim = (int)$dim;

	$sql = "SELECT * FROM dim_grid WHERE dg_id=$dim";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# get good's DG records
function getDimGridByGood($good) {
	$good = (int)$good;

	$sql = "SELECT * FROM dim_grid WHERE dg_good=$good";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# get dims for gold which are not sold
function getNotSoldDimGridByGood($good) {
	$good = (int)$good;

	$sql = "SELECT * FROM dim_grid WHERE dg_good=$good AND NOT EXISTS(SELECT s_dim FROM sales WHERE dg_id=s_dim)";
	return res_to_array(uquery($sql));
}
#---------------------------------------------------------------------------------------------------
# updates DG record data
function updateDGRecord($id, $data) {
	$id = (int)$id;
	$data = addslashes($data);

	$sql = "UPDATE dim_grid SET dg_data='$data' WHERE dg_id=$id";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# updates DG record data
function updateDGRecordState($id, $state) {
	$id = (int)$id;
	$state = (int)$state;

	$sql = "UPDATE dim_grid SET dg_state=$state WHERE dg_id=$id";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
## Sales functions
#---------------------------------------------------------------------------------------------------
## add sale
function add_sale($good, $dim, $amount) {
	$good = (int)$good;
	$dim = (int)$dim;
	$amount = (float)$amount;

	$sql = "INSERT INTO sales VALUES(NULL, $good, $amount, now(), 0, $dim)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# returns sales list
function get_sales($condition = "") {
	if (strlen($condition)) {
		$condition = " AND $condition";
	}

	$sql = "SELECT s_id, s_good, s_earn, s_date, s_client, s_dim, g_title, g_image, dg_data ".
		   "FROM sales, goods, dim_grid ".
		   "WHERE g_id=s_good AND s_dim=dg_id $condition ORDER BY s_id DESC";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# return total amount of sales
function getSalesTotal($condition = "") {
	if (strlen($condition)) {
		$condition = " WHERE $condition";
	}

	$sql = "SELECT sum(s_earn) FROM sales $condition";
	return floor(row_to_array(uquery($sql))[0]);
}

#---------------------------------------------------------------------------------------------------
# return total amount of sales
function getSalesCountTotal($condition = "") {
	if (strlen($condition)) {
		$condition = " WHERE $condition AND s_earn > 1";
	} else {
		$condition = " WHERE s_earn > 1";
	}

	$sql = "SELECT count(1) FROM sales $condition";
	return floor(row_to_array(uquery($sql))[0]);
}

#---------------------------------------------------------------------------------------------------
# return per month amount of sales
function getSalesThisMonth() {
	$sql = "SELECT sum(s_earn) FROM sales WHERE month(s_date) = month('".date("Y-m-d H:i:s")."')";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# return per month count of sales
function getSalesCountThisMonth() {
	$sql = "SELECT count(1) FROM sales WHERE month(s_date) = month('".date("Y-m-d H:i:s")."')";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# return sale
function get_sale($id) {
	$id = (int)$id;
	$sql = "SELECT * FROM sales WHERE s_id=$id";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# get seller name by bought good dimenssion

function getSellerByDim($dim) {
	$dim = (int)$dim;
	$sql = "SELECT c_name FROM clients, sales WHERE s_dim=$dim AND s_client=c_id";
	$row = row_to_array(uquery($sql));
	return count($row) ? $row[0] : "UNKNOWN";
}

#---------------------------------------------------------------------------------------------------
## Client functions
#---------------------------------------------------------------------------------------------------
# adds client
function addClient($name, $phone, $address) {
	$name = addslashes($name);
	$phone = addslashes($phone);
	$address = addslashes($address);

	$sql = "INSERT INTO clients VALUES(NULL, '$name', '$phone', '$address')";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# return client by name
function getClientByName($name) {
	$name = addslashes($name);

	$sql = "SELECT * FROM clients WHERE c_name='$name'";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# return client by name
function getClient($id) {
	$id = (int)$id;

	$sql = "SELECT * FROM clients WHERE c_id=$id";
	return row_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# returns client list
function getClients() {
	$sql = "SELECT * FROM clients";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# update client id for sale
function updateClientIdForSale($saleId, $clientId) {
	$saleId = (int)$saleId;
	$clientId = (int)$clientId;

	$sql = "UPDATE sales SET s_client=$clientId WHERE s_id=$saleId";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# update dim id for sale
function updateDimIdForSale($saleId, $dimId) {
	$saleId = (int)$saleId;
	$dimId = (int)$dimId;

	$sql = "UPDATE sales SET s_dim=$dimId WHERE s_id=$saleId";;
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# returns aberage earned sum by sale
function getAvgEarnPerSale() {
	$sql = "SELECT sum(s_earn)/count(1) FROM sales WHERE s_earn>0";
	return round(row_to_array(uquery($sql))[0], 2);
}

#---------------------------------------------------------------------------------------------------
## Client functions
#---------------------------------------------------------------------------------------------------
# adds client order
function add_order($desc, $pay, $delivery, $name, $address, $phone, $mail, $msg, $price) {
	$desc = addslashes($desc);
	$pay = (int)$pay;
	$delivery = (int)$delivery;
	$name = addslashes($name);
	$address = addslashes($address);
	$phone = addslashes($phone);
	$mail = addslashes($mail);
	$msg = addslashes($msg);
	$price = (float)$price;

	$sql = "INSERT INTO orders VALUES(NULL, '$desc', $pay, $delivery, '$name', '$address', '$phone',
									 '$mail', '$msg', now(), 0, $price)";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# returns client order
function get_order($id) {
	$id = (int)$id;

	$sql = "SELECT * FROM orders WHERE o_id=$id";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# returns count of new orders
function get_undone_orders_count() {
	$sql = "SELECT count(1) FROM orders WHERE o_state=0";
	$res = row_to_array(uquery($sql));
	return $res ? $res[0] : 0;
}

#---------------------------------------------------------------------------------------------------
# returns new orders
function get_new_orders() {
	$sql = "SELECT * FROM orders WHERE o_state=0";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------
# adds ip address as visitor
function add_visit($ip) {
	$ip = addslashes($ip);

	$sql = "INSERT INTO visitors VALUES(NULL, '$ip', now())";
	return uquery($sql);
}

#---------------------------------------------------------------------------------------------------
# returns count of all visits per day
function get_visits_per_day() {
	$sql = "SELECT count(1) FROM visitors WHERE DATE(now()) = DATE(v_date)";
	return row_to_array(uquery($sql))[0];
}

#---------------------------------------------------------------------------------------------------
# returns count of uniq visitors per day
function get_uniq_visits_per_day() {
	$sql = "SELECT v_ip FROM visitors WHERE DATE(now()) = DATE(v_date) GROUP BY v_ip";
	return res_to_array(uquery($sql));
}

#---------------------------------------------------------------------------------------------------

?>