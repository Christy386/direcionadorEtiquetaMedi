# direcionadorEtiquetaMedi
documentação do novo sistema de etiquetas na parte do servidor PHP feitas por Christy.

## Arquivos modificados do servidor PHP

`etiquetaDomPdf2.php` --> Modificada para não mais gerar a etiqueta, mas para apenas buscar no servidor os dados necessarios de determinado ID de serviço e redirecionar esses dados para uma nova aplicação web NodeJs que retorna a etiqueta com o QR code.

`etiquetaDomPdf2Legacy.php` --> Arquivo legado de `etiquetaDomPdf2.php`, ou seja, sem modificações.

`gerarEtiquetaLegacy.php` --> Arquivo criado para gerar uma nova etiqueta apenas com o Id e fazer uma chamada get para o arquivo `etiquetaDomPdf2.php`.

`gerarEtiquetaManual.php` --> Arquivo modificado para gerar uma nova etiqueta colocando manualmente cada paramentro da etiqueta, mas para o novo sistema de criação de etiquetas.

`gerarEtiquetaManualLegacy.php` --> É o arquivo original de `gerarEtiquetaManual.php`, ou seja, utilizando ainda o sistema de criação de etiquetas antigo.

## etiquetaDomPdf2.php


O código começa com a obtenção do parâmetro "id_solicitacao_arquivo" a partir da superglobal $_GET. Esse parâmetro é armazenado na variável $id_solicitacao_arquivo, que posteriormente é atribuída à variável $data.
```php
$id_solicitacao_arquivo = $_GET['id_solicitacao_arquivo'];
$data = $id_solicitacao_arquivo; 
```

A próxima linha inclui um arquivo de conexão ao banco de dados utilizando a função include e especificando o caminho do arquivo de conexão.
```php 
include './conexao/conexao.php';
```
Em seguida, é executada uma consulta SQL para buscar informações da tabela "solicitacoes" com base no ID da solicitação fornecido. O resultado da consulta é armazenado na variável $buscar1 e os dados são recuperados usando a função mysqli_fetch_array(), que retorna um array associativo contendo os valores da linha obtida. Algumas informações relevantes são atribuídas a variáveis, como $identificacao, $status e $tipo_impressao.
```php 
$sql1 = "SELECT * FROM solicitacoes WHERE id_solicitacao = '$id_solicitacao_arquivo'";
$buscar1 = mysqli_query($conexao,$sql1);
$dados1 = mysqli_fetch_array($buscar1);
$identificacao = $dados1['identificacao_pct_solicitacao'];

$status = $dados1['status_solicitacao'];
$tipo_impressao = $dados1['tipo_impressao_solicitacao'];
```
A segunda consulta SQL é realizada para buscar informações em várias tabelas, como "mapa_solicitacao_plan", "plans_mio" e "status_plans_mio", com base no ID da solicitação e no tipo de impressão. O resultado da consulta é armazenado na variável $buscar2 e os dados são recuperados em um array associativo, atribuídos a variáveis como $id_planejamento, $id_status_plan e $status_plan.

```php
$sql2 = "SELECT * FROM mapa_solicitacao_plan, plans_mio, status_plans_mio WHERE id_solicitacao = '$id_solicitacao_arquivo' AND id_tipo_solicitacao = $tipo_impressao AND plans_mio.id_plan_mio = mapa_solicitacao_plan.id_plan AND status_plans_mio.id_status_plans_mio = plans_mio.status_plan_mio";
$buscar2 = mysqli_query($conexao,$sql2);
$dados2 = mysqli_fetch_array($buscar2);

$id_planejamento = $dados2['id_plan'];
$id_status_plan = $dados2['status_plan_mio'];
$status_plan = $dados2['status_plans_mio'];
```

Em seguida, há uma verificação condicional usando a variável $id_planejamento. Se o valor for maior que zero, significa que existe um planejamento associado à solicitação. Dentro dessa condição, há mais duas verificações condicionais com base no valor de $tipo_impressao. Dependendo do tipo, diferentes consultas SQL são executadas para buscar informações de diferentes tabelas. O resultado é armazenado na variável $buscar e a variável $flagif é definida como 1.

Se o valor de $id_planejamento for igual a zero, significa que não há planejamento associado à solicitação. Nesse caso, é executada uma consulta SQL diferente para buscar informações de outras tabelas. O resultado é armazenado na variável $buscar e a variável $flagif é definida como 2.

```php
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
```
Em seguida, há uma atribuição à variável $codigoQr, que recebe o valor de $id_solicitacao_arquivo.

```php
$codigoQr=$id_solicitacao_arquivo;
```
O restante do código é um bloco de strings que contém HTML e marcações para formatação. Esse bloco é armazenado na variável $msg e é composto por elementos HTML, como cabeçalho, estilo CSS e o corpo da página. Há uma série de variáveis PHP incorporadas no bloco de strings que são usadas para exibir as informações obtidas anteriormente a partir do banco de dados.

```php
$msg5 = "";

$msg='
<head>

<style>
	@page{
		margin: 50px;
		
	}
	body{
		
	}
	div{
		margin:0;
	}
	mark{
		font-size: 12px;
	}
	.tableTitle{
		background-color: #000;
		margin-top: 10px; 
		margin-bottom: 10px;
	}
	.titleText{
		color: #fff;
		margin-left: 2px;
		font-size: 15px;
	}
	.fileName{
		#background-color: #aaa;
		margin-top: 2px;
		margin-left: 12px;
		border-color: #000;
		
		border-bottom: 1px solid #aaa;
	}
</style>


</head>
<font size="1" face="Helvetica">

<body>

<div>
'.$base64_qr_url.'   
<div style="margin-top: 5px">
	<img src="'.$base64_logo_url.'" align="left" style="width:163px;height:57px;">
<div>
<div align="right">
	<br>
	<b>CNPJ:</b> 37.605.824/0001-03<br>Av. Amintas Barros, 3700 Sala 1907 <br> Lagoa Nova - Natal/RN
	<br>
	(84) 98634-6730<br>
</div>
</div>
';
```

Dentro de um loop while, as informações recuperadas da consulta são atribuídas a variáveis e são formatadas para serem exibidas na página HTML.

```php
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
		$msg1 = '
		<div style="position:fixed; margin-left: 195px; margin-top: 25px;">
			<img src="'.$base64_qr_url.'" align="left" style="width:110px; height:110px;">
            
		</div>
								
		<div align="center" style="margin-top: 10px">
			<b>SOLICITAÇÃO Nº '.$pedidoId.' - '. $pedidoNome .'</b>
			<br>
			<br>
		</div>


		<div>
			<b>Solicitante: </b>
			<mark>'. $usuario.'</mark>
			<br>
		</div>
		<label><b>Endereço: </b><mark>
		';
		if($id_endereco==1){
			$msg2='Retirada';
		}
		else{
			$msg2=$endereco;
		}

	$msg3 = '
		</mark>
		<br>
		</div>
		<div>
			<b>Solicitação:</b>'. $dataIn.
			'<br><b>  Finalização: </b>'. $dataOut.
			'<br>
		</div>
		<div>
			<b>Tipo de impressão:
			<br> 
			</b>'.$tipo.
			'<br>
		</div>
		<div>
			<b>Material: </b>'. $material.' - '.$cor.
			'
			<br>
		</div>
		
		<div class="tableTitle">
			
			<a class="titleText" >'.
				$quantidade.' Modelos      - '.'      Nº '.$pedidoId.' - '. $pedidoNome .
			'</a>
		
		</div>
	';
	$flag=1;
	}
	$msg4 =''; 



	if($flagif ==1){
		if($row_usuario['nome_planner_plan_mio'] != NULL){
			$msg5 = $msg5.$row_usuario['nome_planner_plan_mio'].'%20'; 
		}
 	}

	 if($flagif ==2){
		if($row_usuario['nome_arquivo_original'] != NULL){
			$msg5 = $msg5.$row_usuario['nome_arquivo_original'].'%20';  
		}
 	}

	$msg6='';

}
```

Ao final do código, há uma concatenação de todas as partes do documento HTML, desde $msg até $msg7, para formar a página completa.

```php
$msg7='</body>';				

$eee = $msg . $msg1 . $msg2 . $msg3 .$msg4 .$msg5 . $msg6 . $msg7;
```

Por fim, há um redirecionamento por meio de JavaScript para uma URL específica, dependendo do servidor de destino, onde os parâmetros são passados como query strings.

```php
echo('<script>
	window.location.href = `
		http://etiquetas.medi3d.cloud/pdf/?
			pedidoId='.$pedidoId.'&
			pedidoNome='. $pedidoNome .'&
			usuario='.$usuario.'&
			endereco='.$msg2.'&
			dataIn='.$dataIn.'&
			dataOut='.$dataOut.'&
			tipo='.$tipo.'&
			material='.$material.'&
			cor='.$cor.'&
			quantidade='.$quantidade.'&
			list='.$msg5.'`;

</script>');//*/

?>
```