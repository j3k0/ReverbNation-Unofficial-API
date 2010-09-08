<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"  />
<title>ReverbNation Reverse Engineered API</title>
<meta name="robots" content="follow, all" />
<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
<script type='text/javascript' src='jquery.js'></script>
<script type='text/javascript' src='reverbnation.js'></script>
</head>
<body>
<div id="page">
<h1>ReverbNation Reverse Engineered API</h1>
<h2>Status Test</h2>
<p><a href="#" onclick="labelStatus(); return false;">Change Label Status</a></p>
<p><a href="#" onclick="artistStatus(); return false;">Change Artist Status</a></p>
<p><a href="#" onclick="addShow(); return false;">Add a Show</a></p>
<p id="rn-login-result"></p>
<p id="rn-status-result"></p>
<p id="rn-addshow-result"></p>
</div>
<script type='text/javascript'>

function testStatus() {
    var now = new Date();
    rnStatus('Time is ' + now.getHours() + ':' + now.getMinutes());
}

function labelStatus() {
    rnInitLabel(55587);
    rnLogin('user', 'password');
    testStatus();
}

function artistStatus() {
    rnInitArtist(964735);
    rnLogin('user', 'password');
    testStatus();
}

function addShow() {
    rnInitArtist(964735);
    rnLogin('user', 'password');
    rnAddShow('2010-10-04 20:30', 'Sky Bar', 'Beirut', 'LB');
}

</script>
</body>
</html>
