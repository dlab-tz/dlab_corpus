<!--
   Copyright 2015 Charles Bundu <rwige1@gmail.com>
   
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.
   
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   
   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
   MA 02110-1301, USA.
   
   Created: March 2015
-->
<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
-->

<!DOCTYPE html>

<html lang="en">

<head>
	<title>dLab | Swahili CORPUS</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Tanzania Data Lab | Swahili Corpus," />
	<meta name="author" content="Charles John Bundu" />
	<link href="images/logo.png" type="image/x-icon" rel="icon" />
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" media="screen" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css" media="screen,projection" />
</head>
<body>
	<div id="wrap">
		<div class="content">
			<main class="main-content" role="main">
				<section class="main-section">
					<div class="container">
						<div class="row banner" >
							<div class="col-sm-6"><img src='images/logo.png' alt="logo" height="50" title="logo" /></div>
							<div class="col-sm-1 col-sm-offset-5"><a href="#"><i class="glyphicon glyphicon-question-sign"></i></a></div>
						</div>
					</div>
					
					<div class="container">
						<div class="row">
							<ul class="nav nav-tabs btn-group nav-justified btn-default tabs">
								<li class="active"><a data-toggle="tab" href="#search" class="btn btn-large">Search</a></li>
								<li><a data-toggle="tab" href="#frequency" class="btn btn-large">Frequency</a></li>
								<li><a data-toggle="tab" href="#context" class="btn btn-large">Context</a></li>
								<!--<li><a data-toggle="tab" href="#login" class="btn btn-large">Login</a></li>-->
								<?php echo $login_variable;?>
							</ul>
						</div>
					</div>