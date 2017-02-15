<?php
	session_start();
	include('connection/session.php');
	require_once('connection/connect.php');
	require_once('includes/header.php');
	include('includes/menu.php');
	include('includes/skeleton.php');
?>
	<!-- main page content -->
	<div class="container">
		<div class="row">
			<div>
						<?php						
							displayRightMenu();
						?>
			</div>
			<div>
					<div class="col-sm-4">
							<?php
							displayLeftMenu();
						?>
					</div>
					<div class="col-sm-8">
						<?php						
							displayContent();
						?>
					</div>
					<!-- left panel -->
			</div>
		</div>
	</div>
<?php
	require_once('includes/footer.php');
?>
