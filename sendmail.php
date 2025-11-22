<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "./email/email.php";
        

function User_Input($data){
   $pattern = "/www./i";
   $pattern2 = "/http/i";  
	$data = trim($data);

  	$data = stripslashes($data);

  	$data = htmlspecialchars($data);
  	
  	if(preg_match($pattern, $data)){
  	    return $data;
  	}
  	if(preg_match($pattern2, $data)){
  	    return $data;
  	}
  	return $data;

}



if(isset($_POST['submit'])){

	if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'], $_POST['captcha_code']) != 0){
			header('location:contact.html?message=captcha_error');
			exit;
	}else{	
		
	
		
		
		

		$name = User_Input(filter_var($_REQUEST['fname'], FILTER_SANITIZE_STRING));
		$email =  filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL);
		$mobile = User_Input($_REQUEST['phone']);
		
		$detail =  User_Input(filter_var($_REQUEST['message'], FILTER_SANITIZE_STRING));

		

	
		//**********   step-2 change domain with original domain name********** 
		$subject = "You have received feedback from your Website";

		$email_cc='';
		
		$domain="www.nhhydraulik.co.in";
        $date=date('Y-m-d');
         
         try {
				$date=date('Y-m-d');
				$conn = new PDO("mysql:host=itology.in;dbname=itoloyok_mail", "itoloyok_test", "test123");
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sth = $conn->prepare('SELECT * FROM clients WHERE domain = :domain and mail_date = :date and email = :email');
                $sth->bindParam(":domain", $domain);
                $sth->bindParam(":date", $date);
                $sth->bindParam(":email", $email);
                $sth->execute();
                
                $row = $sth->fetchAll();
                $rows=$sth->rowCount();
                //echo $rows;
                //echo "<pre>";print_r($row);exit;
                if($rows > 0){
                   header('location:contact.html?message=Please enter another email');
                   exit;
                }
            
        } catch (PDOException $e) {
            header('location:contact.html?message=connection_failed');
            exit;

        }
        
       
         

		if (preg_match("/http/i", $detail)) {
		 
			header('location:contact.html'); 
			exit;
			
			}else{
			
			
			
			//**********step-3 meesage build
			$message ='<br>Name :'.$name;
			$message .='<br>Email  :'.$email;
			$message .='<br>Phone No.  :'.$mobile;
			$message .='<br>Message Details :'.$detail;  
			$message .='<br>';  

			//$to_add = "";  // step-4 ********** change Mail id 
			$to = "kheratkaraniket1@gmail.com";  
			$cc = "";  
			$bcc = "kheratkaraniket2@gmail.com";  

			

			if(sendmail($to,$cc,$bcc,$subject,$message)) 
			{	
				 
				try {
						$date=date('Y-m-d');
						$conn = new PDO("mysql:host=itology.in;dbname=itoloyok_mail", "itoloyok_test", "test123");
						$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

						//********** step-7 change companyname with orginal name,  chnage domain with orginal domain name
				    $data = array('Aniket Kheratkar', $email, $name, $mobile, $detail, $date, $subject,$email_cc);
						$sql =$conn->prepare("INSERT INTO `clients`(`companyName`, `domain`, `email`, `name`, `number`, `msg`,`mail_date`,`subject`,`to_mail`)
						VALUES (?, ?, ?,?,?,?,?,?,?)");
		
						  $check=$sql->execute($data);
						  if($check){
							
							} else{
							
							}
						}
						catch(PDOException $e)
						{
					   
						}
					
				$msg = "Mail sent OK";
				header('location:contact.html?message=send');
				die(); 

				
			} 
			else 
			{
		 	    $msg = "Error sending email!";
				header('location:contact.html?message=Error'); 
				die(); 

			}


		}	
	}
}

?>