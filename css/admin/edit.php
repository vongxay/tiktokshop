<!DOCTYPE html>
<html lang="en">
<head>
    <?php
         include_once("../functions/init.php");
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body>
    <br>
    <?php
    if (isset($_POST['edit-btn'])) {
        $id = $_GET['edit'];
        if (!empty($id)) {
            if (isset($_POST['expiration_date'])) {
                $expiration_date = htmlentities($_POST['expiration_date']);
              
                $db = connect();
                $stmt = $db->prepare("UPDATE tb_settings SET expiration_date = :expiration_date WHERE id = :id");
                $stmt->bindParam("expiration_date", $expiration_date);
                $stmt->bindParam("id", $id);
                if ($stmt->execute()) {

                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>เสร็จ!</strong> 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
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
    }
        if (isset($_REQUEST['method'])) {
            if ($_REQUEST['method'] == 'edit') {
                $id = $_GET['id'];
               
            ?>

                <form action="?page=edit&edit=<?php echo $_GET['id']; ?>" method="POST">
                <div class="mb-3" style="margin: 50px;">
                    <label for="exampleInputPassword1" class="form-label">expiration_date</label>
                    <input type="date" class="form-control" name="expiration_date" id="exampleInputPassword1" required><br>
                    <button type="submit" name="edit-btn" class="btn btn-primary">Submit</button>
                </div>
               
                
                </form>

            <?php


            }
        }
       

        $db = connect();
        $stmt = $db->query("SELECT * FROM tb_settings");
        $resualt = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i=0;
        foreach($resualt as $row){
        $i++;
            
    ?>
    <table class="table" style="margin: 10px;">
  <thead>
    <tr>
      <th scope="col">NO</th>
      <th scope="col">Date</th>
      <th scope="col">Edit</th>
     
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"><?php echo $i; ?></th>
      <td><?php echo $row['expiration_date']; ?></td>
      <td><a href="?page=edit&method=edit&id=<?php echo $row['id']; ?>" class="btn btn-outline-primary">Edit</a></td>
    </tr>
  </tbody>
</table>
<?php } ?>

</body>
</html>