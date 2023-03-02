<?php
function logArray($array)
{
    print "<pre class='bg-info w-50'>";
    print_r($array);
    print "</pre>";
    return;
}
function loadENV()
{
    $dirConvert = str_replace('\src\utils', '', __DIR__);
    require_once realpath($dirConvert . '/vendor/autoload.php');
    $dotenv = Dotenv\Dotenv::createImmutable($dirConvert);
    $dotenv->load();
}

function getUrlImg($name)
{
    return dirname($_SERVER["PHP_SELF"]) . "/src/assets/img/$name";
}

// define('SECRET_KEY', 'devkebannghe');
function hashPassword($password)
{
    // $hashPass = md5($password . SECRET_KEY);
    return md5($password);
}

function useJavaScript($logic)
{
    echo "
        <script>
            $logic
        </script>
    ";
}

function countDownTimer($duration, $elementId)
{
    useJavaScript("
        function checkedBefore(){
            let arr = [];
            window.sessionStorage.setItem('checkedBefore', JSON.stringify(arr));
            for (const item of document.querySelectorAll('input')) {
                if(item.checked){
                    arr.push(item.value);
                    window.sessionStorage.setItem('checkedBefore', JSON.stringify(arr));
                }
            }
        }
        
        function startTimer(duration, display) {
            var timer = duration, minutes, seconds;
            const myInterval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
        
                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;
        
                display.textContent = minutes + ':' + seconds;
                if (--timer < 0) {
                    //timer = duration;
                    clearInterval(myInterval);
                    window.sessionStorage.setItem('timeout', true);
                    checkedBefore();
                    window.location.search += '&timeout';
                }
            }, 1000);
        }
        
        window.onload = function () {
            var display = document.querySelector('$elementId');
            let timeout = window.sessionStorage.getItem('timeout');
            if(!timeout)
                startTimer($duration, display);
        };
    ");
}

function setSessionStorage($key, $value = '')
{
    useJavaScript("
        window.sessionStorage.setItem('$key', $value);
    ");
}


function removeSessionStorage($jsonArr)
{
    useJavaScript("
        for (const item of JSON.parse('$jsonArr')) {
            window.sessionStorage.removeItem(item);
        }
    ");
}

function reloadCurrentPage($duration = 0, $routeParams = '')
{
    include $GLOBALS['controller']->render('views/both/loading.php');
    echo ("<meta http-equiv='refresh' content='$duration; url=$routeParams')>");
}

function toast($type, $message)
{
    useJavaScript("
        toastr.options.timeOut = 5000;
        toastr.$type('$message');
    ");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendEmail($toEmail, $title, $contentHTML)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $_ENV['EMAIL_SENDING_RESET_PASSWORD'];                     //SMTP username
    $mail->Password   = $_ENV['PASSWORD_EMAIL_SENDING_RESET_PASSWORD'];                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($_ENV['EMAIL_SENDING_RESET_PASSWORD'], 'Trung');
    $mail->addAddress($_ENV['EMAIL_SENDING_RESET_PASSWORD']);     //Add a recipient
    $mail->addAddress($toEmail);               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $title;
    $mail->Body    = $contentHTML;
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    return $mail->Send();
}

use PhpOffice\PhpSpreadsheet\IOFactory;

function loadXLSX($file)
{
    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($file);
    $schdeules = $spreadsheet->getActiveSheet()->toArray();
    return $schdeules;
}

function mergeArraySameKeys($array, $key)
{
    $outer_array = array();
    $unique_array = array();
    foreach ($array as $value) {
        $inner_array = array();

        $fid_value = $value[$key];
        if (!in_array($value[$key], $unique_array)) {
            array_push($unique_array, $fid_value);
            unset($value[$key]);
            array_push($inner_array, $value);
            $outer_array[$fid_value] = $inner_array;
        } else {
            unset($value[$key]);
            array_push($outer_array[$fid_value], $value);
        }
    }

    return $outer_array;
}
