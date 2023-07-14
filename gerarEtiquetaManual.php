<?php
include_once 'menuAdmin.php';
include 'conexao/conexao.php';

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="estilotabela.css">
  	<title></title>
</head>
<body>


	<div class="container-fluid mt--7">
		<!-- Table -->
		<div class="row">
			<div class="col">
				<div class="card shadow">
					<div class="card-header border-1">
						<h3 class="mb-0">Gerador de Etiqueta Manual</h3>
					</div>




					<div class="container" style="margin-top: 10px">
						<form action="etiquetaManual.php" method="post" enctype="multipart/form-data">

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label>ID do pedido</label>
										<input type="number" class="form-control form-control-alternative"  placeholder="ID" name="pedidoID" required="" autofocus="" autocomplete="off">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Nome do pedido</label>
										<input type="text" class="form-control form-control-alternative"  placeholder="Nome" name="nomePedido" required="" autofocus="" autocomplete="off">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label>Usuarioooo</label>		
										<input type="text" class="form-control form-control-alternative"  placeholder="Nome" name="usuarioPedido" required="" autofocus="" autocomplete="off">						   
										<?php /*
										<select class="form-control form-control-alternative" id="usuarioPedido" name="usuarioPedido">
											<option>Selecione o usuario</option>
											<?php
											$sql = 'SELECT * FROM usuarios, nivel_acessos WHERE nivel_acessos.id_nivel_acesso = usuarios.nivel_acesso_usuario AND (nivel_acessos.id_nivel_acesso = 4 OR nivel_acessos.id_nivel_acesso = 5 OR nivel_acessos.id_nivel_acesso = 9 OR nivel_acessos.id_nivel_acesso = 11 OR nivel_acessos.id_nivel_acesso = 12)';

											$buscar = mysqli_query($conexao,$sql);

											while ($dados = mysqli_fetch_array($buscar)) {
												$id = $dados['id_usuario'];
												$nome = $dados['nome_usuario'];

												?>
												<option value="<?php echo $nome ?>"><?php echo $nome?></option>

											<?php } ?>
										</select>
										*/?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label>Endereço</label>								   
										<select class="form-control form-control-alternative" id="endereco" name="endereco">
											<option>Selecione o endereço</option>
											<?php
											$sql = 'SELECT * FROM enderecos_usuarios';

											$buscar = mysqli_query($conexao,$sql);

											while ($dados = mysqli_fetch_array($buscar)) {
												if($dados['complemento_endereco']==NULL){
													$endereco = $dados['logradouro_endereco'].", ".$dados['numero_endereco'].", ".$dados['bairro_endereco'].", ".$dados['cidade_endereco']." - ".$dados['cep_endereco'];
												}
												else{
													$endereco = $dados['logradouro_endereco'].", ".$dados['numero_endereco']." - ".$dados['complemento_endereco'].", ".$dados['bairro_endereco'].", ".$dados['cidade_endereco']." - ".$dados['cep_endereco'];
												}
												if($dados['id_endereco']==1){
													$endereco='Retirada';
												}


												?>
												<option value="<?php echo $endereco ?>"><?php echo $dados['tipo_endereco'] . ' - ' .$endereco?></option>

											<?php } ?>
										</select>
									</div>
								</div>
							</div>




							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label>Data da solicitação</label>
										<input type="date" class="form-control form-control-alternative"  placeholder="Identificação" name="datain" required="" autofocus="" autocomplete="off">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label>Data finalização</label>
										<input type="date" class="form-control form-control-alternative"  placeholder="Identificação" name="dataout" required="" autofocus="" autocomplete="off">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Tipo de impressão</label>
										<select class="form-control form-control-alternative" id="exampleFormControlSelect1" name="tipo">
											<option>Selecione a cor do Material</option>
											<?php
											$sql = 'SELECT * FROM tipo_impressoes WHERE disp_tipo_impressao =1';
											$buscar = mysqli_query($conexao,$sql);
											while ($dados = mysqli_fetch_array($buscar)) {
												$tipo_imp = $dados['tipo_impressao'];
												?>
												<option value="<?php echo $tipo_imp?>"><?php echo $tipo_imp ?></option>
											<?php } ?>
										</select>

									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="exampleFormControlSelect1">Cor do Material</label>
										<select class="form-control form-control-alternative" id="exampleFormControlSelect1" name="cor">
											<option>Selecione a cor do Material</option>
											<?php
											$sql = 'SELECT * FROM cor_impressoes WHERE disp_cor_impressao =1';
											$buscar = mysqli_query($conexao,$sql);
											while ($dados = mysqli_fetch_array($buscar)) {
												$cor_impressao = $dados['cor_impressao'];
												?>
												<option value="<?php echo $cor_impressao?>"><?php echo $cor_impressao ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="exampleFormControlSelect1">Material</label>
										<select class="form-control form-control-alternative" id="exampleFormControlSelect1" name="material">
											<option>Selecione o material</option>
											<?php
											$sql = 'SELECT * FROM tipo_material_impressao WHERE disp_material_impressao =1';
											$buscar = mysqli_query($conexao,$sql);
											while ($dados = mysqli_fetch_array($buscar)) {
												$material_imp = $dados['material_impressao'];
												?>
												<option value="<?php echo $material_imp?>"><?php echo $material_imp ?></option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label>Quantidade</label>
										<input type="number" class="form-control form-control-alternative"  placeholder="Quantidade" name="quantidade" required="" autofocus="" autocomplete="off">
									</div>
								</div>
							</div>

							<div class = "row">
								<div class="col-md-8">
									<div class="form-group">
										<label for="exampleFormControlTextarea1">Arquivos</label>
										<textarea class="form-control" id="exampleFormControlTextarea1"  name="arquivos" rows="8"></textarea>
									</div>
								</div>
							</div>






							<div class="card-footer py-4">
								<div class="col-md-8">
									<div style="text-align: right">
										<button type="submit"  class="btn btn-primary">Gerar etiqueta</button>
									</div>
								</div>

							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>


	<?php 
	include 'footer.php';
	include 'rodape.php';
	?>

</body>
</html>