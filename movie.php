<!doctype html>


<?php
// Get current movie title int the html title.
$page = isset($_GET['menu'])?$_GET['menu']:'movie';
$page = isset($_GET['menu'])?$_GET['menu']:'movie1';
$page = isset($_GET['menu'])?$_GET['menu']:'movie2';
$page = isset($_GET['menu'])?$_GET['menu']:'movie3';
$page = isset($_GET['menu'])?$_GET['menu']:'movie4';

switch($page){
	case 'movie':
		$movie = $_GET["film"];
		$title = 'Mortal Kombat';
        $content = 'movie.php?film=mortalkombat';
		break;
	case 'movie1':
		$movie = $_GET["film"];
		$title = 'Princess Bride';
		$content = 'movie.php?film=princessbride';
		break;
	case 'movie2':
		$movie = $_GET["film"];
		$title = 'Teenage Mutant Ninja Turtles';
		$content = 'movie.php?film=tmnt';
		break;
	case 'movie3':
		$movie = $_GET["film"];
		$title = 'Teenage Mutant Ninja Turtles 2';
		$content = 'movie.php?film=tmnt2';
		break;
	case 'movie4':
		$movie = $_GET["film"];
		$title = 'Joker';
		$content = 'movie.php?film=joker';
		break;
}
// The following html could be in a separate file and included, eg layout.php.
?>

<html>
	
	<head>
		<title>Rancid Tomatoes - <?php echo $title;?></title>
		<meta charset="utf-8" />
		<link href="css/movie.css" type="text/css" rel="stylesheet" />
		<link rel="icon" type="image/gif" href="img/movie/rotten.gif" />
	</head>
	
	<?php
		// Initialize vars.
		
		// Get the film name.
		$movie = $_GET["film"];

		// Get the movie info.
		$raw_info = file_get_contents("$movie/info.txt");

		// Movie overview.
		$raw_overview = file_get_contents("$movie/overview.txt");

		// Info.
		$info = explode("\n", $raw_info);

		// Overview.
		$overview = explode("\n", $raw_overview);

		// Count of reviews.
		$count = 0;

		// Freshness.
		$freshness = "";
		$freshAlt = "";
	
		if (intval($info[2]) >= 60)
		{
				$freshness = "img/movie/freshbig.png";
				$freshAlt = "Fresh";
			}
			else
			{
				$freshness = "img/movie/rottenbig.png";
				$freshAlt = "Rotten";
			}
		
		// Display overview slider.
		function sidebar()
		{
			global $overview;
			foreach($overview as $row)
			{
				$row = explode(":", $row);
				echo "<dt>{$row[0]}</dt><dd>{$row[1]}</dd>";
			}
		}
		
		// Get review information.
		function getReviews()
		{
			global $movie;
			global $count;
			$raw_review = array();
			foreach (glob("$movie/review*.txt") as $filename)
			{
				$raw_review[$count] = file_get_contents("$filename");
				$count++;
			}
			for ($i=0;$i<$count;$i++)
			{
				$review = explode("\n", $raw_review[$i]);
				displayReview($review,$i);
			}
		}
		
		// Display review information.
		function displayReview($review,$num)
		{
			global $count;
			$num++;
			$review[1] = strtolower($review[1]);
			echo "<p class='review'>
						<img src='img/movie/{$review[1]}.gif' alt='{$review[1]}' />
						<q>{$review[0]}</q>
				 </p>
				 <p class='reviewer'>
				 		<img src='img/movie/critic.gif' alt='Critic' />
				 		{$review[2]}<br />
				 		{$review[3]}
				 </p>";
			if($num == ceil($count/2)){
			echo "</div>
				  <div class='column'>";
			}
		}			
	?>

	<body>
		<div class="banner">
			<img src="img/movie/banner.png" alt="Rancid Tomatoes" />
		</div>

		<h1 id="title"><?echo "$info[0] ($info[1])";?></h1>
		
		<div id="overall"> <!-- Main section. -->

			<div id="right"> <!-- Start of right section. -->
				<div>
					<img id="overview_img" src="<?=$movie?>/overview.png" alt="general overview" />
				</div>

				<dl>
					<?php sidebar();?>
				</dl>

			</div> <!-- End of right section. -->

			<div id="left"> <!-- Start of left section. -->

				<div id="score">
					<img id="rotten" src="<?=$freshness?>" alt="<?=$freshAlt?>" />
					<?=$info[2]?>%
				</div>
				
				<div class="column"> <!-- Start of reviews. -->
					<?php getReviews();?>				
				</div> <!-- End of reviews -->

				<div id="aftercolumns"></div>

			</div> <!-- End of left section. -->

				<p id="footer">(1- <?=$count?>) of <?=$count?></p>
		</div> <!-- End of main section. -->
		
		<div id="validate">
			<p><a href="https://webster.cs.washington.edu/validate-html.php">
			<img src="img/movie/w3c-html.png" alt="HTML Validator" /></a><br />

			<a href="https://webster.cs.washington.edu/validate-css.php">
			<img src="img/movie/w3c-css.png" alt="CSS Validator" /></a></p>
		</div>
	</body>
</html>