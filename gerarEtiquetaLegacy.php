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
						<h3 class="mb-0">Gerador de Etiqueta Manual Legacy</h3>
					</div>

					<div class="container" style="margin-top: 10px">
						<form action="etiquetaDomPdf2Legacy.php" method="get" enctype="multipart/form-data">

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label>ID do pedido</label>
										<input type="number" class="form-control form-control-alternative"  placeholder="ID" name="pedidoID" required="" autofocus="" autocomplete="off">
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