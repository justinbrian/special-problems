<!doctype html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rancid Tomatoes</title>

    <link href="css/movie.css" type="text/css" rel="stylesheet"/>
    <link href="img/movie/rotten.gif" type="image/gif" rel="icon"/>
</head>

<?php
/*
 * Query parameter that allows user to pass the name of the movie from the web browser.
 * Example: movie.php?film=mymovie
 * Available movies: mymovie, fightclub, mortalkombat, princessbride, tmnt, tmnt2
 */
$movie = $_GET["film"];

/*
 * Reads movie info.txt into a an string array
 * $movie is used to define relative path.
 * FILE_IGNORE_NEW_LINES MUST be used in order to omit \n
 * $movieInfo[0] Movie Title
 * $movieInfo[1] Movie Year
 * $movieInfo[2] Movie Score
 */
$movieInfo = file("$movie/info.txt", FILE_IGNORE_NEW_LINES);

/*
 * Reads movie overview.txt into an string array
 * $movie is used to define relative path.
 * $movieOverview[0] Overview Subtitle. Example: STARING, DIRECTOR, PRODUCER ...
 * $movieOverview[1] Overview Contents
 */
$movieOverview = file("$movie/overview.txt", FILE_IGNORE_NEW_LINES);

/*
 * Reads the paths of all files matching the format review*.txt into an string array
 * Example: review1.txt, review2.txt, review3.txt ...
 * $movie is used to define relative path.
 */
$movieReviewPath = glob("$movie/review*.txt");

/*Alternative way of reading contents.
file_get_contents stores the text into a string.
$movieInfo = explode("\n", file_get_contents("$movie/info.txt"));
$movieOverview = explode("\n", file_get_contents("$movie/overview.txt"));*/

?>


<body>
<div id="bodyHeader">
    <img src="img/movie/banner.png" alt="Rancid Tomatoes"/>
</div>

<!--
In PHP ?= used for printing the return value; ($var value)
IN PHP ?php used for calling only; $var;
Implemented functions are void, they only print HTML code, so they must be called only -->

<h1><?= $movieInfo[0] ?> (<?= $movieInfo[1] ?>)</h1>

<!-- each section tag must be specified by the developer -->

<div id="main">
    <div id="overviewSection">
        <div><img src="<?= $movie ?>/overview.png" alt='general overview'/></div>
        <?php printMovieOverviewList($movieOverview); ?>
    </div>

    <div id="reviewSection">
        <?php printMovieReviewScore($movieInfo); ?>
        <?php printMovieReviewsTwoColumns($movieReviewPath); ?>
    </div>

    <p id="reviewCount">(1- <?= count($movieReviewPath); ?>) of <?= count($movieReviewPath); ?></p>
</div>

<div id="w3c">
    <a href="https://validator.w3.org/check/referer">
        <img src="img/w3c-html.png" alt="Valid HTML"/></a>
    <a href="https://jigsaw.w3.org/css-validator/check/referer">
        <img src="img/w3c-css.png" alt="Valid CSS"/></a>
</div>
</body>
</html>


<!--PHP FUNCTIONS-->
<?php
/**Prints the movie overview to the HTML document
 * Appropriate div tags MUST be written by the developer to separate sections.
 * @param array $movieOverviewIn String array containing the movie overview.
 */
function printMovieOverviewList(array $movieOverviewIn)
{
    ?>

    <dl>


        <?php foreach ($movieOverviewIn as $movieOverviewLine) {
            //Splits each movieOverview element into more sub elements.The delimiter is :.
            $movieOverviewLine = explode(":", $movieOverviewLine);

            ?>

            <dt><?= $movieOverviewLine[0] ?></dt>
            <dd><?= $movieOverviewLine[1] ?></dd>

        <?php } ?>
    </dl>
<?php } ?>

<?php
/**Prints the movie review score to the HTML document
 * Appropriate div tags MUST be written by the developer to separate sections.
 * @param array $movieInfoIn String array containing the movie information.
 */
function printMovieReviewScore(array $movieInfoIn)
{
    ?>

    <div id="reviewSectionHeader">

        <?php
        //determines if movie score is greater than 60
        //intval() converts string to int
        if (intval($movieInfoIn[2]) >= 60) {
            ?>

            <img src="img/movie/freshbig.png" alt="Fresh"/>
            <?= $movieInfoIn[2] ?>%

            <?php
        } else {
            ?>
            <img src="img/movie/rottenbig.png" alt="Rotten"/>
            <?= $movieInfoIn[2] ?>%

            <?php
        } ?>
    </div>
    <?php
} ?>

<?php
/**Prints the movie reviews in two columns to the HTML document
 * Appropriate div tags MUST be written by the developer to separate sections.
 * @param array $movieReviewPathIn String array containing the movie directories.
 */
function printMovieReviewsTwoColumns(array $movieReviewPathIn)
{

    //defines the first index value for the array to 0
    $low = 0;

    //defines the last index value for the array to half size of the array + 1
    //ceil() rounds fractions up
    $high = ceil(count($movieReviewPathIn) / 2);

    // creates two columns
    for ($i = 0; $i < 2; $i++) {
        ?>

        <div class='reviewSectionColumn'>


            <?php
            for ($j = $low; $j < $high; $j++) {

                //Splits each review element into sub elements. The delimiter is the new line
                $reviewLine = file("$movieReviewPathIn[$j]", FILE_IGNORE_NEW_LINES);

                //MUST be lower case to match string value of file
                $reviewLine[1] = strtolower($reviewLine[1]);
                ?>

                <p class="reviewSectionText">
                    <img src="img/movie/<?= $reviewLine[1] ?>.gif" alt="<?= $reviewLine[1] ?>"/>
                    <q><?= $reviewLine[0] ?> </q>
                </p>
                <p class='reviewSectionName'>
                    <img src='img/movie/critic.gif' alt='Critic'/>
                    <?= $reviewLine[2] ?><br/>
                    <?= $reviewLine[3] ?>
                </p>


                <?php
            }
            ?>
        </div>

        <?php
        //redefines first index value for array to the latest value of $j
        $low = $j;

        //redefines last index value for array to the array size
        $high = count($movieReviewPathIn);
    } ?>
    <?php
}

?>
<!-- PHP FUNCTIONS END-->