<h1>Pie chart demo</h1>
<?
include "./pieChart.php";
showPieChart(array(
        "questionText" => "Where did you take online courses before coming to the Tseng College?",
        "responses" => array(
            array(
                "choiceText" => "CSUN",
                "count" => 2
            ),
            array(
                "choiceText" => "UCLA",
                "count" => 2
            ),
            array(
                "choiceText" => "ASU",
                "count" => 1
            ),
            array(
                "choiceText" => "Santa Monica College",
                "count" => 2
            ),
            array(
                "choiceText" => "Pierce College",
                "count" => 2
            )
        )
    ));
showPieChart(array(
        "questionText" => "Where did you take online courses before coming to the Tseng College?",
        "responses" => array(
            array(
                "choiceText" => "CSUN",
                "count" => 2
            ),
            array(
                "choiceText" => "UCLA",
                "count" => 2
            ),
            array(
                "choiceText" => "ASU",
                "count" => 1
            ),
            array(
                "choiceText" => "Santa Monica College",
                "count" => 2
            ),
            array(
                "choiceText" => "Pierce College",
                "count" => 2
            )
        )
    ));
?>
<a href="https://github.com/JoshuaDavid/tseng-exit-interview">Source on GitHub</a>
