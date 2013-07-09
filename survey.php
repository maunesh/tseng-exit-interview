<?
include "./DBFunctions.php";
if($_REQUEST['survey_id']) $survey_id = $_REQUEST['survey_id'];
else $survey_id = 1;
$surveyresponse_id = getSurveyResponseID($_REQUEST["confirmationcode"]);
$question_id = 1;
$question_ids = getQuestionIDs($survey_id);
$position = 1;
foreach($question_ids as $question_id) {
    saveQuestionResponse($question_id, $survey_id, $surveyresponse_id, $position);
    $position++;
}
if($_POST["Submit"] == "Submit") {
    header("Location: ./thanks.html");
}
?>
<html>
    <head>
        <title> Student Exit Survey </title> 
        <style> 
            h1 {
                text-shadow:2px 2px #CCCCCC;
            } 
            body {
                font-family: Arial;
            }
            textarea.answer {
                background-color: lightyellow;
                color: green;
                height: 5em;
                width: 50ex;
            }
        </style>
    </head>
    <body>
        <div style="width:100%;background-color:#d0d0d0;"> 
            <div style="width:80%;margin-left:8%;background-color:#fff;padding:20px;">
                <h1>
                    <img align="middle" src="hdlg.png" alt="tseng_logo">
                </h1>
                <h1>Student Exit Survey</h1> 
            
                <form method="post">
                    Please check the radio buttons below, where applicable. 
                    <ol>
    <?
    $position = 1;
    foreach($question_ids as $question_id) {
        echo questionToHTML($question_id, $survey_id, $surveyresponse_id, $position);
        $position++;
    }
    ?>
                    </ol>
                    <div style="text-align: center;">
                        <input type="submit" name="Save" style="width:150px; height:40px; color: #98012C; font-size: large; background-color: lightyellow;" value="Save" />
                        <input type="submit" name="Submit" style="width:150px; height:40px; color: #98012C; font-size: large; background-color: lightyellow;" value="Submit" />
                    </div>
                </form>
            </div>
        </div>	
    </body>
</html>
