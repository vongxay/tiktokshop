
  <?php
    $profile = getCustomerBy($_SESSION['loggedId']);
    $id = $profile['id'];
    $customer_id = $profile['id'];
    if (isset($_POST['submit'])) {
        $fname = $_POST['fname'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];
        $distric = $_POST['distric'];
        $db = connect();
        $stmt = $db->prepare("UPDATE tb_customer SET fname = :fname, mobile = :mobile, address = :address, distric = :distric WHERE id = :id");
        $stmt->bindParam("fname", $fname);
        $stmt->bindParam("mobile", $mobile);
        $stmt->bindParam("address", $address);
        $stmt->bindParam("distric", $distric);
        $stmt->bindParam("id", $customer_id);
        if ($stmt->execute()) {

            $pid = $_GET['id'];
            foreach ($_SESSION['cart'] as $p_id => $qty) {
                $order_id = generateOdId();
                $db = connect();
                $smt = $db->query("SELECT * FROM tb_product WHERE product_id = '" . $p_id . "'");
                $result = $smt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $user_product_id = $row['customer_id'];
                    $total    = $row['price'] * $qty;
                    $customer_id = $_SESSION['loggedId'];
                    $stmt1 = $db->prepare("INSERT INTO tb_order_detail(`order_id`,`product_id`,`customer_id`,`qty`,`price_qty`,`user_product_id`,`created`) VALUES('$order_id','$p_id','$customer_id','$qty','$total','$user_product_id',NOW()) ");
                    $stmt1->execute();
                    $pqty = $row['qty'];
                    $sumqty = $pqty - $qty;
                    $stmt2 = $db->prepare("UPDATE tb_product SET qty = $sumqty WHERE product_id = '" . $p_id . "'");
                    // $stmt2->bindParam("sumqty", $sumqty);
                    // $stmt2->bindParam("product_id", $p_id);
                    $stmt2->execute();
                }
            }

            if ($stmt1 == true && $stmt2 == true) {
                $order_id = generateOdId();
                $db = connect();
                $customer_id = $_SESSION['loggedId'];
                $stmt4 = $db->prepare("INSERT INTO tb_order(`order_id`,`customer_id`,`order_date`,`created`) VALUES('$order_id', '$customer_id', NOW(), NOW()) ");
                // $stmt4->bindParam("order_id", $order_id);
                // $stmt4->bindParam("customer_id", $customer_id);
                $stmt4->execute();
            }
            if ($result) {
                //unset($_SESSION['cart'][$p_id]);
                unset($_SESSION['cart']);
                unset($_SESSION['id']);
                echo '
                    <script type="text/javascript">
                        setTimeout(function() {
                            location.href = "index.php";
                        }, 1000);
                    </script>
                ';
            }
        }
    }
