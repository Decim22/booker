<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Booker</title>
	<link rel="stylesheet" type="text/css" href="css/app.css">
	<meta name="csrf-token" content="{{csrf_token()}}">
</head>
<body>
	@include('layout.nav')
	<div id="app">
		<note></note>
	</div>
	<script type="text/javascript" src="js/app.js"></script>
</body>
</html>