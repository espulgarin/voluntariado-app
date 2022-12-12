<?php if(!isset($conn)){ include 'db_connect.php'; } 
/* $sql = "SELECT venue, c.nombre, c.id_categoria
FROM events e
INNER JOIN categorias c
ON e.venue = c.id_categoria
order by unix_timestamp(date_created) desc "; */
$sql = "SELECT * FROM CATEGORIAS;";
$qry = $conn->query($sql);

	?>

<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-event">

        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Título</label>
						<input type="text" class="form-control form-control-sm" name="event" value="<?php echo isset($event) ? $event : '' ?>">
					</div>
				</div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Fecha de inicio</label>
              <input type="text" class="form-control form-control-sm datetimepicker" autocomplete="off" name="event_datetime" value="<?php echo isset($event_datetime) ? date("Y/m/d H:i",strtotime($event_datetime)) : '' ?>">
            </div>
          </div>
				</div>
        <div class="row">
<!-- 		<div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Categoría</label>
              <input type="text" class="form-control form-control-sm" name="categoria" value="<?php //echo isset($venue) ? $venue : '' ?>">
            </div>
          </div> -->

           <div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Categoría</label>
              <select id="cat" class="form-control form-control-sm" name="venue">
				
					<?php 
					 while($row= $qry->fetch_assoc())
					 { 
						$a = ($venue==$row['id_categoria']) ? ("selected") : '';
					  	echo "<option value=" . $row['id_categoria'] ." $a>" . $row['nombre'] . "</option>";
					 }
					?>
				</select>																			
            </div>
          </div>
		
		  </div>


				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label for="" class="control-label">Descripción</label>
							<textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
								<?php echo isset($description) ? $description : '' ?>
							</textarea>
						</div>
					</div>
				</div>
        </form>
    	</div>
    	<div class="card-footer border-top border-info">
    		<div class="d-flex w-100 justify-content-center align-items-center">
    			<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-event">Guardar</button>
    			<button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=mostrar_anuncios'">Cancelar</button>
    		</div>
    	</div>
	</div>
</div>
<script>
	$('#manage-event').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_event',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				/* alert(resp);
				return resp; */ 
				if(resp == 1)
				{
					alert_toast('Anuncio guardado correctamente',"success");
					setTimeout(function(){
						location.href = 'index.php?page=mostrar_anuncios'
					},2000)
				}
				else{
					alert_toast('No se pudo guardar el anuncio',"error");
					setTimeout(function(){
						location.href = 'index.php?page=nuevo_anuncio'
					},2000)
				}
			}
		})
	})
</script>