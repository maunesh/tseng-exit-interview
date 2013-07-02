<html>

<head>
	<title> Student Exit Survey </title> 
	<style> h1 {text-shadow:2px 2px #CCCCCC;} body { font-family: Arial; }</style>
</head>

<body>
	<div style="width:100%;background-color:#d0d0d0;">
	<div style="width:80%;margin-left:8%;background-color:#fff;padding:20px;">
	<h1> <img align="middle" src="hdlg.png" alt="tseng_logo"></h1>
	<h1> <center> <i> Student Exit Survey Submission </i> </center> </h1> 

	<?php
	
	//Question 01
	$ans01 = $_POST['que01_txt'];
	echo ($ans01."<br>"); 
	
	//Question 02
	if (isset($_POST['que02'])) { 
		$selected_radiobtn = $_POST['que02']; 	
		//set the value
		if ($selected_radiobtn == 'y') { 
			$ans02 = 'Yes'; 
		}elseif($selected_radiobtn == 'n') { 
			$ans02 = 'No';		
		// if it is empy
		} else { 
		// set the value to NULL
			$ans01 = ''; //NULL
		}
		echo ($ans02."<br>");
	}
	
	//Question 03
	$ans03 = $_POST['que03_txt'];
	echo ($ans03."<br>"); 
	
	//Question 04
	$ans04 = $_POST['que04_txt'];
	echo ($ans04."<br>"); 
	
	//Question 05
	$ans05 = $_POST['que05_txt'];
	echo ($ans05."<br>"); 
	
	//Question 06
	$ans06 = $_POST['que06_txt'];
	echo ($ans06."<br>"); 

	//Question 07
	$ans07 = $_POST['que07_txt'];
	echo ($ans07."<br>"); 
	
	//Question 08
	$ans08 = $_POST['que08_txt'];
	echo ($ans08."<br>"); 
	
	//Question 09
	$ans09 = $_POST['que09_txt'];
	echo ($ans09."<br>"); 
	
	//Question 10
	if (isset($_POST['que10'])) { 
		$selected_radiobtn = $_POST['que10']; 	
		//set the value
		if ($selected_radiobtn == 'y') { 
			$ans10 = 'Yes'; 
		}elseif($selected_radiobtn == 'n') { 
			$ans10 = 'No';		
		// if it is empy
		} else { 
		// set the value to NULL
			$ans10 = ''; //NULL
		}
		echo ($ans10."<br>");
	}
	
	//Question 11
	$ans11 = $_POST['que11_txt'];
	echo ($ans11."<br>"); 
	
	//Question 12
	$ans12 = $_POST['que12_txt'];
	echo ($ans12."<br>"); 

	//function to check if the question is anwered of not
	function isAnswered($passed){
		if (empty($passed)){
			echo $passed.' is not answered.';
		}
		else
			echo $passed.' is answered.';
	}
	
	
	?>

	
</body>


</html>