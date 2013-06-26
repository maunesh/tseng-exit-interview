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
