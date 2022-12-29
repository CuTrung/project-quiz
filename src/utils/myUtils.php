<?php

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

function countDownTimer($duration, $elementId)
{
    echo "
    <script>
    function checkedBefore(){
        let arr = [];
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

    </script>
    ";
}


function removeSessionStorage($jsonArr)
{
    echo "
        <script>
        for (const item of JSON.parse('$jsonArr')) {
            window.sessionStorage.removeItem(item);
        }
        </script>
    ";
}
