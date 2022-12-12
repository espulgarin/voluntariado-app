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

        <input type="hidden" name="id_categoria" value="<?php echo isset($id_categoria) ? $id_categoria : '' ?>">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="" class="control-label">Nombre de la categoría</label>
						<input type="text" class="form-control form-control-sm" name="nombre" value="<?php echo isset($nombre) ? $nombre : '' ?>">
					</div>
				</div>
          
<!--        <div class="row">
 		<div class="col-md-6">
            <div class="form-group">
              <label for="" class="control-label">Categoría</label>
              <input type="text" class="form-control form-control-sm" name="categoria" value="<?php //echo isset($venue) ? $venue : '' ?>">
            </div>
          </div> 

        
		  </div>-->


				<div class="row">
					<div class="col-md-10">
						<div class="form-group">
							<label for="" class="control-label">Descripción</label>
							<textarea name="descripcion" id="" cols="30" rows="10" class="summernote form-control">
								<?php echo isset($descripcion) ? $descripcion : '' ?>
							</textarea>
						</div>
					</div>
				</div>
        </form>
    	</div>
    	<div class="card-footer border-top border-info">
    		<div class="d-flex w-100 justify-content-center align-items-center">
    			<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-event">Guardar</button>
    			<button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=mostrar_categorias'">Cancelar</button>
    		</div>
    	</div>
	</div>
</div>
<script>
	$('#manage-event').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=guardar_categoria',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				//alert(resp);
				//return resp; 
				if(resp == 1)
				{
					alert_toast('Categoría guardada correctamente',"success");
					setTimeout(function(){
						location.href = 'index.php?page=mostrar_categorias'
					},2000)
				}
				else{
					alert_toast('No se pudo guardar la categoría',"error");
					setTimeout(function(){
						location.href = 'index.php?page=nueva_categoria'
					},2000)
				}
			}
		})
	})
</script>