<html>
<head>
	<title>Talk2Me</title>
	<link rel='stylesheet' href='style.css?date=<?= date() ?>'>
	<script src='script.js'></script>
	<link href="https://fonts.googleapis.com/css?family=Caveat+Brush" rel="stylesheet">
	<script src='https://code.responsivevoice.org/responsivevoice.js'></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class='speak'>
	<div style='position:absolute; top:0; z-index:99; text-align:center;'><span class='header-main'>Talk2Me</span> <span class='header-sub'>v1</span> <br><span class='header-sub'>by StevenMashima</span></div>
	<div class='speak-btn' onclick='startvoice(this)' data-lang='ja-JP' data-voice='Japanese Female' id='secondary'>
		<i class="material-icons" style='color:black;'>mic</i>
	</div>
	Speak Japanese
	<div class='result'></div>
</div>
<div class='speak'>
	<div class='speak-btn' onclick='startvoice(this)' data-lang='en-US' data-voice='US English Female' id='primary'>
		<i class="material-icons" style='color:black;'>mic</i>
	</div>
	Speak English
	<div class='result'></div>
</div>
</body>
</html>
