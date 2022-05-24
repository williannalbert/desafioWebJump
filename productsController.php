<?php

if(isset($_POST['save'])){
    require_once('productsSql.php');
    $prodSql = new productsSql();
    if(isset($_POST['sku']) && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['quantity']) && isset($_POST['description']) && isset($_POST['category'])) 
    {
        $prodSql->setSku($_POST['sku']);
        $prodSql->setNome($_POST['name']);
        $prodSql->setPreco($_POST['price']);
        $prodSql->setQuantidade($_POST['quantity']);
        $prodSql->setDescricao($_POST['description']);
        $prodSql->setCategorias($_POST['category']);
        if($_FILES['image']['name']!="")
        {
            $image_name = $_FILES['image']['name'];
            $image_size = $_FILES['image']['size'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $error = $_FILES['image']['error'];
            if($error === 0){
                $img_ex = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                $exts = array("jpg", "png", "jpeg");
                if(in_array($img_ex, $exts)){
                    $img_new_name = uniqid("img_", true).'.'.$img_ex;
                    $img_upload_path = 'imgs/'.$img_new_name;
                    move_uploaded_file($tmp_name,  $img_upload_path);
                    $prodSql->setImagem($img_new_name);
                }
            }
        }    
        $prodSql->insert();
    }
    else
        echo "<script>alert('Necessário preencher todos os dados');document.location='assets/addProduct.php'</script>";
    
}
if(isset($_GET['sku']) && isset($_GET['act'])){
    require_once('productsSql.php');
    $prodSql = new productsSql();
    if($_GET['act'] === "delete"){
        $prodSql->setSku($_GET['sku']);
        $prodSql->delete();
        echo "<script>alert('Exclusão realizada com sucesso.');document.location='assets/products.php'</script>";
    }
}
if(isset($_POST['edit'])){
    require_once('productsSql.php');
    $prodSql = new productsSql();
    if(isset($_POST['sku']) && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['quantity']) && isset($_POST['description']) && isset($_POST['category'])) {
        $prodSql->setSku($_POST['sku']);
        $prodSql->setNome($_POST['name']);
        $prodSql->setPreco($_POST['price']);
        $prodSql->setQuantidade($_POST['quantity']);
        $prodSql->setDescricao($_POST['description']);
        $prodSql->setCategorias($_POST['category']);
        if($_FILES['image']['name']!="")
        {
            
            $image_name = $_FILES['image']['name'];
            $image_size = $_FILES['image']['size'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $error = $_FILES['image']['error'];
            if($error === 0){
                $img_ex = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
                $exts = array("jpg", "png", "jpeg");
                if(in_array($img_ex, $exts)){
                    $img_new_name = uniqid("img_", true).'.'.$img_ex;
                    $img_upload_path = 'imgs/'.$img_new_name;
                    move_uploaded_file($tmp_name,  $img_upload_path);
                    $prodSql->setImagem($img_new_name);
                }
            }
        }  

        $prodSql->update($_GET['sku']);
        echo "<script>alert('Atualização realizada com sucesso.');document.location='assets/products.php'</script>";
    }
    else
        echo "<script>alert('Necessário preencher todos os dados');document.location='assets/products.php'</script>";
  }
?>