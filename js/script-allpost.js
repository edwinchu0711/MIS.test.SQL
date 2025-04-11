var currentHeight = 0 ;

var In_menulist = false ;

var list_opening = false ;

preloader();

function pageload() {

    up_detect();
    adjustFontSize();
    window.addEventListener('resize', adjustFontSize);
}

function adjustFontSize() {
    const screenWidth = window.innerWidth; // 取得螢幕寬度
    const element = document.querySelector('#header-title'); // 取得文字容器
    const textLength = element.innerText.length; // 取得文字長度

    let fontSize;
    let letterSpacing;

    // 根據字數和螢幕大小動態調整字體大小
    if (textLength <= 10) {
        fontSize = screenWidth * 0.04; // 字數少於 10，字體大小為螢幕寬度的 5%
    } else if (textLength <= 15) {
        fontSize = screenWidth * 0.026; // 字數在 11-20，字體大小為螢幕寬度的 4%
        element.style.marginBottom = '2%';
    } else if (textLength <= 25) {
        fontSize = screenWidth * 0.023; // 字數在 21-50，字體大小為螢幕寬度的 3%
        element.style.marginBottom = '2.3%';
    } 

    // 根據字體大小動態設定字間距
    letterSpacing = fontSize * 0.1; // 字間距為字體大小的 10%

    // 設置字體大小與字間距
    element.style.fontSize = fontSize + 'px';
    element.style.letterSpacing = letterSpacing + 'px';
}









function up_detect(){

    const arrow = document.querySelector("#arrow");

    const arrowBox = document.getElementById("arrow-box");

    window.addEventListener('scroll', () => {

        if (window.scrollY > 900){

            arrow.className = "show";

            arrowBox.style.visibility = "visible"

        }

        else {

            arrowBox.style.visibility = "hidden"

            arrow.className = "hidden";

        }  

    });



}



function preloader(){ //網頁讀取前動畫

    var loader = document.getElementById("preloader");

    window.addEventListener("load", function(){

        // 延遲1.5秒後隱藏 preloader

        setTimeout(function(){

            loader.style.transition =  "opacity 1s ease";

            loader.style.opacity = "0";

        }, 1500); // 1500毫秒 = 1.5秒

        setTimeout(function(){

            loader.style.display = "none";

        }, 2500);

    });

}







window.onload = pageload ;

// *******************************************************

// *******************************************************

// *******************************************************

// *******************************************************

// *******************************************************

// *******************************************************

// *******************************************************

// *******************************************************

// *******************************************************

// *******************************************************

// *******************************************************