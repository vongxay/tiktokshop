<?php
// Include database config first
require_once(__DIR__ . '/config.php');

function generateProId() {
	$id = 'E-';
	if (!empty(checkProId())) {
		$temp_id = checkProId();
		$temp = explode("-", $temp_id);
		if (!empty($temp[1])) {
			$tmp = (int)$temp[1];
			$tmp = $tmp + 1;
			$id .= checkId($tmp);
		} else {
			$id .= '00001';
		}
	} else {
		$id .= '00001';
	}
	return $id;
}

function checkProId() {
	$db = connect();
	$stmt = $db->query("SELECT MAX(product_id) as mx FROM tb_product");
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	return $result['mx'];
}

function generateCustom() {
	$rand = rand(5, 6);
	$id = $rand;
	if (!empty(checkProId())) {
		$temp_id = checkProId();
		$temp = explode("-", $temp_id);
		if (!empty($temp[1])) {
			$tmp = (int)$temp[1];
			$tmp = $tmp + 1;
			$id .= checkId($tmp);
		} else {
			$id .= $rand;
		}
	} else {
		$id .= $rand;
	}
	return $id;
}

function checkCustom() {
	$db = connect();
	$stmt = $db->query("SELECT MAX(customer_id) as mx FROM tb_customer");
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	return $result['mx'];
}
function checkId($id) {
	$tmp = '';
	if (strlen($id) == 1) {
		$tmp .= '0000' . $id;
	} else if (strlen($id) == 2) {
		$tmp .= '000' . $id;
	} else if (strlen($id) == 3) {
		$tmp .= '00' . $id;
	} else if (strlen($id) == 4) {
		$tmp .= '0' . $id;
	} else if (strlen($id) == 5) {
		$tmp .= $id;
	}
	return $tmp;
}

function generateOdId() {
	$id = 'O-';
	if (!empty(checkOdId())) {
		$temp_id = checkOdId();
		$temp = explode("-", $temp_id);
		if (!empty($temp[1])) {
			$tmp = (int)$temp[1];
			$tmp = $tmp + 1;
			$id .= checkId($tmp);
		} else {
			$id .= '00001';
		}
	} else {
		$id .= '00001';
	}
	return $id;
}

function checkOdId() {
	$db = connect();
	$stmt = $db->query("SELECT MAX(order_id) as mx FROM tb_order");
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	return $result['mx'];
}

function getProductCategory() {
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_category ORDER BY RAND() LIMIT 7");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$db = connect();
$stmt = $db->query("SELECT * FROM tb_customer");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$i=0;
foreach($result as $row){
$code = $row['store'];
}
function getProductCategoryUser() {
    $profile = $_SESSION['id'];
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_customer");
	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$i=0;
	foreach($result as $row){
	$i++;
	$code = $row['store'];
	
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_category  ORDER BY name ASC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
function getProductCatework() {
	$db = connect();
	$stmt = $db->query("SELECT store FROM tb_cutomer ORDER BY id ASC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getProductMin() {
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_product WHERE qty <= 3 ORDER BY name ASC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getProducts() {
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_product ORDER BY name ASC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductsNews() {
	$db = connect();
	$stmt = $db->query("SELECT n.news_id, n.name as news_name, n.img_name, n.created, c.id, c.work, c.name FROM tb_news n INNER JOIN tb_category c ON n.category_id = c.id WHERE c.name LIKE '%ຂ່າວ%' ORDER BY n.id DESC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_product WHERE product_id = :product_id");
	$stmt->bindParam("product_id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getCategoryById($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_category WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getVideoById($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_video_news WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProductPOST($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_post WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProductName($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_name WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getProductC($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_category WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProductC_detail($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_order_detail WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getProductM($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_music WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getProductW($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_work WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getTableById($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_table WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}


function getPictureById($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_picture WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPromotionById($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_promotion WHERE id = :id");
	$stmt->bindParam("id", $id);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getProductByCategoryId($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_product WHERE category_id = :category_id AND id > 0");
	$stmt->bindParam("category_id", $id);
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductsHome() {
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_product WHERE id > 0 ORDER BY name ASC LIMIT 12");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMostOrderProduct() {
	$db = connect();
	$stmt = $db->query("SELECT p.* FROM tb_product p INNER JOIN tb_order_detail od ON (p.product_id = od.product_id) WHERE p.qty > 0 AND (SELECT COUNT(product_id) FROM tb_order_detail WHERE product_id = p.product_id GROUP BY product_id) >= 4 GROUP BY p.product_id ORDER BY p.name ASC LIMIT 5");
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchProducts($query) {
	$db = connect();
	if (!empty($query)) {
		$stmt = $db->prepare("SELECT * FROM tb_product WHERE name LIKE :query AND id > 0");
		$query = "%" . $query . "%";
		$stmt->bindParam("query", $query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		return getProducts();
	}
}

function searchOrder($query) {
	$db = connect();
	if (!empty($query)) {
		$stmt = $db->prepare("SELECT * FROM tb_order_detail WHERE created LIKE :query AND qty > 0");
		$query = "%" . $query . "%";
		$stmt->bindParam("query", $query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	} else {
		return getOrderDetail();
	}
}
function getRelatedProduct($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_product WHERE category_id = :category_id AND qty > 0 ORDER BY name LIMIT 4");
	$stmt->bindParam("category_id", $id);
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function countChats() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_chat");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countComments() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM comment");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countsents() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_sent");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countOrders() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_order");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countOrderDetail() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_order_detail");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getOrders() {
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_order INNER JOIN tb_customer ON tb_order.customer_id = tb_customer.id ORDER BY order_date DESC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getOrdersInvioce() {
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_order INNER JOIN tb_customer ON tb_order.customer_id = tb_customer.id ORDER BY order_date DESC LIMIT 1");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderDetail() {
	$db = connect();
	$stmt = $db->query("SELECT p.name, od.* FROM tb_product p INNER JOIN tb_order_detail od ON (p.product_id = od.product_id) ORDER BY od.created DESC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTable() {
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_table ORDER BY id DESC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrdered($id) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_order WHERE customer_id = :customer_id ORDER BY order_date DESC");
	$stmt->bindParam("customer_id", $id);
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderedProducts($id) {
	$customer_id = $_SESSION['loggedId'];
	$db = connect();
	$stmt = $db->prepare("SELECT (SELECT name FROM tb_product WHERE product_id = od.product_id) AS name, od.* FROM tb_order_detail od WHERE od.order_id = :order_id AND od.user_product_id = $customer_id  ORDER BY od.created DESC");
	$stmt->bindParam("order_id", $id);
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getOrderedProducts_detail($id) {
	$customer_id = $_SESSION['admin_loggedId'];
	$db = connect();
	$stmt = $db->prepare("SELECT (SELECT name FROM tb_product WHERE product_id = od.product_id) AS name, od.* FROM tb_order_detail od WHERE od.order_id = :order_id ORDER BY od.created DESC");
	$stmt->bindParam("order_id", $id);
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCustomerBy($value) {
	$db = connect();
	$stmt = $db->prepare("SELECT * FROM tb_customer WHERE id = :value OR username = :value");
	$stmt->bindParam("value", $value);
	$stmt->execute();
	return $stmt->fetch(PDO::FETCH_ASSOC);
}



function getchats() {
	$db = connect();
	$stmt = $db->query("SELECT * FROM tb_chat ORDER BY sender_id DESC");
	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function countProducts() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_product");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function productsearch($id){
	  $profile = $_SESSION['username'];
	  $ds = $_POST['ds'];
      $de = $_POST['de'];
      $db = connect();
      $smt = $db->query("SELECT n.news_id, n.category_id, n.view, n.name, n.username, n.img_name, n.created, c.name AS category_name  FROM tb_news n INNER JOIN tb_category c ON (n.category_id = c.id) WHERE n.username = '$profile' AND n.created BETWEEN '$ds' AND '$de'  ORDER BY n.category_id DESC");
	  $smt->bindParam("n.username", $id);
	  $smt->execute();
	  return $smt->fetchAll(PDO::FETCH_ASSOC);
}
function countCategorys() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_category");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countCustomers() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_customer");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countUserOrder() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_order WHERE DAY(created_in) = DAY(CURRENT_DATE()) ");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countProductMin() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_product WHERE qty <= 3 ");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countProductAll() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_product");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function counttable() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_table WHERE status = 0");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}
function counttable1() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_table WHERE status = 1");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function countOrder_detail() {
	$db = connect();
	$stmt = $db->query("SELECT COUNT(*) as count FROM tb_order_detail WHERE statust = 3");
	return $stmt->fetch(PDO::FETCH_ASSOC);
}