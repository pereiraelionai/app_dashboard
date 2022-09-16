<?php

class Dashboard {

    public $data_inicio;
    public $data_fim;
    public $numero_vendas;
    public $total_vendas;

    public function __get($attr) {
        return $this->$attr;
    }

    public function __set($attr, $valor) {
        $this->$attr = $valor;
        return $this;
    }
}

//class de conexao com o banco
class Conexao {
    private $host = 'localhost';
    private $dbname = 'dashboard';
    private $user = 'root';
    private $pass = '';

    public function conectar () {
        try {

            $conexao = new PDO(
                "mysql:host=$this->host;dbname=$this->dbname",
                "$this->user",
                "$this->pass"
            );

            //utilizar charset utf8 (isso por que o banco foi criado via sql)
            $conexao->exec('set charset utf8');

            return $conexao;


        } catch (PDOException $erro) {
            echo '<p>' . $erro->getMessege() . '</p>';
        }
    }
}

class Bd {
    private $conexao;
    private $dashboard;

    public function __construct(Conexao $conexao, Dashboard $dashboard) {
        $this->conexao = $conexao->conectar();
        $this->dashboard = $dashboard;
        $this->data_inicio = $dashboard->__get('data_inicio'); // pode ser feito dessa forma ou mandando o $this_>dashboard direto no bindValue
    }

    public function getNumeroVendas() {
        $query = "select
                    count(*) as numero_vendas 
                from 
                    tb_vendas 
                where 
                    data_venda between :data_inicio and :data_fim";
        
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue('data_inicio', $this->data_inicio);
        $stmt->bindValue('data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getTotalVendas() {
        $query = "select
                    SUM(total) as total_vendas 
                from 
                    tb_vendas 
                where 
                    data_venda between :data_inicio and :data_fim";
        
        $stmt = $this->conexao->prepare($query);
        $stmt->bindValue('data_inicio', $this->data_inicio);
        $stmt->bindValue('data_fim', $this->dashboard->__get('data_fim'));
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }    
}

//logica do script
$competencia = explode('-',$_GET['competencia']);
$ano = $competencia[0];
$mes = $competencia[1];
$dias_do_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);


$dashboard = new Dashboard();
$dashboard->__set('data_inicio', $ano . '-'. $mes . '-' . '01');
$dashboard->__set('data_fim', $ano . '-'. $mes . '-' . $dias_do_mes);

$conexao = new Conexao();


$bd = new Bd($conexao, $dashboard);

$num_vendas = $bd->getNumeroVendas()->numero_vendas;
$total_vendas_bd = $bd->getTotalVendas()->total_vendas;
$dashboard->__set('total_vendas', $total_vendas_bd);
$dashboard->__set('numero_vendas', $num_vendas);
//print_r($dashboard);
//echo $_GET['competencia'];
//print_r($competencia);
echo json_encode($dashboard);


?>