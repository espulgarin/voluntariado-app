<?php 
include 'db_connect.php';
$eid = isset($_GET['eid']) ? $_GET['eid'] : '';
if(!empty($eid)){
	$event = $conn->query("SELECT id, event_datetime, event, description, venue, status, date_created, c.nombre
	FROM events e
	INNER JOIN categorias c
	ON e.venue = c.id_categoria
	where id = $eid")->fetch_array();
	foreach($event as $k => $v){
		$$k = $v;
	}
}
?>
<div class="col-lg-12">
	<div class="card card-outline card-info">
		<div class="card-header">
			<b>Asistencia</b>
			<div class="card-tools">
				<button class="btn btn-success btn-flat" type="button" id="print_record">
				<i class="fa fa-print"></i> Imprimir</button>
			</div>
			
		</div>
		<div class="card-body">
		<div class="row justify-content-center">
			<label for="" class="mt-2">Anuncio</label>
			<div class="col-sm-4">
	            <select name="eid" id="eid" class="custom-select select2" lang="es">
	                <option value=""></option>
	                <?php
	                $events = $conn->query("SELECT * FROM events order by event asc");
	                while($row=$events->fetch_assoc()):
	                ?>
	                <option value="<?php echo $row['id'] ?>" data-cid="<?php echo $row['id'] ?>" <?php echo $eid == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['event']) ?></option>
	                <?php endwhile; ?>
	            </select>
			</div>
		</div>
		<hr>
		<?php if(empty($eid)): ?>
			<center> Seleccione un evento.</center>
		<?php else: ?>
			<table class="table table-condensed table-bordered table-hover" id="att-records">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="">Voluntario</th>
						<th class="">Programa</th>
						<th class="">Correo</th>
						<th class="">Número de contacto</th>
						<th class="">Estado</th>
					</tr>
				</thead>
				<tbody>
					<?php 
	                $i = 1;
					$attendees = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM attendees where event_id= $eid order by concat(firstname,' ',lastname) asc ");
					if($attendees->num_rows > 0):
	                while($row=$attendees->fetch_assoc()):
	                    if($row['status'] == 1)
	                        $stats = "En curso";
	                    else
	                        $stats = "Pendiente";
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td class=""><?php echo ucwords($row['name']) ?></td>
						<td class=""><?php echo ucwords($row['middlename']) ?></td>
						<td class=""><?php echo $row['email'] ?></td>
						<td class=""><?php echo $row['contact'] ?></td>
						<td class=""><?php echo $stats ?></td>
					</tr>
					<?php endwhile; ?>
					<?php else: ?>
					<tr>
						<th colspan="6"><center>Sin resulta dos.</center></th>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
		<?php endif; ?>

		</div>
	</div>
</div>
<noscript>
	<style>
		table#att-records{
			width:100%;
			border-collapse:collapse
		}
		table#att-records td,table#att-records th{
			border:1px solid
		}
		.text-center{
			text-align:center
		}
	</style>
	<div>
		<p><b>Anuncio: <?php echo isset($event) ? ucwords($event) : '' ?></b></p>
		<p><b>Categoría: <?php echo isset($venue) ? ucwords($nombre) : '' ?></b></p>
	</div>
</noscript>
<script>
	$(document).ready(function(){
		$('#eid').change(function(){
			location.href = 'index.php?page=asistencia&eid='+$(this).val()
		})	
		
		$('#print_record').click(function(){
		var _c = $('#att-records').clone();
		var ns = $('noscript').clone();
			ns.append(_c)
		var nw = window.open('','_blank','width=900,height=600')
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(() => {
			nw.close()
		}, 500);
	})
	})
	
</script>