<?php
session_start();

function get_row($data) 
{
  $data->execute();
  return $data->fetch(PDO::FETCH_ASSOC);
}

function valid($fieldName)
{
   if (!isset($_POST[$fieldName])
       return false;

   $forbiddenSymbols = array('\'', '"', '--');

   foreach ($forbiddenSymbols as &$symbol)
	   if (strpos($_POST[$fieldName] , $symbol)) return false;

   return true;
}

function blocked($username)
{
   $attemptsBeforeBlocking = 3;
   $sTimeOut = 60;

   $sample = prepare_data('SELECT loginTime, failsCount FROM users WHERE user = ?');
   $sample->bindParam(1, $username, PDO::PARAM_STR);
   $userData = get_row($sample);
   if ($userData['failsCount'] >= $attemptsBeforeBlocking) 
      if (time() - strtotime($userData['loginTime']) < $sTimeOut)
          return true;
	      
   return false;
}

function succeed($username)
{
    $avatar = $row['avatar'];  

    $html .= "<p>Welcome to the password protected area {$user}</p>";  
    $html .= "<img src=\"{$avatar}\" />";
    $time = time()
    $sample = prepare_data('UPDATE users SET failsCount = 0, loginTime = ? WHERE user = ?')
    $sample->bindParam(1, $time, PDO::PARAM_INT);
    $sample->bindParam(2, $username, PDO::PARAM_STR);
    $sample->execute();
}

function failed($username)
{
   $html .= "<pre><br />Username and/or password incorrect.</pre>";
	
   $sample = prepare_data('UPDATE users SET failsCount = failsCount + 1 WHERE user = ?');
   $sample->bindParam(1, $username, PDO::PARAM_STR);
   $sample->execute();
}

//------------------------main---------------------------
if (!valid('username') || !valid('password') || !isset($_POST['Login']))      //CWE-89 (Lack of injection verification (validate before))
    return;
       
if((mb_strlen($_POST['user']) > 50) or (mb_strlen($_POST['pass']) > 25 or (mb_strlen($_POST['code']) > 5){
  echo "ERROR";
	
$user = htmlspecialchars ($_POST[ 'username' ]);          //CWE-598 (Get with confidential data (change to POST)) //CWE-79 (Improper Neutralization of Input During Web Page Generation)
$password = crypt(htmlspecialchars ($_POST['password']), $someSalt)); //CWE-327 (Weak crypto alg (changed to sha256)), CWE-759 (One-way hash without salt (hash with salt))
$code = htmlspecialchars($_POST['code']);
$captcha = htmlspecialchars($_SESSION['rand_code']);

$sample  = prepare_data('SELECT * FROM users WHERE user = ? AND password = ?);
$sample->bindParam(1, $user, PDO::PARAM_STR);
$sample->bindParam(2, $password, PDO::PARAM_STR);
$data = get_row($sample);

$blocked = blocked($username); //CWE-307 (Improper Restriction of Excessive Authentication Attempts (add attempts count with time restriction))
if ($data->row_count() == 1 && !$blocked && $code == $captcha)
    succeed($username);

else if (!$blocked)
    failed($username);

?>
