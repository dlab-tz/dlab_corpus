<?php
    require_once('includes/header.php');

?>


<div class="container">
    <h1>User Information</h1>
  	<hr>
	<div class="row">
      <!-- left column -->
      <div class="col-md-3">
        <div class="text-center">
          <img src="//placehold.it/100" class="avatar img-circle" alt="avatar">
          <h6>Upload a different photo...</h6>
          
          <input class="form-control" type="file">
        </div>
      </div>
      
      <!-- edit form column -->
      <div class="col-md-9 personal-info">
        <div class="alert alert-info alert-dismissable">
          <a class="panel-close close" data-dismiss="alert">×</a> 
          <i class="fa fa-coffee"></i>
          This is a <strong>page</strong> showing the information about you.
        </div>
        <h3>Personal info</h3>
        
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label class="col-lg-3 control-label">First name:</label>
            <div class="col-lg-8">
              <input class="form-control" value="" type="text" placeholder="First Name">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Last name:</label>
            <div class="col-lg-8">
              <input class="form-control" value="" type="text" placeholder="Last Name">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Institution:</label>
            <div class="col-lg-8">
              <input class="form-control" value="" type="text">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Email:</label>
            <div class="col-lg-8">
              <input class="form-control" value="" type="text" placeholder="Email">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Category:</label>
            <div class="col-lg-8">
              <div class="ui-select">
                <select id="user_time_zone" class="form-control">
                  <option value="">University professor: languages | linguistics</option>
                  <option value="">Graduate student: languages or linguistics</option>
                  <option value="">University professor: not languages | linguistics</option>
                  <option value="">Teacher: not university; not graduate student</option>
                  <option value="">Creator of language-related blog, or professional translator</option>
                  <option value="" selected="selected">Graduate student: not languages or linguistics</option>
                  <option value="">Student (undergraduate)</option>
                  <option value="">Other</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Username:</label>
            <div class="col-md-8">
              <input class="form-control" value="" type="text" placeholder="username">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Password:</label>
            <div class="col-md-8">
              <input class="form-control" value="" type="password" placeholder="Password">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label">Confirm password:</label>
            <div class="col-md-8">
              <input class="form-control" value="" type="password" placeholder="Password">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-md-8">
              <input class="btn btn-primary" value="Save Changes" type="submit">
              <span></span>
              <input class="btn btn-default" value="Cancel" type="reset">
            </div>
          </div>
        </form>
      </div>
  </div>
</div>
<hr>

<?php
	require_once('includes/footer.php');
?>
