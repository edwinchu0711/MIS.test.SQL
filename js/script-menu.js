
if (typeof BASE_PATH == 'undefined') {
    var BASE_PATH = window.location.origin;
} 
function initNavbar() {
    // 檢查是否已經初始化
    if (window.navbarInitialized) ;
    window.navbarInitialized = true;
    setTimeout(() => {
    menu_content_detect();
    },1000);
    // menuBG();
  adjustMenuInContentArea();

// 如果需要在視窗調整大小後重新計算，可以綁定 resize 事件
    window.addEventListener("resize", adjustMenuInContentArea);
   
}

function menuBG(){
    const backgroundColors = [
        {
            name: '薰衣草奶霜',
            gradient: 'linear-gradient(to bottom, #E8E6F0, #F6F4FA)',
            borderColor: '#9D91C4'
        },
        {
            name: '蜜桃奶昔',
            gradient: 'linear-gradient(to bottom, #FFE4E1, #FFF0F0)',
            borderColor: '#FFB6C1'
        },
        {
            name: '抹茶拿鐵',
            gradient: 'linear-gradient(to bottom, #E8F0E6, #F4FAF2)',
            borderColor: '#90C499'
        },
        {
            name: '藍莓優格',
            gradient: 'linear-gradient(to bottom, #E6EEF4, #F2F7FA)',
            borderColor: '#89CFF0'
        },
        {
            name: '焦糖瑪奇朵',
            gradient: 'linear-gradient(to bottom, #F2E6D9, #FFF5EE)',
            borderColor: '#DEB887'
        },
        {
            name: '玫瑰奶茶',
            gradient: 'linear-gradient(to bottom, #F4E2E2, #FFF0F0)',
            borderColor: '#FFB6B6'
        },
        {
            name: '薄荷冰淇淋',
            gradient: 'linear-gradient(to bottom, #E6F4F1, #F2FAF8)',
            borderColor: '#98D8D6'
        }
    ];

    let currentColorIndex = -1;

    function updateColors(newIndex) {
        const menuElement = document.getElementById('menu');
        
        // 更新背景顏色
        menuElement.style.background = backgroundColors[newIndex].gradient;
        
        // 更新按鈕邊框顏色
        const buttons = menuElement.getElementsByClassName('button-text');
        for(let button of buttons) {
            button.style.setProperty('--c', backgroundColors[newIndex].borderColor);
        }

        // 更新圖片邊框顏色
        const tableImages = menuElement.getElementsByClassName('table-img');
        for(let img of tableImages) {
            img.style.borderColor = backgroundColors[newIndex].borderColor;
        }
    }

    // 點擊事件處理
    document.getElementById('menu').addEventListener('click', function() {
        this.classList.remove('color-transition');
        void this.offsetWidth;
        this.classList.add('color-transition');
        
        let newIndex;
        do {
            newIndex = Math.floor(Math.random() * backgroundColors.length);
        } while (newIndex === currentColorIndex);
        
        currentColorIndex = newIndex;
        updateColors(newIndex);
    });

    // 初始化顏色
    const menuElement = document.getElementById('menu');
    menuElement.classList.remove('color-transition');
    void menuElement.offsetWidth;
    menuElement.classList.add('color-transition');
    
    let newIndex;
    do {
        newIndex = Math.floor(Math.random() * backgroundColors.length);
    } while (newIndex === currentColorIndex);
    
    currentColorIndex = newIndex;
    updateColors(newIndex);
}

function adjustMenuInContentArea() {
    // 獲取所有的 .table-img 元素
    const tableImgs = document.querySelectorAll(".table-img");

    // 檢查是否存在 .table-img 元素
    if (tableImgs.length === 0) return;

    // 初始化最大高度和最大寬度
    let maxHeight = 0;
    let maxWidth = 0;

    // 遍歷所有 .table-img，計算最大高度和最大寬度
    tableImgs.forEach(img => {
        const imgHeight = img.offsetHeight; // 獲取元素的高度
        const imgWidth = img.offsetWidth; // 獲取元素的寬度

        if (imgHeight > maxHeight) {
            maxHeight = imgHeight; // 更新最大高度
        }

        if (imgWidth > maxWidth) {
            maxWidth = imgWidth; // 更新最大寬度
        }
    });

    // 設置 menu-incontent-area 的高度和寬度
    const menuInContentArea = document.querySelector(".menu-incontent-area");
    if (menuInContentArea) {
        menuInContentArea.style.height = `${maxHeight}px`; // 設置高度為最大高度
        menuInContentArea.style.width = `${maxWidth * 0.4}px`; // 設置寬度為最大寬度的 40%
    }
}




function menu_content_detect() { // menu 的背景動畫+內容偵測
    let canAnimating = true; 
    const menu_content = document.querySelectorAll(".menu-table-content");
    const menu_table = document.getElementById("menu");
    if (!menu_table){
        return;
    }
    const windowHeight = window.innerHeight; // 視窗的高度
    let backgroundfade = false;
    let threshold = 100; // 計算元素上方的像素數
    let speed = 0.006;
    let change = 0.3;

    window.addEventListener('scroll', throttle(() => {
        const elementRect = menu_table.getBoundingClientRect(); //取得元素的位置
        const elementTop = Math.abs(elementRect.top); //取絕對值
        const pixelsAbove = Math.abs(windowHeight - threshold);

        if (elementTop < pixelsAbove) {
            let i = 0;
            if (canAnimating){
                delay();
                    if (!backgroundfade) {
                        backgroundfade = true;
                        backgroundChange();
                    }
                canAnimating = false;
                anime({
                targets: '#menu',
                opacity: [0, 1],       // 從 0 變到 1
                duration: 1200,
                easing: 'easeInOutQuad',
                complete: function () {
                    
                }
            });
            }
            
            function delay() {
                if (i < menu_content.length) {
                    menu_content[i].classList.remove("hide_up");
                    menu_content[i].classList.add("menu-show");
                    setTimeout(delay, 180);
                    i++;
                }
            }

            function backgroundChange() {
                if (change > 0.7) {
                    speed = 0.002;
                }
                else{
                    speed = 0.006;
                }
                if (change < 1) {
                   
                    change += speed;
                    requestAnimationFrame(backgroundChange);
                }
            }

            
        } 
        else if (elementTop > 2000 || window.scrollY < 15 && elementTop > 1000) {
            menu_content.forEach(element => {
                if (element.classList.contains("menu-show")) {
                    element.classList.remove("menu-show");
                    element.classList.add("hide_up");
                    document.getElementById("menu").style.opacity = '0';
                    canAnimating = true;
                    threshold = 200;
                }
            });

            change = 0;
            backgroundfade = false;
        }
    }, 200)); // 使用節流來減少事件觸發

    // 節流函數
    function throttle(func, limit) {
        let lastFunc;
        let lastRan;
        return function() {
            const context = this;
            const args = arguments;
            if (!lastRan) {
                func.apply(context, args);
                lastRan = Date.now();
            } else {
                clearTimeout(lastFunc);
                lastFunc = setTimeout(function() {
                    if ((Date.now() - lastRan) >= limit) {
                        func.apply(context, args);
                        lastRan = Date.now();
                    }
                }, limit - (Date.now() - lastRan));
            }
        }
    }
}

initNavbar();