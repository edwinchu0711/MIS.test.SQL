var currentHeight = 0 ;

var In_menulist = false ;

var list_opening = false ;

preloader();

function pageload() {

    up_detect();

    window.addEventListener("resize",profileImgSize);

    profileImgSize();

}







function profileImgSize(){

    const header = document.getElementById("header");

    const BGImg = document.getElementById("header-img");

    const profileImg = document.getElementById("profileImg");

    const profileContent = document.getElementById("profile-content");

    BGImg.style.height = window.innerHeight*0.6 + 'px';

    const computedHeight = parseFloat(getComputedStyle(header).height);

    let IMG_R = Math.min(window.innerWidth * 0.23, computedHeight * 0.42+80);

    if (window.innerWidth > 800){
        profileImg.style.width = IMG_R + 'px';
        profileImg.style.height = IMG_R + 'px';
        profileContent.style.flexDirection = 'row';

        profileImg.style.top = window.innerHeight*0.6 -IMG_R*0.3+'px'

        profileImg.style.left = window.innerWidth*0.04 +'px';

        profileImg.style.border =   `${IMG_R * 0.03}px solid rgb(255, 255, 255)` ;/* 暖金色邊框 */

        header.style.marginBottom = 100 + IMG_R * 0.5 +'px';

        profileContent.style.marginLeft = (window.innerWidth*0.04 + IMG_R*1.1) + 'px';

        profileContent.style.marginRight = window.innerWidth*0.04 +'px';

        profileContent.style.marginTop = IMG_R*0.15+'px' ;

    }
    else{
        IMG_R *= (1200-window.innerWidth)/400;
        profileImg.style.width = IMG_R + 'px';
        profileImg.style.height = IMG_R + 'px';
        profileContent.style.flexDirection = 'column';

        profileContent.style.marginLeft = '10%';

        profileContent.style.marginRight = '10%';

        profileContent.style.marginTop = IMG_R*0.8+'px';

        profileImg.style.top = window.innerHeight*0.6 -IMG_R*0.3+'px'

        profileImg.style.left = window.innerWidth*0.5 - IMG_R*0.53 + 'px';

        profileImg.style.border =   `${IMG_R * 0.03}px solid rgb(255, 255, 255)` ;/* 暖金色邊框 */

        header.style.marginBottom = 100 + IMG_R * 0.5 +'px';

    }

    







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