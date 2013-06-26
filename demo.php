<?
include "./pieChart.php";
showPieChart(array(
        "questionText" => "What degree did you earn from the Tseng College?",
        "responses" => array(
            array(
                "choiceText" => "answered question",
                "count" => 4
            ),
            array(
                "choiceText" => "skipped question",
                "count" => 6
            )
        )
    ));
