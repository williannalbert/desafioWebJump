<?php
require_once('database.php');

class categoriesSql
{
    private $id;
    private $nome;
    protected $dbConn;

    public function __construct($id = 0, $nome = "")
    {
        $this->id = $id;
        $this->nome = $nome;

        $this->dbConn = new PDO(DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS,[PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }
    public function getNome()
    {
        return $this->nome;
    }

    public function insert()
    {
        try 
        {
            $consulta = $this->getOne();
            if(isset($consulta[0]))
            {
                echo "<script>alert('Já existe uma categoria com esse Id');document.location='assets/categories.php'</script>";
            }
            else
            {
                $stm = $this->dbConn->prepare("INSERT INTO categorias (Id, Nome) values (?, ?)");
                $stm->execute( [$this->id, $this->nome]);
                echo "<script>alert('Inclusão realizada com sucesso.');document.location='assets/categories.php'</script>";
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
            $stm = $this->dbConn->prepare("SELECT Id, Nome FROM categorias");
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
            $stm = $this->dbConn->prepare("SELECT Id, Nome FROM categorias WHERE Id = ?");
            $stm->execute([$this->id]);
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
            $stm = $this->dbConn->prepare("UPDATE categorias SET Id = ?, Nome = ? WHERE Id = ?");
            $stm->execute([$this->id, $this->nome, $id_update]);

            $atualizar = $this->dbConn->prepare("UPDATE produtocategoria SET categoria_id = ? WHERE categoria_id = ?");
            $atualizar->execute([$this->id, $id_update]);
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
            $consulta = $this->dbConn->prepare("SELECT produto_sku FROM produtocategoria WHERE categoria_id = ?");
            $consulta->execute([$this->id]);
            $retorno = $consulta->fetchAll();
            if(isset($retorno[0]))
            {
                echo "<script>alert('Existem produtos cadastrados com essa categoria.');document.location='assets/categories.php'</script>";
            }
            else
            {
                $stm = $this->dbConn->prepare("DELETE FROM categorias WHERE id = ?");
                $stm->execute([$this->id]);
                return $stm->fetchAll();
            }
        }
        catch(Exception $ex)
        {
            return $ex->getMessage();
        }
    }
}
?>