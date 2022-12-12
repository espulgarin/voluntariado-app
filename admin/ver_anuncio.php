<?php
include 'db_connect.php' ;	

//$qry = $conn->query("SELECT * FROM events where id = {$_GET['id']}")->fetch_array();
$qry = $conn->query("SELECT id, event_datetime, event, description, venue, status, date_created, c.nombre
FROM events e
INNER JOIN categorias c
ON e.venue = c.id_categoria
where id = {$_GET['id']}")->fetch_array();
foreach($qry as $k =>$v){
	$$k = $v;
}

?>

<div class="col-lg-12">
	<div class="row">
		<div class="col-md-8">
			<div class="card card-outline card-info">
				<div class="card-body">
					<div class="">
						<dl class="callout callout-info">
							<dt>Anuncio</dt>
							<dd><?php echo ucwords($event) ?></dd>
						</dl>
						<dl class="callout callout-info">
							<dt>Fecha de inicio</dt>
							<dd><?php setlocale(LC_TIME, NULL); 
								echo utf8_encode(
										ucfirst(
											strftime("%A, %d de %B de %Y, %I:%M %p", strtotime($event_datetime))));
								/*date("d M, Y h:i A",strtotime($row['event_datetime']))*/ ?></dd>
						</dl>
						<dl class="callout callout-info">
							<dt>Categoría</dt>
							<dd><?php echo ucwords($nombre) ?></dd>
						</dl>
						<dl class="callout callout-info">
							<dt>Descripción</dt>
							<dd><?php echo html_entity_decode($description) ?></dd>
						</dl>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card card-outline card-warning">
				<div class="card-header">
					<b>Registrador asignado</b>
					<div class="card-tools">
						<button class="btn btn-sm btn-flat btn-default bg-light" id="manage-registrar" type="button">Gestionar</button>
					</div>
				</div>
				<div class="card-body">
					<ul class="nav flex-column">
						<?php 
						$r = $conn->query("SELECT ar.*,concat(u.firstname, ' ',u.lastname) as name,u.firstname,u.lastname FROM assigned_registrar ar inner join users u on u.id = ar.user_id where event_id = {$_GET['id']} order by concat(u.lastname,', ',u.firstname,' ',u.middlename) asc");
						while($row=$r->fetch_assoc()):
						?>
						<li class="nav-item text-dark">
							<div class="d-flex align-items-center py-1">
								<span class="brand-image mr-2 img-circle elevation-2 d-flex justify-content-center align-items-center bg-primary text-white font-weight-500" style="width: 30px;height:30px"><b><?php echo strtoupper(substr($row['firstname'], 0,1).substr($row['lastname'], 0,1)) ?></b></span>
								<span><b><?php echo ucwords($row['name']) ?></b></span>
							</div>
						</li>
						<?php endwhile; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('#manage-registrar').click(function(){
		uni_modal("Gestionar registrador asociado al anuncio","manage_registrar.php?id=<?php echo $id ?>");
	})
</script>