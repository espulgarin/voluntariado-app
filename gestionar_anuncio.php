<?php include 'admin/db_connect.php' ?>
<?php 
$event = $conn->query("SELECT * FROM events where md5(id) = '{$_GET['c']}'")->fetch_array();
foreach($event as $k => $v){
	$$k = $v;
}

?>
 <div class="content-header">
      <div class="container-md">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo ucwords($event) ?></h1>
          </div><!-- /.col -->

        </div><!-- /.row -->
            <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>

<div class="col-lg-12">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="card card-outline card-primary">
					<div class="card-header">
						<div class="card-tools d-flex justify-content-end" style="width: calc(40%)">
							<a class="btn btn-block btn-sm btn-default btn-flat border-primary col-sm-4 mr-2" href="javascript:void(0)" onclick="location.reload()"><i class="fa fa-redo"></i> Actualizar lista</a>
							<?php if($status != 2): ?>
							<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_attendee m-0 col-sm-4" href="javascript:void(0)"><i class="fa fa-plus"></i> Nuevo voluntario</a>
							<?php endif; ?>
						</div>
					</div>	
					<div class="card-body">
						<?php if($status == 2): ?>
							<div class="alert alert-info"><i class="fa fa-info-circle"></i> El registro e inicio del anuncio está en estado cerrado y por tanto no es posible añadir nuevos voluntarios. </div>
						<?php endif; ?>
						<table class="table table-bordered" id="lista">
							<thead>
								<tr>
									<th>#</th>
									<th>Nombres y apellidos</th>
									<th>Programa</th>
									<th>Correo</th>
									<th>Asistencia</th>
									<th>Acciones</th>
								</tr>
							</thead>
							<tbody>
							<?php
							$i = 1;
							$qry = $conn->query("SELECT a.*,concat(a.firstname,' ',a.lastname) as name,e.event FROM attendees a inner join events e on e.id = a.event_id where e.id = $id order by unix_timestamp(e.date_created) desc ");
							while($row= $qry->fetch_assoc()):
							?>
							<tr>
								<th class="text-center"><?php echo $i++ ?></th>
								<td><b><?php echo ucwords($row['name']) ?></b></td>
								<td><b><?php echo ucwords($row['middlename']) ?></b></td>
								<td><b><?php echo $row['email'] ?></b></td>
								<td class="text-center">
									<input type="checkbox" name="status_chk" id="" data-bootstrap-switch data-toggle="toggle" data-on="Asiste" data-off="Pendiente" class="switch-toggle status_chk" data-size="xs" data-onstyle="success" data-offstyle="danger" data-width="5rem" data-id='<?php echo $row['id'] ?>' <?php echo $row['status'] == '1' ? 'checked' : '' ?>>
								</td>
								<td class="text-center">
				                    <div class="btn-group">
										<?php if($status != 2): ?>
				                        <button href="button" class="btn btn-primary btn-flat edit_attendee" data-id="<?php echo $row['id'] ?>"> 
				                          <i class="fas fa-edit"></i>
				                        </button>
										<?php endif; ?>
				                         <button href="button" class="btn btn-info btn-flat view_attendee" data-id="<?php echo $row['id'] ?>">
				                          <i class="fas fa-eye"></i>
				                        </button>
										<?php if($status != 2): ?>
				                        <button type="button" class="btn btn-danger btn-flat delete_attendee" data-id="<?php echo $row['id'] ?>">
				                          <i class="fas fa-trash"></i>
				                        </button>
										<?php endif; ?>
			                      </div>
								</td>
							</tr>	
						<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#lista').dataTable
		(
			{
				"language": {
					"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
			}}
		)
		$('.view_attendee').click(function(){
			uni_modal("Detalles del voluntario","ver_voluntario.php?id="+$(this).attr('data-id'))
		})
		$('.new_attendee').click(function(){
			uni_modal("Nuevo voluntario","manage_attendee.php?event_id=<?php echo $id ?>","mid-large")
		})
		$('.edit_attendee').click(function(){
			uni_modal("Editar detalles del voluntario","manage_attendee.php?id="+$(this).attr('data-id')+"&event_id=<?php echo $id ?>","mid-large")
		})
		$('.delete_attendee').click(function(){
		_conf("Está seguro de eliminar el voluntario?","delete_attendee",[$(this).attr('data-id')])
		})

		$('.status_chk').change(function(){
			var status = $(this).prop('checked') == true ? 1 : 2;
			if($(this).attr('data-state-stats') !== undefined && $(this).attr('data-state-stats') == 'error'){
				$(this).removeAttr('data-state-stats')
				return false;
			}
			var _this = $(this)
			// return false;
			var id = $(this).attr('data-id');
			start_load()
			$.ajax({
				url:'admin/ajax.php?action=update_attendee_stats',
				method:'POST',
				data:{id:id,status:status},
				error:function(err){
					console.log(err)
					alert_toast("Se produjo un error al actualizar el estado del voluntario",'error')
						_this.attr('data-state-stats','error').bootstrapToggle('toggle')
						end_load()
				},
				success:function(resp){
					if(resp == 1){
						alert_toast("Estado del voluntario actualizado exitosamente.",'success')
						end_load()
					}else if(resp == 2){
						alert_toast("El registro y asistencia al anuncio está cerrado.",'error')
						_this.attr('data-state-stats','error').bootstrapToggle('toggle')
						setTimeout(function(){
							location.reload()
						},2000)
					}else{
						alert_toast("Se produjo un error al actualizar el estado del voluntario.",'error')
						_this.attr('data-state-stats','error').bootstrapToggle('toggle')
						end_load()
					}
				}
				
			})
		})
		$('table').dataTable()
		
	})
		function delete_attendee($id){
		start_load()
		$.ajax({
			url:'admin/ajax.php?action=delete_attendee',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Voluntario eliminado correctamente.",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>