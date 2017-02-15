<?php

	require_once('connection/connect.php');
	require_once('includes/header.php');
	include('includes/menu.php');
	include('includes/skeleton.php');
?>
	<!-- main page content -->
	<div class="container">
  	<div class="tab-content">
       <div class="tab-pane active fade in" id="search">
	     <div class="row">
					  <div class="col-sm-6">
					     <div class="panel panel-default">
						    <div class="panel-body">
								<form action="#">
										<div class="form-group"><input style="width: 80%; border-radius:0" type="search" class="form-control"></div>
										<div class="form-group">
											<button class="btn btn-default" type="submit">Search String</button> 
											<button class="btn btn-default" type="reset">Reset</button>
										</div>
							 
						   		</form>
							</div>
						 </div>
					  </div>
					  <div class="col-sm-6">
					    <div class="panel panel-default">
							<div class="panel-body"> 
								<div class="help">
									 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo molestias temporibus quas excepturi modi at velit harum veniam perferendis eveniet voluptatem reprehenderit explicabo placeat, esse! Deserunt sint eveniet esse modi.</div>
								</div>
						</div>
					  </div>
				  </div>
	  </div>
	  <div class="tab-pane fade" id="frequency">
	     Mara ukitafuta, matokeo yataonekana hapa.
	  </div>
	  <div class="tab-pane fade" id="context">
		  Mara ukitafuta, matokeo yataonekana hapa.
	  </div>
	 <!--The login page-->
	  <div class="tab-pane fade" id="login">
	     <form class="form-horizontal" action="includes/login.php" method="POST" id="login" name="Login_Form">
		 	<div class="form-group">
			 	<label class="control-label col-sm-2" for="username">Username</label>
				 <div class="col-sm-4">
				 	<input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
				 </div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2" for="password">Password</label>
				<div class="col-sm-4">
					<input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-6">
					<input type="submit" class="btn btn-default" id="Login" name="Login" value="Login">
				</div>
			</div>
		 </form>
	  </div>
			   
  </div>


</div>
</div>
	
<?php
	require_once('includes/footer.php');
?>
