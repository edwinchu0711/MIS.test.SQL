var currentHeight = 0 ;
var In_menulist = false ;
var list_opening = false ;
preloader();
function pageload() {
    button();
}


function button(){
    document.querySelectorAll('.ani_button').forEach(button => {
        button.addEventListener('click', function(e) {
            
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
            ripple.style.top = `${e.clientY - rect.top - size / 2}px`;
            
            this.appendChild(ripple);
            
            ripple.addEventListener('animationend', () => {
                ripple.remove();
            });
        });
    });
}




function preloader(){ //網頁讀取前動畫
    var loader = document.getElementById("preloader");
    window.addEventListener("load", function(){
        // 延遲1.5秒後隱藏 preloader
        setTimeout(function(){
            loader.style.transition =  "opacity 1s ease";
            loader.style.opacity = "0";
            document.getElementById("hidebody").style.visibility = 'visible';
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