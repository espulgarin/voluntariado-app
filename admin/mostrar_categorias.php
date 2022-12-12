<?php include'db_connect.php';?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=nueva_categoria"><i class="fa fa-plus"></i> Nueva categoría</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="25%">
 					<col width="55%">
<!--					<col width="20%">
					<col width="15%"> -->
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Nombre de la categoría</th>
						<th>Descripción</th>

						<th>Acción	</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					//$sql = "SELECT * FROM events order by unix_timestamp(date_created) desc ";
					$sql = "SELECT * 
							FROM categorias";
					$qry = $conn->query($sql);

						while($row= $qry->fetch_assoc()):
						?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords($row['nombre']) ?></b></td>
						<td>
							<div class="d-block">
								<?php echo ucfirst($row['descripcion'])  ?>
							</div>
						</td>
						
						<td class="text-center">
		                    <div class="btn-group">
		                        <a href="./index.php?page=editar_categoria&id=<?php echo $row['id_categoria'] ?>" class="btn btn-primary btn-flat">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                         <!-- <a href="./index.php?page=ver_categoria&id=<?php //echo $row['id_categoria'] ?>" class="btn btn-info btn-flat">
		                          <i class="fas fa-eye"></i>
		                        </a> -->
		                        <button type="button" class="btn btn-danger btn-flat delete_event" data-id="<?php echo $row['id_categoria'] ?>">
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
	_conf("Está seguro de eliminar esta categoría?","eliminar_categoria",[$(this).attr('data-id')])
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
					alert_toast("Se actualizó la categoría correctamente.",'success')
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
	function eliminar_categoria($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=eliminar_categoria',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Categoría eliminada correctamente",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>