<!DOCTYPE html>
<html lang="en">
<?php include 'header.php' ?>
<body class="hold-transition register-page">
<div class=" col-lg-7">
  <div class="register-logo">
    <a href="#"><b>Sistema de registro de voluntariado</b></a>
  </div>
<?php session_start() ?>
<?php include('admin/db_connect.php'); ?>
<?php 
if(isset($_SESSION['login_id'])){
	$qry = $conn->query("SELECT * from users where id = {$_SESSION['login_id']} ");
	foreach($qry->fetch_array() as $k => $v){
		$$k = $v;
	}
}
?>
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg"><?php echo !isset($id) ? 'Crear cuenta' : 'Gestionar cuenta'; ?></p>
      <form id="manage-signup">
      	<input type="hidden" value="<?php echo isset($id) ? $id : '' ?>" name="id">
	      <div class="col-md-12">
	      	<div class="row">
	      		<div class="col-md-6 border-right">
      				<div class="input-group mb-3">
			          <input type="text" class="form-control" name="firstname" required placeholder="Nombres" value="<?php echo isset($firstname) ? $firstname : '' ?>">
			          <div class="input-group-append">
			            <div class="input-group-text">
			              <span class="fas fa-user"></span>
			            </div>
			          </div>
			        </div>
			        <div class="input-group mb-3">
			          <input type="text" class="form-control" name="lastname" required placeholder="Apellidos" value="<?php echo isset($lastname) ? $lastname : '' ?>">
			          <div class="input-group-append">
			            <div class="input-group-text">
			              <span class="fas fa-user"></span>
			            </div>
			          </div>
			        </div>
			        <div class="input-group mb-3">
			          <input type="text" class="form-control" name="contact" required placeholder="Número de contacto" value="<?php echo isset($contact) ? $contact : '' ?>">
			          <div class="input-group-append">
			            <div class="input-group-text">
			              <span class="fas fa-mobile"></span>
			            </div>
			          </div>
			        </div>
					<div class="input-group mb-3">
			          <input type="text" class="form-control" name="middlename"  placeholder="Programa académico" value="<?php echo isset($middlename) ? $middlename : '' ?>">
			          <div class="input-group-append">
			            <div class="input-group-text">
			              <span class="fas fa-user-graduate"></span>
			            </div>
			          </div>
			        </div>
			        <div class="mb-3">
			          <textarea cols="30" rows="3" class="form-control" name="address" required placeholder="Dirección"><?php echo isset($address) ? $address : '' ?></textarea>
			        </div>
	      		</div>
	      		<div class="col-md-6">
	      			<div class="input-group mb-3">
			          <input type="email" class="form-control" name="email" required="" placeholder="Correo" value="<?php echo isset($email) ? $email : '' ?>">
			          <div class="input-group-append">
			            <div class="input-group-text">
			              <span class="fas fa-envelope"></span>
			            </div>
			          </div>
			        </div>
			        <small id="msg"></small>
			        <div class="input-group mb-3">
			          <input type="password" class="form-control" name="password" <?php echo isset($id) ? '' : "required" ?> placeholder="Contraseña">
			          <div class="input-group-append">
			            <div class="input-group-text">
			              <span class="fas fa-lock"></span>
			            </div>
			          </div>
			        </div>
			        <?php if(isset($id)): ?>
						<small><i>Dejar este campo en blanco para no cambiar la contraseña.</i></small>
					<?php endif; ?>
			        <div class="input-group mb-3">
			          <input type="password" class="form-control" name="cpass" <?php echo isset($id) ? '' : "required" ?> placeholder="Repetir contraseña">
			          <div class="input-group-append">
			            <div class="input-group-text">
			              <span class="fas fa-lock"></span>
			            </div>
			          </div>
			        </div>
					<small id="pass_match" data-status=''></small>

	      		</div>
	      	</div>
	      </div>
        <div class="row">
          <div class="col-8">
	        <?php if(!isset($id)): ?>

            <!-- <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div> -->
			<?php endif; ?>

          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block"><?php echo !isset($id) ? 'Regristrar' : 'Actualizar cuenta'; ?></button>
          </div>
          <!-- /.col -->
        </div>
      </form>

	        <?php if(!isset($id)): ?>
      <a href="login.php" class="text-center">Ya tengo una cuenta</a>
			<?php endif; ?>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->
<script>
	$(document).ready(function(){
		
	$('#manage-signup').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		if($('#pass_match').attr('data-status') != 1){
			if($("[name='password']").val() !=''){
				$('[name="password"],[name="cpass"]').addClass("border-danger")
				end_load()
				return false;
			}
		}
		$.ajax({
			url:'admin/ajax.php?action=signup',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Información almacenada correctamente.',"success");
					setTimeout(function(){
						location.replace('index.php?page=home')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>El correo ya se encuentra registrado.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
		$('[name="password"],[name="cpass"]').keyup(function(){
			var pass = $('[name="password"]').val()
			var cpass = $('[name="cpass"]').val()
			if(cpass == '' ||pass == ''){
				$('#pass_match').attr('data-status','')
			}else{
				if(cpass == pass){
					$('#pass_match').attr('data-status','1').html('<i class="text-success">Las contraseñas coincides.</i>')
				}else{
					$('#pass_match').attr('data-status','2').html('<i class="text-danger">Las contraseñas no coinciden.</i>')
				}
			}
		})
	})

</script>
<?php include 'footer.php' ?>

</body>
</html>
