<?php

if(isset($_POST['save'])){
    require_once('categoriesSql.php');
    $catSql = new categoriesSql();

    $catSql->setId($_POST['category-code']);
    $catSql->setNome($_POST['category-name']);
    $catSql->insert();
    //echo "<script>alert('Inclusão realizada com sucesso.');document.location='assets/categories.php'</script>";
}

if(isset($_GET['id']) && isset($_GET['act'])){
    require_once('categoriesSql.php');
    $catSql = new categoriesSql();
    if($_GET['act'] === "delete"){
        $catSql->setId($_GET['id']);
        $catSql->delete();
        echo "<script>alert('Exclusão realizada com sucesso.');document.location='assets/categories.php'</script>";
    }
}


?>