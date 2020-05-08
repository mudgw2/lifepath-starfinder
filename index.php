<!DOCTYPE html>
<html class="no-js" lang="en">
<?php
if (session_status()==1) {
	session_start();
}
	include "../header.php";
	include "functions.php";

	$level 	= 1;
	$age 	= 0;
?>
<body><div id="particles-js"></div>

<div class="container">
<?php include "../nav.php";  ?>
<h1>Lifepath Generator</h1>
 <header class="masthead mb-auto">
	<nav class="navbar navbar-dark col-md-12 justify-content-end">
	
	<form >
	<div class="form-group flex-row" style="margin-bottom:0;">
	
	<div class="input-group flex-nowrap input-group-lg">
		<div class="input-group-prepend">
		<span class="input-group-text">RACE</span>
		</div>
		<div class="input-group-prepend">
		<select class="form-control form-control-lg" id="race_select">
		<?php generate('races');?>
		</select>
		</div>
		<div class="input-group-prepend">
		<button type="submit" class="btn btn-primary">SEED &gt;</button>
		</div>
	<input class="form-control" name="seed" value="<?php if(isset($_GET['seed'])){echo $_GET['seed'];}else{echo time();}?>"/>
	
	<button type="submit" class="btn btn-danger"><a href="." class="text-white">RELOAD NEW SEED</a></button>
	</div>
	  </form>
	</div>
	</nav>
</header>

<script>
$(document).ready(function(){
	$('#race_select').change(function(){
		$.cookie('race', $('#race_select').val());
	});
});
</script>
<?php 
$race_selected = $_COOKIE['race']; 
?>

<div class="row">
	<div class="col-12">
	<div class="card">
	<div class="card-header" data-toggle="collapse" href="#collapseRacial" role="button" aria-expanded="false" aria-controls="collapseRacial">
	<?php echo $race_selected; ?> Racial Information</div>
	<div class="card-body" id="collapseRacial">
	<?php get_race_info($race_selected);?>
</div></div></div></div>
<br/>
<div class="row">
	<div class="col-12">
	<div class="card">
	<div class="card-header">Childhood</div>
	<div class="card-body"><p>
	<?php 
		if($race_selected == 'Android'){
			echo"None.";
		}else{
			echo"You grew up ";
			generate('birth');
			echo ", raised by ";
			generate('caretakers_origin');
			echo ". You come from a line of ";
			echo generate('background');
			echo ", and ";
			generate('environment');
			echo ". ";
			generate('caretakers_status');
			echo ".";
		}
	?></p>
</div>
	</div></div>
</div>
<br/>
<div class="row row-cols-2">
<div class="col-6">

<div class="card">
  <div class="card-header">
    Family
  </div>
  <div class="card-body">
    <p class="card-text"><strong>You 
	<?php siblings($race_selected);?>
	</strong></p>
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
<div class="col-6">

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

</div>
<?php include "../footer.php"; ?>

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