<?php
require_once('database.php');

class productsSql
 {
    private $nome;
    private $sku;
    private $preco;
    private $descricao;
    private $quantidade;
    private $categorias;
    private $imagem;
    protected $dbConn;

    public function __construct($nome = "", $sku = "", $preco = "", $descricao="", $quantidade="", $categorias = "", $imagem = "")
    {
        $this->nome = $nome;
        $this->sku = $sku;
        $this->preco = $preco;
        $this->descricao = $descricao;
        $this->quantidade = $quantidade;
        $this->categorias = $categorias;
        $this->imagem = $imagem;

        $this->dbConn = new PDO(DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS,[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }
    public function getNome()
    {
        return $this->nome;
    }
    public function setSku($sku)
    {
        $this->sku = $sku;
    }
    public function getSku()
    {
        return $this->sku;
    }
    public function setPreco($preco)
    {
        $this->preco = $preco;
    }
    public function getPreco()
    {
        return $this->preco;
    }
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }
    public function getQuantidade()
    {
        return $this->quantidade;
    }
    public function setCategorias($categorias)
    {
        $this->categorias = $categorias;
    }
    public function getcategorias()
    {
        return $this->categorias;
    }
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }
    public function getImagem()
    {
        return $this->imagem;
    }

    public function insert()
    {
        try 
        {
            $consulta = $this->getOne();
            if(isset($consulta[0]))
            {
                echo "<script>alert('Já existe um produto com essa sku');document.location='assets/products.php'</script>";
            }
            else
            {
                if(isset($this->nome) && isset($this->sku) && isset($this->preco) && isset($this->descricao) && isset($this->quantidade) && isset($this->categorias))
                {
                    $stm = $this->dbConn->prepare("INSERT INTO produtos (nome, sku, preco, descricao, quantidade, imagem) values (?, ?, ?, ?, ?, ?)");
                    $stm->execute( [$this->nome, $this->sku, $this->preco, $this->descricao, $this->quantidade, $this->imagem]);
                    require("productsCategoriesSql.php");
                    $prodCat = new productsCategoriesSql();
                    $prodCat->setProdutoSku($this->sku);
                    $prodCat->setCategoriaId($this->categorias);
                    $prodCat->insertCategoryProduct();
                    echo "<script>alert('Inclusão realizada com sucesso.');document.location='assets/products.php'</script>";
                }
                else
                    echo "<script>alert('Necessário preencher todos os campos');document.location='assets/addProduct.php'</script>";
            }
        } 
        catch (Exception $ex) 
        {
            return $ex->getMessage();
        }   
    }
    public function getAll()
    {
        try
        {
            $stm = $this->dbConn->prepare("SELECT nome, sku, preco, descricao, quantidade, imagem FROM produtos");
            $stm->execute();
            return $stm->fetchAll();
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
    }

    public function getOne()
    {
        try
        {
            $stm = $this->dbConn->prepare("SELECT nome, sku, preco, descricao, quantidade, imagem FROM produtos WHERE sku = ?");
            $stm->execute([$this->sku]);
            return $stm->fetchAll();
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
    }
    public function update($id_update)
    {
        try
        {
            if(isset($this->nome) && isset($this->sku) && isset($this->preco) && isset($this->descricao) && isset($this->quantidade) && isset($id_update) && isset($this->categorias))
            {
                if($this->imagem === ""){
                    $stm = $this->dbConn->prepare("UPDATE produtos SET nome = ?, sku = ?, preco = ?, descricao = ?, quantidade = ? WHERE sku = ?");
                    $stm->execute([$this->nome, $this->sku, $this->preco, $this->descricao, $this->quantidade, $id_update]);
                }
                else{
                    $stm = $this->dbConn->prepare("UPDATE produtos SET nome = ?, sku = ?, preco = ?, descricao = ?, quantidade = ?, imagem = ? WHERE sku = ?");
                    $stm->execute([$this->nome, $this->sku, $this->preco, $this->descricao, $this->quantidade, $this->imagem, $id_update]);
                }
                
                require("productsCategoriesSql.php");
                $prodCat = new productsCategoriesSql();
                $prodCat->setProdutoSku($this->sku);
                $prodCat->setCategoriaId($this->categorias);
                $prodCat->insertCategoryProduct();
            }
            else
                echo "<script>alert('Necessário preencher todos os campos');document.location='assets/products.php'</script>";
            
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
    }
    public function delete()
    {
        try
        {   
            require_once('productsCategoriesSql.php');
            $proCat = new productsCategoriesSql();
            $proCat->setProdutoSku($this->sku);
            $proCat->deleteCategoryProduct();

            $stm = $this->dbConn->prepare("DELETE FROM produtos WHERE sku = ?");
            $stm->execute([$this->sku]);
            
            return $stm->fetchAll();
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
    }
    public function getProdutosCategorias($proId)
    {
        try
        {
            require_once('productsCategoriesSql.php');
            $proCat = new productsCategoriesSql();
            $proCat->setProdutoSku($proId);
            return $proCat->getCategoryProduct();
        }
        catch(Exception $ex)
        {
            return $ex->getMessage(); 
        }
    }
    public function getTopFour()
    {
        try
        {
            $stm = $this->dbConn->prepare("SELECT nome, sku, preco, descricao, quantidade, imagem FROM produtos LIMIT 4");
            $stm->execute();
            return $stm->fetchAll();
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
    }
}
?>