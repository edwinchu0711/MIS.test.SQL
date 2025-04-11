if (typeof BASE_PATH == 'undefined') {
    var BASE_PATH = window.location.origin;
} 



function initNavbar() {
    // 檢查是否已經初始化
    window.navbarInitialized = true;
    cookieDetect();
    positionArrowAboveCookie();
}







function cookie_good() {
    var btn_yes = document.getElementById("cookie_yes");
    btn_yes.innerHTML = "Yeah!";
    document.body.style.cursor = `url("${BASE_PATH}/img/Cookie.webp"), auto`;

    var cookie_p = document.getElementById("cookie_p");
    cookie_p.innerHTML = "YOU ARE COOKIE!";
    var btn = document.getElementById("cookie_button");
    btn.style.display = 'none';

    setTimeout(function () {
        btn.style.display = "inline";
        cookie_p.innerHTML = "Do you want to continue using a cookie as a mouse?";
    }, 5000);

    document.getElementById('cookie_yes').addEventListener('click', function () {
        var field = document.getElementById('cookie');
        sessionStorage.setItem("cookie", "yes"); // 儲存到 sessionStorage
        field.style.display = 'none';
        if (document.getElementById("arrow-box")){
            positionArrowAboveCookie();
        }

        // 發送 AJAX 請求到伺服器，更新 PHP 的 $_SESSION
        fetch(`${BASE_PATH}/views/cookie/update-session.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ key: 'cookie', value: 'yes' }) // 傳送 JSON 資料
        })
        .then(response => response.json())
        .then(data => console.log('Server response:', data)) // 確認伺服器回應
        .catch(error => console.error('Error updating session:', error));
    });
}

function cookie_bad() {
    var btn_no = document.getElementById("cookie_no");
    btn_no.innerHTML = "QAQ";
    document.body.style.cursor = "default";
    var btn_yes = document.getElementById("cookie_yes");
    btn_yes.style.display = 'none';

    setTimeout(function () {
        var field = document.getElementById('cookie');
        field.style.display = 'none';
        sessionStorage.setItem("cookie", "no"); // 儲存到 sessionStorage
        if (document.getElementById("arrow-box")){
            positionArrowAboveCookie();
        }
        

        // 發送 AJAX 請求到伺服器，更新 PHP 的 $_SESSION
        fetch(`${BASE_PATH}/views/cookie/update-session.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ key: 'cookie', value: 'no' }) // 傳送 JSON 資料
        })
        .then(response => response.json())
        .then(data => console.log('Server response:', data)) // 確認伺服器回應
        .catch(error => console.error('Error updating session:', error));
    }, 2000);
}


function cookieDetect(){ //偵測cookie點擊
    document.getElementById("cookie_yes").onclick = cookie_good;
    document.getElementById("cookie_no").onclick = cookie_bad;
}

function positionArrowAboveCookie() {
    const arrowBox = document.getElementById('arrow-box');
    if (arrowBox){
       const cookie = document.getElementById('cookie');
        function adjustArrowPosition() {
            const cookieHeight = cookie.offsetHeight;
            arrowBox.style.bottom = `${cookieHeight+10}px`; // 讓箭頭在 cookie 上面
        }
        // 調整箭頭位置
        adjustArrowPosition();

        // 如果視窗大小改變，重新調整箭頭位置
        window.addEventListener('resize', adjustArrowPosition); 
    }
    

}

initNavbar();
document.addEventListener('DOMContentLoaded', initNavbar);
