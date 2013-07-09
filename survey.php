<?
include "./DBFunctions.php";
$survey_id = 1;
$surveyresponse_id = 1;
$question_id = 1;
$question_ids = getQuestionIDs($survey_id);
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
	<h1> <img align="middle" src="hdlg.png" alt="tseng_logo"></h1>
	<h1> <center> <i> Student Exit Survey  </i> </center> </h1> 
	
	<form method="post">
		<ul type="none"><li><i> Please check the radio buttons below, where applicable. </i></li></ul>
		<ol>
<?
$position = 1;
foreach($question_ids as $question_id) {
    saveQuestionResponse($question_id, $survey_id, $surveyresponse_id, $position);
    echo questionToHTML($question_id, $survey_id, $surveyresponse_id, $position);
    $position++;
}
?>
		
                <!--
		<li>
			<strong> What degree(s) did you earn from Tseng College, CSUN?  </strong><br>	
			<ul> 
				<li> List them below: <br>
                                        <textarea name="que01_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> Was this your first experience with taking an online course?  </strong><br>	
			<ul> 
				<li> 
					<input type="radio" name="que02" value="y" /> Yes
					<input type="radio" name="que02" value="n" /> No
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> How would you compare this educational experience with other educational experiences online? </strong><br>	
			<ul> 
				<li> Describe below: <br>
					<textarea name="que03_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> If you have taken opnline courses outside of the Tseng College, CSUN, please list the colleges and/or universities where you attended online courses. </strong><br>	
			<ul> 
				<li> List them below: <br>
					<textarea name="que04_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> What did you feel were the two greates benefits of being enrolled in an ounline courses? What were two things you did not like about being enrolled in an online course?</strong><br>	
			<ul> 
				<li> List them below: <br>
					<textarea name="que05_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> How would you rate your learning experience of the online program you participated in on a scale of 1 to 5?  </strong><br>	
			<ul> 
				<li> Enter one, 5 being the best and 1 being the worst. <br>
					<textarea name="que06_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> Why did you choose this rating? </strong><br>	
			<ul> 
				<li> Explain below: <br>
					<textarea name="que07_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> Please tell me your impression of this online experience including tech support, course design, faculty involvement or anything else that comes to mind.  </strong><br>	
			<ul> 
				<li> Write below: <br>
					<textarea name="que08_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		
		
		<li>
			<strong> If you were seeking another course or program that was offered in an online enviromnet, is there anything that you would look for that was not a part of your experience in your program offered through Tseng College? </strong><br>	
			<ul> 
				<li> List them below: <br>
					<textarea name="que09_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> Was enough information about the online program given to you from the onset when you first made inquiries?  </strong><br>	
			<ul> 
				<li> 
					<input type="radio" name="que10" value="y" /> Yes
					<input type="radio" name="que10" value="n" /> No
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> Do you have any suggestion for us to improve the educational experience or program in an online format? </strong><br>	
			<ul> 
				<li> List them below: <br>
					<textarea name="que11_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		
		<li>
			<strong> That concludes our survey. Do you have any last minute comment or question for me?  </strong><br>	
			<ul> 
				<li> Type below: <br>
					<textarea name="que12_txt" style="color: green; background-color: lightyellow"; rows="5" cols="50"></textarea>
				</li>
			</ul>
		</li>
		<br>
		-->
		
		</ol>

		
		
		
			<div style="text-align: center;">
			<input type="submit" style="width:150px; height:40px; color: #98012C; font-size: large; background-color: lightyellow;" value="Submit">
			</div>

	</form>
	</div>
	</div>	

</body>

</html>
