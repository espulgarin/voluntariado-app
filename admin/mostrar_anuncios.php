<?php include'db_connect.php';?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=nuevo_anuncio"><i class="fa fa-plus"></i> Nuevo anuncio</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="25%">
					<col width="20%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Fecha de inicio</th>
						<th>Anuncio</th>
						<th>Categoría</th>
						<th>Estado</th>
						<th>Acción	</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					//$sql = "SELECT * FROM events order by unix_timestamp(date_created) desc ";
					$sql = "SELECT id, event_datetime, event, description, venue, status, date_created, c.nombre
					 FROM events e
					 INNER JOIN categorias c
					 ON e.venue = c.id_categoria
					 order by unix_timestamp(date_created) desc ";
					$qry = $conn->query($sql);

						while($row= $qry->fetch_assoc()):
						?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php setlocale(LC_TIME, NULL); 
								echo utf8_encode(
										ucfirst(
											strftime("%A, %d de %B de %Y, %I:%M %p", strtotime($row['event_datetime']))));
								/*date("d M, Y h:i A",strtotime($row['event_datetime']))*/ ?></b></td>
						<td><b><?php echo ucwords($row['event']) ?></b></td>
						<td><b><?php echo ucwords($row['nombre']) //echo ucwords($row['venue']) ?></b></td>
						<td class="text-center">
							<div class="d-block">
							<?php if($row['status'] == 0): ?>
							<?php if(strtotime(date("Y-m-d H:i:s")) < strtotime($row['event_datetime'])): ?>
								<span class="badge badge-secondary">Pendiente</span>
							<?php else: ?>
								<input type="checkbox" name="status_chk" id="" checked data-bootstrap-switch data-toggle="toggle" data-on="Abierto" data-off="Cerrado" class="switch-toggle status_chk" data-size="xs" data-offstyle="secondary" data-width="5rem" data-id='<?php echo $row['id'] ?>'>
								<br>
								<small><i>Clic para actualizar estado.</i></small>
							<?php endif; ?>
							<?php elseif($row['status'] == 1): ?>
								<input type="checkbox" name="status_chk" id="" checked data-bootstrap-switch data-toggle="toggle" data-on="Abierto" data-off="Cerrado" class="switch-toggle status_chk" data-size="xs" data-offstyle="secondary" data-width="5rem" data-id='<?php echo $row['id'] ?>'>
								<br>
								<small><i>Clic para actualizar estado.</i></small>
								
							<?php elseif($row['status'] == 2): ?>
								<input type="checkbox" name="status_chk" id="" data-bootstrap-switch data-toggle="toggle" data-on="Abierto" data-off="Cerrado" class="switch-toggle status_chk" data-size="xs" data-offstyle="secondary" data-width="5rem" data-id='<?php echo $row['id'] ?>'>
								<br>
								<small><i>Clic para actualizar estado.</i></small>
							<?php endif; ?>
							</div>
						</td>
						<td class="text-center">
		                    <div class="btn-group">
		                        <a href="./index.php?page=editar_anuncio&id=<?php echo $row['id'] ?>" class="btn btn-primary btn-flat">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                         <a href="./index.php?page=ver_anuncio&id=<?php echo $row['id'] ?>" class="btn btn-info btn-flat">
		                          <i class="fas fa-eye"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat delete_event" data-id="<?php echo $row['id'] ?>">
		                          <i class="fas fa-trash"></i>
		                        </button>
	                      </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>

	$(document).ready(function(){
		$('#list').dataTable
		(
			{
				"language": {
					"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
			}}
		)
	$('.delete_event').click(function(){
	_conf("Está seguro de eliminar este anuncio?","delete_event",[$(this).attr('data-id')])
	})

	$('.status_chk').change(function(){
		var status = $(this).prop('checked') == true ? 1 : 2;
		if($(this).attr('data-state-stats') !== undefined && $(this).attr('data-state-stats') == 'error'){
			$(this).removeAttr('data-state-stats')
			return false;
		}
		// return false;
		var id = $(this).attr('data-id');
		start_load()
		$.ajax({
			url:'ajax.php?action=update_event_stats',
			method:'POST',
			data:{id:id,status:status},
			error:function(err){
				console.log(err)
				alert_toast("Ocurrió un error al actualizar los detalles del anuncio.",'error')
					$('#status_chk').attr('data-state-stats','error').bootstrapToggle('toggle')
					end_load()
			},
			success:function(resp){
				if(resp == 1){
					alert_toast("Se actualizó el anuncio correctamente.",'success')
					end_load()
				}else{
					alert_toast("Ocurrió un error al actualizar el estado.",'error')
					$('#status_chk').attr('data-state-stats','error').bootstrapToggle('toggle')
					end_load()
				}
			}
		})
	})
	})
	function delete_event($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_event',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Anuncio eliminado correctamente",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>