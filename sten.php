<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<title>CDAC CTF</title>
	<link rel="icon" href="/images/flag.png">
    <link rel="stylesheet" type="text/css" media="screen" href="/css/style_Score.css" />

</head>



<?php
include "./config.php";
?>

<body>
<canvas></canvas>
<p></p>
<div  class="center">
<h1 style='font-family:monospace;font-size: 2.8em;'> SCOREBOARD </h1>
</div>
<p></p>
<div class="dash-challenge-container" >
	<div class="leaderboard">
		<table>
			<thead>
				<tr class="heading">
					<th>Rank</th>
					<th>Name</th>
					<th>Solved</th>
					<th>Score</th>
					<th>Last Submit</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$sql = "select @a:=@a+1 as rank, (select ts from scoreboard where user_id=sb.user_id order by ts DESC LIMIT 1) as ti, u.name as name, u.status as status, count(sb.c_id) as solved, sum(ch.score) as sscore from (SELECT @a:= 0) AS a, users as u, challenges as ch, scoreboard as sb where sb.c_id = ch.id and sb.user_id = u.id group by sb.user_id order by sscore desc, rank asc;";
					$result = mysqli_query($conn, $sql) or die(mysqli_error());
					$count = mysqli_num_rows($result);
					$i = 1;
					if ($count > 0) {
						while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
							echo "<tr>";
							// echo "<td>".$row["name"]."</td>";
							if ($row["status"] == 'false'){
								echo "<td><s>".$i++."</s></td>";
								echo "<td><s>".$row["name"]." <small style='background-color:tomato;'>[blocked]</small></s></td>";
								echo "<td><s>".$row["solved"]."</s></td>";
								echo "<td><s>".$row["sscore"]."</s></td>";
								echo "<td><s>".$row["ti"]."</s></td>";
							}else{
								echo "<td>".$i++."</td>";
								echo "<td>".$row["name"]."</td>";
								echo "<td>".$row["solved"]."</td>";
								echo "<td>".$row["sscore"]."</td>";
								echo "<td>".$row["ti"]."</td>";
							}
							echo "</tr>";
						}
					}
				?>
				
			</tbody>
		</table>
	</div>
	<?php
$date = date_default_timezone_set('Asia/Kolkata');
$today = date("F j, Y, g:i a");
// ,(time() - 60 * 2));
?>

<div class="last-update" style='text-align: center; padding-top:20px;'>Last Update at <span style="font-weight: bold;"> <?php echo $today ?> </span></div>

</div>


<?php
    $url1= '/sten.php';
    // $_SERVER['REQUEST_URI'];
    header("Refresh: 60; URL=$url1");
?>

<script>

// Initialising the canvas
var canvas = document.querySelector('canvas'),
    ctx = canvas.getContext('2d');

// Setting the width and height of the canvas
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// Setting up the letters
var letters = 'ABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZ0123456789012345678901234567890123456789';
letters = letters.split('');

// Setting up the columns
var fontSize = 10,
    columns = canvas.width / fontSize;

// Setting up the drops
var drops = [];
for (var i = 0; i < columns; i++) {
  drops[i] = 1;
}

// Setting up the draw function
function draw() {
  ctx.fillStyle = 'rgba(0, 0, 0, .1)';
  ctx.fillRect(0, 0, canvas.width, canvas.height);
  for (var i = 0; i < drops.length; i++) {
    var text = letters[Math.floor(Math.random() * letters.length)];
    ctx.fillStyle = '#0f0';
    ctx.fillText(text, i * fontSize, drops[i] * fontSize);
    drops[i]++;
    if (drops[i] * fontSize > canvas.height && Math.random() > .95) {
      drops[i] = 0;
    }
  }
}

// Loop the animation
setInterval(draw, 33);



window.onload = function() {
	var ctx = document.getElementById('canvas').getContext('2d');
	window.myLine = new Chart(ctx, config);
};

document.getElementById('randomizeData').addEventListener('click', function() {
	config.data.datasets.forEach(function(dataset) {
		dataset.data = dataset.data.map(function() {
			return randomScalingFactor();
		});

	});

	window.myLine.update();
});

</script>

</body>
</html>
