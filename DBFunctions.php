<?
include "./DBDefinitions.php";
global $mysqli;
$mysqli = new mysqli(DLS01_HOSTNAME, DLS01_USERNAME, DLS01_PASSWORD);
function dbQuery($query) {
    global $mysqli;
    $debug = false;
    if($debug) echo '<pre class="query">' . $query . '</pre>';
    $result = mysqli_query($mysqli, $query);
    return $result;
}
function resultsAsArray($mysqli_result) {
    $arr = array();
    while($arr[] = mysqli_fetch_assoc($mysqli_result)) {}
    return $arr;
}
function init() {
    dbQuery('CREATE SCHEMA IF NOT EXISTS Survey_Monkey_Results');
    dbQuery('USE Survey_Monkey_Results');
    dbQuery('CREATE TABLE IF NOT EXISTS Surveys (
        ID INT NOT NULL AUTO_INCREMENT,
        PRIMARY KEY(ID)
    )');
    dbQuery('CREATE TABLE IF NOT EXISTS SurveyResponses (
        ID INT NOT NULL AUTO_INCREMENT,
        ConfirmationCode INT,
        Survey_ID INT,
        PRIMARY KEY(ID)
    )');
    dbQuery('CREATE TABLE IF NOT EXISTS Questions (
        ID INT NOT NULL AUTO_INCREMENT,
        Survey_ID INT,
        QuestionText VARCHAR(4096),
        QuestionType VARCHAR(256),
        QuestionInstructions VARCHAR(4096),
        PRIMARY KEY(ID)
    )');
    dbQuery('CREATE TABLE IF NOT EXISTS QuestionResponses (
        ID INT NOT NULL AUTO_INCREMENT,
        Survey_ID INT,
        SurveyResponse_ID INT,
        Question_ID INT,
        ResponseText VARCHAR(4096),
        PRIMARY KEY(ID)
    )');
    dbQuery('CREATE TABLE IF NOT EXISTS QuestionChoices (
        ID INT NOT NULL AUTO_INCREMENT,
        Question_ID INT,
        ChoiceText VARCHAR(4096),
        PRIMARY KEY(ID)
    )');
}
function padNumber($n, $size = 2) {
    $padded = "";
    $padded .= $n;
    while(strlen($padded) < $size) $padded = "0" . $padded;
    return $padded;
}
function getQuestionIDs($survey_id) {
    $result = dbQuery("SELECT * FROM Questions
        WHERE Survey_ID={$survey_id};");
    $ids = array();
    $i = 0;
    while($row = mysqli_fetch_assoc($result)) {
        $ids[$i] = $row["ID"];
        $i++;
    }
    return $ids;
}
function getQuestionText($id, $survey_id) {
    $result = dbQuery("SELECT QuestionText FROM Questions 
        WHERE ID={$id};");
    $row = mysqli_fetch_row($result);
    return $row[0];
}
function getQuestionInstructions($id, $survey_id) {
    $result = dbQuery("SELECT QuestionInstructions FROM Questions 
        WHERE ID={$id};");
    $row = mysqli_fetch_row($result);
    return $row[0];
}
function getQuestionType($id, $survey_id) {
    $result = dbQuery("SELECT QuestionType FROM Questions 
        WHERE ID={$id};");
    $row = mysqli_fetch_row($result);
    return $row[0];
}
function getQuestionResponseText($question_id, $surveyresponse_id, $qname) {
    $responseText = "";
    $result = dbQuery("SELECT ResponseText FROM QuestionResponses 
        WHERE Question_ID={$question_id} 
        AND SurveyResponse_ID={$surveyresponse_id}");
    $row = mysqli_fetch_row($result);
    if(sizeof($row) == 1) $responseText = $row[0];
    if(!empty($_POST[$qname])) $responseText = $_POST[$qname];
    return $responseText;
}
function saveQuestionResponse($question_id, $survey_id, $surveyresponse_id, $position) {
    global $mysqli;
    $qname = "que" . padNumber($position, 2) . '_txt';
    $result = dbQuery("SELECT ID FROM QuestionResponses
        WHERE Question_ID={$question_id}
        AND Survey_ID={$survey_id}
        AND SurveyResponse_ID={$surveyresponse_id};");
    $assoc = mysqli_fetch_assoc($result);
    if($assoc) {
        $id = $assoc["ID"];
    }
    else {
        dbQuery("INSERT INTO QuestionResponses (Question_ID, Survey_ID, SurveyResponse_ID)
            VALUES ({$question_id}, {$survey_id}, {$surveyresponse_id});");
    }
    $responsetext = getQuestionResponseText($question_id, $surveyresponse_id, $qname);
    if($responsetext) {
        $responsetext = mysqli_real_escape_string($mysqli, $responsetext);
        dbQuery('UPDATE QuestionResponses
            SET ResponseText="'.$responsetext.'"
            WHERE Question_ID='.$question_id.'
            AND Survey_ID='.$survey_id.'
            AND SurveyResponse_ID='.$surveyresponse_id.';');
    }
}
function questionToHTML($question_id, $survey_id, $surveyresponse_id, $position = -1) {
    $questionText = getQuestionText($question_id, $survey_id);
    $questionType = getQuestionType($question_id, $survey_id);
    $questionInstructions = getQuestionInstructions($question_id, $survey_id);
    $qname = "que" . padNumber($position, 2) . '_txt';
    $questionResponseText = getQuestionResponseText($question_id, $surveyresponse_id, $qname);
    $HTML  = "<li>";
    if($questionText) {
        $HTML .= '<div class="question">';
        $HTML .= $questionText;
        $HTML .= '</div>';
    } 
    else {
        return "";
    }
    if($questionInstructions) {
        $HTML .= '<div class="instructions">';
        $HTML .= $questionInstructions;
        $HTML .= '</div>';
    }
    if($questionType == "text") {
        $HTML .= '<textarea class="answer" name="'.$qname.'">';
        $HTML .= $questionResponseText;
        $HTML .= '</textarea>';
    }
    else {
        if($questionType == "radio") {
            $result = dbQuery("SELECT ChoiceText FROM QuestionChoices
                WHERE Question_ID={$question_id};");
            $rows = resultsAsArray($result);
            foreach($rows as $index => $row) {
                $choicetext = $row["ChoiceText"];
                $savedChoice = $questionResponseText;
                if(!empty($_POST[$qname])) $savedChoice = $_POST[$qname];
                if(strlen($choicetext) > 0) {
                    $HTML .= '<div class="choice">';
                    $HTML .= '<input type="radio" id="'.$qname.'_'.$index.'" name="'.$qname.'" value="'.$choicetext.'" ';
                    if($savedChoice == $choicetext) $HTML .= 'checked="checked" ';
                    $HTML .= '/>';
                    $HTML .= '<label for="'.$qname.'_'.$index.'">' . $choicetext . '</label>';
                    $HTML .= '</div>';
                }
            }
        }
    }
    $HTML .= "</li>";
    return $HTML;
}
function getSurveyResponseID($code) {
    $result = dbQuery("SELECT ID FROM SurveyResponses
        WHERE ConfirmationCode=\"{$code}\";");
    if($result) {
        $rows = resultsAsArray($result);
        $id = ($rows[0]["ID"]);
    }
    else {
    }
    return $id;
}
init();
?>
