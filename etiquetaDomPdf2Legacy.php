<?php

require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
include('qrcode/qrlib.php');


header('Content-type: application/pdf; charset=UTF-8');
set_time_limit(0);
set_include_path('pdf/src/'.PATH_SEPARATOR.get_include_path());
date_default_timezone_set('UTC');

$id_solicitacao_arquivo = $_GET['pedidoID'];
//$id_solicitacao_arquivo = 576711;
include './conexao/conexao.php';




$sql1 = "SELECT * FROM solicitacoes WHERE id_solicitacao = '$id_solicitacao_arquivo'";
$buscar1 = mysqli_query($conexao,$sql1);
$dados1 = mysqli_fetch_array($buscar1);
$identificacao = $dados1['identificacao_pct_solicitacao'];

$status = $dados1['status_solicitacao'];
$tipo_impressao = $dados1['tipo_impressao_solicitacao'];



$sql2 = "SELECT * FROM mapa_solicitacao_plan, plans_mio, status_plans_mio WHERE id_solicitacao = '$id_solicitacao_arquivo' AND id_tipo_solicitacao = $tipo_impressao AND plans_mio.id_plan_mio = mapa_solicitacao_plan.id_plan AND status_plans_mio.id_status_plans_mio = plans_mio.status_plan_mio";
$buscar2 = mysqli_query($conexao,$sql2);
$dados2 = mysqli_fetch_array($buscar2);

$id_planejamento = $dados2['id_plan'];
$id_status_plan = $dados2['status_plan_mio'];
$status_plan = $dados2['status_plans_mio'];


$flagif = 0;
if($id_planejamento > 0){
	if($tipo_impressao == 1){//modelo de estudo com base
		$sql = "SELECT * FROM arquivos_plans_mio, solicitacoes ,usuarios, cor_impressoes, tipo_material_impressao, tipo_impressoes, enderecos_usuarios WHERE arquivos_plans_mio.id_solicitacao_plan_mio = $id_planejamento AND solicitacoes.id_solicitacao= $id_solicitacao_arquivo AND usuarios.id_usuario = solicitacoes.id_usuario_solicitante AND cor_impressoes.id_cor_impressao = solicitacoes.cor_material_solicitacao AND tipo_material_impressao.id_material_impressao = solicitacoes.tipo_material_solicitacao AND tipo_impressoes.id_tipo_imprepressao = solicitacoes.tipo_impressao_solicitacao AND enderecos_usuarios.id_endereco = solicitacoes.entrega_solicitacao  AND arquivos_plans_mio.erro_plan_mio !=6 AND (arquivos_plans_mio.tipo_arquivo_plan_mio = 1 OR arquivos_plans_mio.tipo_arquivo_plan_mio = 2)";
		$buscar = mysqli_query($conexao,$sql);
		$flagif = 1;		
	}
	if($tipo_impressao == 10){//modelo de estudo com base
		$sql = "SELECT * FROM arquivos_plans_mio, solicitacoes ,usuarios, cor_impressoes, tipo_material_impressao, tipo_impressoes, enderecos_usuarios WHERE arquivos_plans_mio.id_solicitacao_plan_mio = $id_planejamento AND solicitacoes.id_solicitacao= $id_solicitacao_arquivo AND usuarios.id_usuario = solicitacoes.id_usuario_solicitante AND cor_impressoes.id_cor_impressao = solicitacoes.cor_material_solicitacao AND tipo_material_impressao.id_material_impressao = solicitacoes.tipo_material_solicitacao AND tipo_impressoes.id_tipo_imprepressao = solicitacoes.tipo_impressao_solicitacao AND enderecos_usuarios.id_endereco = solicitacoes.entrega_solicitacao  AND arquivos_plans_mio.erro_plan_mio !=6 AND arquivos_plans_mio.tipo_arquivo_plan_mio = 3";
		$buscar = mysqli_query($conexao,$sql);
		$flagif = 1;		
	}

}

else{
	$sql = "SELECT * FROM arquivos,solicitacoes, usuarios, cor_impressoes, tipo_material_impressao, tipo_impressoes, enderecos_usuarios WHERE arquivos.id_solicitacao_arquivo = $id_solicitacao_arquivo AND solicitacoes.id_solicitacao= $id_solicitacao_arquivo AND usuarios.id_usuario = solicitacoes.id_usuario_solicitante AND cor_impressoes.id_cor_impressao = solicitacoes.cor_material_solicitacao AND tipo_material_impressao.id_material_impressao = solicitacoes.tipo_material_solicitacao AND tipo_impressoes.id_tipo_imprepressao = solicitacoes.tipo_impressao_solicitacao AND enderecos_usuarios.id_endereco = solicitacoes.entrega_solicitacao  AND arquivos.erro_arquivo !=6";
	$buscar = mysqli_query($conexao,$sql);
	$flagif = 2;
}

$options = new Options();
$options->setChroot(__DIR__);

$codigoQr=$id_solicitacao_arquivo	;

$dompdf = new Dompdf(array('enable_remote' => true));
$msg5 = "";

$msg='<head>

<meta charset="Content-type: text/html; charset=iso-8859-1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></>
<style>
@page {
	margin: 1px;
}
</style>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{
	border-color:black;
	border-style:solid;
	border-width:1px;
	font-family:Helvetica, sans-serif;
	font-size:11px;
	overflow:hidden;
	padding:2px 1px;
	word-break:normal;
}
.tg th{
	border-color:black;
	border-style:solid;
	border-width:1px;
	font-family:Helvetica, sans-serif;
	font-size:11px;
	font-weight:normal;
	overflow:hidden;
	padding:10px 5px;
	word-break:normal;
}
.tg .tg-0lax{
	text-align:left;
	vertical-align:top
}
</style>

<style type="text/css">
	.wrapper-page {
		page-break-after: always;
	}
	.wrapper-page:last-child {
		page-break-after: avoid;
	}
	.tgg  {
		border-spacing:0;
		white-space: pre-wrap;
	}
	.tgg td{
		border-bottom: 1px solid #c0c0c0;
		border-collapse: collapse;
		border-top:0px;
		border-bottom:0px;
		font-family:Helvetica, sans-serif;
		font-size:11px;
		white-space: pre-wrap;
		overflow:hidden;
		padding:1px 1px;
		word-break:normal;
	}
	.tgg th{
		border-bottom: 1px solid #c0c0c0;
		border-collapse: collapse;
		border-top:0px;
		border-bottom:0px;
		font-family:Helvetica, sans-serif;
		font-size:11px;
		white-space: pre-wrap;
		overflow:hidden;
		padding:1px 1px;
		word-break:normal;;
	}
	.tgg .tgg-w84r{
		background-color:#9b9b9b;
		border-color:##c0c0c0;
		font-weight:bold;
		text-align:left;
		vertical-align:top;
		white-space: pre-wrap;
		padding:3px 3px;
	}
	.tgg .tgg-73oq{
		border-color:##c0c0c0;
		text-align:left;
		vertical-align:top;
		white-space: pre-wrap;
	}
	.tgg .tgg-0lax{
		text-align:left;
		vertical-align:top
	}

</style>



<title></title>
</head><font size="1" face="Helvetica">  
<p>   
	<img src="http://www.labpronto.com.br/hub/assets/img/brand/blue.jpg" align="left"style="width:163px;height:57px;">
	<div align="right">
		<br><b>CNPJ:</b> 37.605.824/0001-03<br>Av. Amintas Barros, 3700 Sala 1907 <br> Lagoa Nova - Natal/RN<br>
		(84) 98634-6730<br>
	</div>
</p>
';

$flag=0;
while($row_usuario = mysqli_fetch_assoc($buscar)){

	$pedidoId = $row_usuario['id_solicitacao'];
	$pedidoNome = $row_usuario['identificacao_pct_solicitacao'];
	$usuario = $row_usuario['nome_usuario'];

	$dataformat = new DateTime($row_usuario['data_solicitacao']);
	$dataIn2 = $dataformat->format('Y-m-d H:i:s');
	$dataIn = date('d/m/Y H:i', strtotime($dataIn2. ' -3 hours'));


	$dataformat = new DateTime($row_usuario['finalizacao_solicitacao']);
	$dataOut2 = $dataformat->format('Y-m-d H:i:s');
	$dataOut = date('d/m/Y H:i', strtotime($dataOut2. ' -3 hours'));

	$cor = $row_usuario['cor_impressao'];
	$material = $row_usuario['material_impressao'];
	$tipo = $row_usuario['tipo_impressao'];
	$quantidade = $row_usuario['quantidade_solicitacao'];
	$id_endereco = $row_usuario['id_endereco'];
	if($row_usuario['complemento_endereco']==NULL){
		$endereco = $row_usuario['logradouro_endereco'].", ".$row_usuario['numero_endereco'].", ".$row_usuario['bairro_endereco'].", ".$row_usuario['cidade_endereco']." - ".$row_usuario['cep_endereco'];
	}
	else{
		$endereco = $row_usuario['logradouro_endereco'].", ".$row_usuario['numero_endereco']." - ".$row_usuario['complemento_endereco'].", ".$row_usuario['bairro_endereco'].", ".$row_usuario['cidade_endereco']." - ".$row_usuario['cep_endereco'];
	}

	if($flag==0){
		$msg1 = '<label><div align="right"><img src="https://www.medi3d.com.br/hub/qrcodepng.php?id='. $codigoQr.'" align="right"style="width:85px;height:85px;"></div><div align="left"><br></label>
			<div align="left"><b>Solicitação nº '.$pedidoId.' - '. $pedidoNome .'</b><br><br></div>
			<label><b>Solicitante: </b><mark>'. $usuario.'</mark><br></label>
			<label><b>Endereço: </b><mark>';
		if($id_endereco==1){
			$msg2='Retirada';
		}
		else{
			$msg2=$endereco;
		}

		$msg3 = '</mark><br></label><label>     <b>Solicitação:</b>'. $dataIn.'<b>  Finalização: </b>'. $dataOut.'<br></label><label><b>Tipo de impressão: </b>'.$tipo.'<br></label>
		<label><b>Material:</b>'. $material.' - '.$cor.'<br><br></label><label>  
		</label></th><table class="tgg"width=270>
		<thead><tr><th class="tgg-w84r"></th><th class="tgg-w84r">'.$quantidade.' Modelos      - '.'      Nº '.$pedidoId.' - '. $pedidoNome .'</th>
		</tr>
		</thead>';
		$flag=1;
	}
	$msg4 ='<tbody >'; 

	if($flagif ==1){
	    if($row_usuario['nome_planner_plan_mio'] != NULL){
	       	$msg5 = $msg5.'<tr><td class="tgg-0lax"></td><td class="tgg-0lax">' .$row_usuario['nome_planner_plan_mio'] . '</td></tr>'; 
	    }
	}

	if($flagif ==2){
	    if($row_usuario['nome_arquivo_original'] != NULL){
	       	$msg5 = $msg5.'<tr><td class="tgg-0lax"></td><td class="tgg-0lax">' .$row_usuario['nome_arquivo_original'] . '</td></tr>'; 
	    }
	}
	
    $msg6='</th></tr>';
}
$msg7='</tbody><div style="page-break-after: always;"></div><!--   Core   -->
<script src="./assets/js/plugins/jquery/dist/jquery.min.js"></script>
<!--   Optional JS   -->
<script src="./assets/js/plugins/chart.js/dist/Chart.min.js"></script>
<script src="./assets/js/plugins/chart.js/dist/Chart.extension.js"></script>
<!--   Argon JS   -->
<script src="./assets/js/argon-dashboard.min.js?v=1.1.0"></script>
<script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>';

$html = file_get_contents("pagvazia.php");
$dompdf->setPaper('A6', 'portrait');

//$html = "http://localhost/hub/paginaDomPdf?id_solicitacao_arquivo=". $id_solicitacao_arquivo;
//$dompdf->load_html(utf8_decode($html));
//$dompdf->loadHTML('<h1>Tessssst</h1>');
//$dompdf->loadHTML(file_get_contents("paginaDomPdf.php?id_solicitacao_arquivo=104"));

/*$dompdf->loadHTML('<html>

<head>
<title>My first PHP Page</title>
</head>
<body>
This is normal HTML code

<?php 
echo $test;
?>

Back into normal HTML

</body>
</html>');*/

$eee = $msg . $msg1 . $msg2 . $msg3 .$msg4 .$msg5 . $msg6 . $msg7;

$msgenvio = utf8_encode($eee);
$dompdf->load_html(utf8_decode($msgenvio));
$dompdf->render();
//header('Content-type: application/pdf');
//$dompdf->stream("etiqueta.pdf", array(true)); //já salva o arquivo	
echo $dompdf->output();
?>