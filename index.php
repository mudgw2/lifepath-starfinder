<!doctype html>
<html class="no-js" lang="en">
<?php
//Clear SESSION scope
	session_start();
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	session_regenerate_id(true);
	include "header.php";
	include "functions.php";

	$level 	= 1;
	$age 	= 0;
?>
<body id="page-top">
<div class="container">

<h1>Lifepath Generator<small class="pull-right">for Starfinder RPG</small></h1>

<nav class="navbar navbar-dark">
  <form class="form-inline">

		<div class="form-group pull-right">
	<label for="RaceSelect1">Your Race&nbsp;
		<select name="race" id="race_select" class="form-control" >
			<option value="Human" selected>Human</option>
			<option value="Android">Android</option>
		</select></label>
</div>


  </form>
</nav>

<?php
$race_selected = "<script>document.write(localStorage.getItem('selected'));</script>";
?>


<p>You grew up
	<?php generate('birth');?>, raised by <?php generate('caretakers_origin');?>.  You come from a line of <?php generate('background');?>, and
	<?php generate('environment');?>.  <?php generate('caretakers_status');?>.
</p>

<div class="row">
<div class="col-md-6">

<div class="card">
  <div class="card-header">
    Family
  </div>
  <div class="card-body">
    <p class="card-text"><strong>You <?php siblings($race_selected);?></strong></p>
  </div>
</div>

<div class="card">
  <div class="card-header">
    Personality
  </div>
  <div class="card-body">
    <p class="card-text">
			You like to wear <?php generate('clothing');?>, and you <?php generate('hair');?>.
			You have <?php generate('mark'); ?>
		</p>
  </div>
</div>

</div>
<div class="col-md-6">

<div class="card">
  <div class="card-header">
    Fate
  </div>
  <div class="card-body">
    <p class="card-text">
			<?php
			fate();
			?>
		</p>
  </div>
</div>


</div>
</div>
<script src="js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>

<script>
	var selected = localStorage.getItem('selected');
	if (selected) {
	  $("#race_select").val(selected);
	}
	$("#race_select").change(function() {
	  localStorage.setItem('selected', $(this).val());
	  location.reload();
	});
</script>
</body>
</html>
