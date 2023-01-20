<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="generator" content="Hugo 0.83.1">
		<title>BinCom PHP Test | Home</title>
		
		<link href="css/bootstrap.min.css" type="text/css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/all.min.css" />
		<style>
			bd-placeholder-img {
				font-size: 1.125rem;
				text-anchor: middle;
				-webkit-user-select: none; 
				-moz-user-select: none;
				user-select: none;
			}

			@media (min-width: 768px) {
				.bd-placeholder-img-lg { font-size: 3.5rem; }
			}
			
			@media screen and (max-width: 768px) {
				.col-sm-4 {
					text-align: center;
					margin: 25px 0;
				}
			}
		</style>
		<link href="css/main.css" type="text/css" rel="stylesheet">
	</head>
	<body>
		<header>
			<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-blue" id="navbar">
				<div class="container">
					<a class="navbar-brand" style="font-weight:700;font-size:1.8em;" href="#">BinCom PHP Test</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse navbar-center" id="navbarCollapse">
						<ul class="navbar-nav nav-pills me-auto mb-2 mb-md-0">
							<li class="nav-item" id="options_wards"><a class="nav-link text-blue" role='button' onclick="to_wards()">Wards</a></li>
							<li class="nav-item" id='options_lgas'><a class="nav-link" role='button' onclick="to_lgas()">LGAs</a></li>
							<li class="nav-item" id="options_create"><a class="nav-link" role='button' onclick="to_create()">Create</a></li>
						</ul>
					</div>
				</div>
			</nav>
		</header>
		<main>
			<section class="image image-responsive bg-blue" style="padding-top:100px; padding-bottom:40px;" id="home">
				<div class="jumbotron text-white text-center">
					<h1 style="font-weight:900;font-size:2.4em;">BinCom PHP test</h1>
					<p>Manage and Monitor the voting proceedings</p>
				</div>
			</section>

			<section id="results" class='px-3'></section>
			<section id="lga_results" style="display:none;">
				<div class="container-fluid row">
					<select id='state' class="form-select col-lg-3" aria-label="Disabled select example" style='width:240px;'></select>
					<select id='lga' class="form-select col-lg-3 mx-2" aria-label="Disabled select example" style='width:240px;' disabled>"<option selected>Select Local Government</option></select>
					<div class='col-lg-4'></div>
					<div class='mx-4' id="total"></div>
					<div class="row pt-3 mx-4">
						<div class="col-lg-9 col-md-7"></div>
						<div class="col-lg-3 col-md-5 my-4">
							<canvas id="pie"></canvas>
						</div>
					</div>
				</div>
			</section>
			<section id='create' style="margin-left: calc( 50% - 360px);" style="display:none;">
				<div class="container-fluid row" style="width: 720px;">
					<select id='create_state' class="form-select col-md-4 m-2" aria-label="Disabled select example" style="width:200px;"></select>
					<select id='create_lga' class="form-select col-md-4 m-2" aria-label="Disabled select example" style="width:200px;">"<option selected>Select Local Government</option></select>
					<select id='create_ward' class="form-select col-md-4 m-2" aria-label="Disabled select example" style="width:200px;">"<option selected>Select Ward</option></select>
					<form>
						<div class="form-group">
						    <label for="first-create">First Nmae</label>
						    <input class="form-control" id="first-create" placeholder="First Name"/>
						</div>
						<div class="form-group mt-2">
						    <label for="last-create">Last Nmae</label>
						    <input class="form-control" id="last-create" placeholder="Last Name"/>
						</div>
						<div class="form-group mt-2">
						    <label for="polling-create">Polling Unit</label>
						    <input class="form-control" id="polling-create" aria-describedby="pollingHelp" placeholder="Polling Unit"/>
						    <small id="pollingHelp" class="form-text text-muted">Name of the polling unit, this is case sensitive, this should be spelled correctly in case it already exist</small>
						</div>
						
						<div class='row p-2'>
							<select id='create_party' class="form-select col-lg-3" style="width:200px;"></select>
							<button type="button" class="btn btn-primary mt-2">+</button>
						</div>
						<button type="button" class="btn btn-primary">Submit</button>
					</form>
				</div>
			</section>
			
			<section id="contacts">
				<div class="container-fluid bg-grey">
					<div class="row">
						<div class="col-md-6">
							<table style="width:100%">
								<tr>
									<th class="text-blue">Quick Links</th>
								</tr>
								<tr>
									<td><a href="#">Latest Event</a></td>
									<td><a href="#">Terms and Conditions</a></td>
								</tr>
								<tr>
									<td><a href="#">Privacy policy</a></td>
									<td><a href="#">Contact us</a></td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table style="width:100%">
								<tr>
									<th class="text-blue">Contacts</th>
								</tr>
								<tr>
									<td>Bsoftlimited@gmail.com</td>
									<td><a href="#">Github.com/bsoftlimited</a></td>
								</tr>
								<tr>
									<td>Back of Amarata Yenagoa Bayelsa State</td>
									<td>+234 7087952034</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<hr>
							<p class="footer-company-name" style="text-align:center;">All Rights Reserved. &copy; 2023 <a href="#">Bsoft Limited</a> Design By : <a href="https://nobel-porfolio.netlify.app/">Okelekele Nobel Bobby</a></p>
						</div>
					</div>
				</div>
			</section>
		</main>
		<script src="js/all.min.js"></script>
		<script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="js/chart.js"></script>
		<script src="js/ajax.js"></script>
		<script src="js/main.js"></script>
		<script>

			let lga_results = document.getElementById("lga_results");
			let create = document.getElementById("create");

			let options_wards = document.getElementById("options_wards");
			let options_create = document.getElementById("options_create");
			let options_lgas = document.getElementById("options_lgas");

			function clear(){
				options_wards.classList.remove("active");
				options_create.classList.remove("active");
				options_lgas.classList.remove("active");
			}

			initResults(results);
			initTotals();
			initSignup();

			let current = 'results';
			function to_create(){
				results.style.display = 'none';
				lga_results.style.display = 'none';
				create.style.display = 'inline';

				clear();
				options_create.classList.add("active");
			}

			function to_lgas(){
				results.style.display = 'none';
				lga_results.style.display = 'block';
				create.style.display = 'none';

				clear();
				options_lgas.classList.add("active");
			}

			function to_wards(){
				results.style.display = 'block';
				lga_results.style.display = 'none';
				create.style.display = 'none';

				clear();
				options_wards.classList.add("active");
			}

			to_wards();
		</script>
	</body>
</hmtl>