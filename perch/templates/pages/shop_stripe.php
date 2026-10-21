<?php
	$hide = perch_page_attribute('hide_search',array(),true);
	if($hide=='yes'){
		header("location:/404");
	}
	
	$section = perch_get('section');
?>
<?php perch_layout('header'); ?>

<?php 
	if(!$section){
		perch_content('Hero'); 
	}
?>

<main class="flow">
	<?php 
		if(!$section){
		perch_content('Content'); 
		}elseif($section=='cjsweekly'){
		perch_content('CJS Weekly'); 
		?>
		<p>&nbsp;</p>
		<form method="post" action="/stripe-session.php">
			<div class="input">
				<label>Ready?</label>
				<input type="submit" value="Subscribe" class="button" />
				<input type="hidden" name="type" value="cjsweekly" />
			</div>
		</form>
		<?php
		}elseif($section=='cjsweekly-group'){
		perch_content('CJS Weekly Group'); 
		?>
		<p>&nbsp;</p>
		<form method="post" action="/stripe-session.php">
			<div class="input">
				<label>Ready?</label>
				<input type="submit" value="Subscribe" class="button" />
				<input type="hidden" name="type" value="cjsweekly-group" />
			</div>
		</form>
		<?php
		}elseif($section=='invoice'){
		perch_content('Invoice'); 
		?>
		<form method="post" action="/stripe-session.php">
			<div class="input">
				<label>Ready?</label>
				<input type="submit" value="Pay Invoice" class="button" />
				<input type="hidden" name="type" value="invoice" />
			</div>
		</form>
		<?php
		}elseif($section=='adverts'){
		perch_content('Adverts'); 
		?>
		<form method="post" action="/stripe-session.php">
			<div class="input">
				<label>Ready?</label>
				<input type="submit" value="Pay for Advert" class="button" />
				<input type="hidden" name="type" value="advert" />
			</div>
		</form>
		<?php
		}elseif($section=='success'){
		perch_content('Success'); 
		}
	?>
</main>

<?php perch_layout('footer'); ?>