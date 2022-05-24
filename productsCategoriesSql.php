<?php
require_once('database.php');

class productsCategoriesSql{
    private $produtoSku;
    private $categoriaId;
    protected $dbConn;

    
    public function __construct($produtoSku = "", $categoriaId = "")
    {
        $this->produtoSku = $produtoSku;
        $this->categoriaId = $categoriaId;

        $this->dbConn = new PDO(DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS,[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }
    public function setProdutoSku($produtoSku)
    {
        $this->produtoSku = $produtoSku;
    }
    public function getProdutoSku()
    {
        return $this->produtoSku;
    }
    public function setCategoriaId($categoriaId)
    {
        $this->categoriaId = $categoriaId;
    }
    public function getCategoriaId()
    {
        return $this->categoriaId;
    }
    public function getCategoryProduct()
    {
        try
        {
            $stm = $this->dbConn->prepare("SELECT nome, categoria_id 
                FROM categorias 
                INNER JOIN produtocategoria
                on Id = categoria_id
                where produto_sku = ?");
            $stm->execute([$this->produtoSku]);
            return $stm->fetchAll();
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
    }
    public function insertCategoryProduct()
    {
        try
        { 
            $this->deleteCategoryProduct();
            $stm = $this->dbConn->prepare("INSERT INTO produtocategoria VALUES (?, ?)");
            $stm->execute([$this->produtoSku, $this->categoriaId]);
            return $stm->fetchAll();
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
    }
    public function deleteCategoryProduct()
    {
        try
        {
            $stm = $this->dbConn->prepare("DELETE FROM produtocategoria WHERE produto_sku = ?");
            $stm->execute([$this->produtoSku]);
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
    }
}
?>