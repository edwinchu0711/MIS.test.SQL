var currentHeight = 0 ;
var filterchose = false;
if (typeof BASE_PATH == 'undefined') {
    var BASE_PATH = window.location.origin;
} 
let introductiontop = 1.4 ;

var marquee_gap = 0.03;

preloader();

function pageload() {
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
          navigator.serviceWorker.register('/service-worker.js')
            .then(registration => {
              console.log('ServiceWorker 註冊成功:', registration.scope);
            })
            .catch(error => {
              console.log('ServiceWorker 註冊失敗:', error);
            });
        });
      }
      
    document.getElementById("hidebody").style.visibility="visible";
    food_marquee();
    setupHoverEffects();
    profileImgSize();
    initSearch();
    autocomplete();
    setTimeout(() => {   
        weBelieve_detect();
    },1500);
    window.addEventListener("resize",profileImgSize);
    document.getElementById("headerWhite").style.background = `linear-gradient(rgba(248 ,217 ,171, ${0.1+0.3*0.35}), rgba(248 ,236, 230, ${0.1+0.3*0.45}), white)`;
    
    window.addEventListener("resize",indexButton);
    indexButton();
    checkFlexWrap();
    introduction();
    
    window.addEventListener("resize",introduction);
    
    window.addEventListener('resize', () => {
        checkFlexWrap();
    });
    setTimeout(() => {
        window.addEventListener("scroll",()=>{
        indexNavbar();
    })
    }, 1200);
    
}


function indexNavbar(){
    const navbar = document.getElementById('navbar');
    window.addEventListener("scroll",()=>{
        navbar.classList.add('visible');
        return;
    })
    setTimeout(() => {
        navbar.classList.add('visible');
        return;
    }, 4000);
}




function autocomplete(){
    const searchInput = document.getElementById('search-index-input');
    const filterTags = document.querySelectorAll('.filter-tag');
    
    filterTags.forEach(tag => {
        tag.addEventListener('click', function() {
            // 檢查是否已經是 active 狀態
            const isCurrentlyActive = this.classList.contains('active');
            
            // 移除所有標籤的 active 狀態
            filterTags.forEach(t => t.classList.remove('active'));
            
            if (!isCurrentlyActive) {
                // 如果之前不是 active，則激活並填入文字
                this.classList.add('active');
                searchInput.value = this.textContent;
                window.filterchose = true; // 設置 filterchose 為 true
            } else {
                // 如果之前是 active，則清空搜索框
                searchInput.value = '';
                window.filterchose = false; // 設置 filterchose 為 false
            }
            
            // 觸發 input 事件
            const inputEvent = new Event('input', {
                bubbles: true,
                cancelable: true,
            });
            searchInput.dispatchEvent(inputEvent);
            
            // 讓搜索框獲得焦點
            searchInput.focus();
        });
    });
    
    // 監聽搜索框的 input 事件
    searchInput.addEventListener('input', function() {
        // 當手動輸入內容時，移除所有標籤的 active 狀態
        if (this.value === '') {
            filterTags.forEach(t => t.classList.remove('active'));
            window.filterchose = false;
        }
    });
}

function initSearch() {
    const searchButton = document.getElementById('search-bigbutton');
    const searchSpare = document.getElementById('search-spare');
    const searchInput = document.getElementById('search-input');
    const searchClose = document.getElementById('search-close');
    const searchLoading = document.querySelector('.search-loading');
    const searchContent = document.querySelector('.search-content');
    const filterTags = document.querySelectorAll('.filter-tag');

    document.getElementById("search-results-click").addEventListener("click", closeSearch);

    // 檢查必要元素是否存在
    if (!searchSpare || !searchInput || !searchContent || !searchLoading) {
        console.error('Required DOM element could not be found');
        return;
    }

    // 初始化 filter tags 功能
    filterTags.forEach(tag => {
        tag.addEventListener('click', function(e) {
            // 防止事件冒泡到 search-results-click
            e.stopPropagation();
            
            // 移除其他標籤的 active 狀態
            filterTags.forEach(t => t.classList.remove('active'));
            
            // 為當前標籤添加 active 狀態
            this.classList.add('active');
            
            // 設置搜尋輸入框的值
            searchInput.value = this.textContent;
            
            // 立即執行搜尋
            performSearch();
        });
    });

    // 打開搜尋框
    function openSearch() {
        searchSpare.style.display = 'flex';
        document.body.classList.add('locked');
        setTimeout(() => {
            searchSpare.style.opacity = '1';
            searchInput.focus();
        }, 10);
    }

    // 關閉搜尋框
    function closeSearch() {
        searchSpare.style.opacity = '0';
        document.body.classList.remove('locked');
        setTimeout(() => {
            searchSpare.style.display = 'none';
            searchInput.value = '';
            searchContent.innerHTML = '';
            // 清除所有標籤的 active 狀態
            filterTags.forEach(tag => tag.classList.remove('active'));
        }, 300);
    }

    // 顯示錯誤訊息
    function showError(message) {
        searchContent.innerHTML = `<div class="no-results">${message}</div>`;
        searchLoading.style.display = 'none';
    }

    // 獲取包含關鍵字的所有相關段落
    function getRelevantExcerpts(text, query, contextLength = 50) {
        if (!text || !query) return '';
        
        const lowerText = text.toLowerCase();
        const lowerQuery = query.toLowerCase();
        let excerpts = [];
        let lastIndex = 0;
        let index;
        
        // 尋找所有關鍵字出現的位置
        while ((index = lowerText.indexOf(lowerQuery, lastIndex)) !== -1) {
            // 計算截取的起始和結束位置
            let start = Math.max(0, index - contextLength);
            let end = Math.min(text.length, index + query.length + contextLength);
            
            // 檢查是否與前一個excerpt重疊
            if (excerpts.length > 0) {
                const lastExcerpt = excerpts[excerpts.length - 1];
                const lastEnd = lastExcerpt.end;
                if (start <= lastEnd) {
                    // 如果重疊，擴展前一個excerpt
                    excerpts[excerpts.length - 1].end = end;
                    lastIndex = index + query.length;
                    continue;
                }
            }
            
            // 添加新的excerpt
            excerpts.push({
                text: text.substring(start, end),
                start: start,
                end: end
            });
            
            lastIndex = index + query.length;
        }
        
        // 處理並合併所有excerpts
        return excerpts.map(excerpt => {
            let text = excerpt.text;
            if (excerpt.start > 0) text = '...' + text;
            if (excerpt.end < text.length) text = text + '...';
            return text;
        }).join('<br>');
    }

    // 高亮關鍵字
    function highlightText(text, query) {
        if (!text || !query) return text;
        // 轉義特殊字符，避免 XSS 攻擊
        const escapeRegExp = (string) => {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        };
        
        const regex = new RegExp(`(${escapeRegExp(query)})`, 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }

    // 搜尋功能
    async function performSearch() {
        const query = searchInput.value.trim();
        if (query.length < 1) {
            showError('Type your search keywords');
            return;
        }

        searchLoading.style.display = 'block';
        searchContent.innerHTML = '';

        try {
            const response = await fetch(`${BASE_PATH}/views/search/search.php?q=${encodeURIComponent(query)}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            if (!Array.isArray(data)) {
                throw new Error('Return data format error');
            }

            searchLoading.style.display = 'none';

            if (data.length === 0) {
                showError('No related results found');
                return;
            }

            const results = data.map(post => {
                if (!post.id || !post.dish_name) {
                    console.warn('Search results contain invalid data', post);
                    return '';
                }

                const activeTag = document.querySelector('.filter-tag.active'); 
            
                // 獲取包含關鍵字的相關段落
                const descriptionExcerpt = post.description 
                    ? getRelevantExcerpts(post.description, query)
                    : '';
                
                const eatingTipsExcerpt = post.eating_tips
                    ? getRelevantExcerpts(post.eating_tips, query)
                    : '';
            
                // 只有在找到匹配的內容時才顯示對應段落
                const descriptionHtml = descriptionExcerpt 
                    ? `<p class="description">Description: ${highlightText(descriptionExcerpt, query)}</p>`
                    : '';
            
                const eatingTipsHtml = eatingTipsExcerpt
                    ? `<p class="eating-tips">Eating Tips: ${highlightText(eatingTipsExcerpt, query)}</p>`
                    : '';
            
                return `
                    <div class="search-result-item">
                        <a href="${BASE_PATH}/views/classification/allpost.php#${encodeURIComponent(post.dish_name)}${post.id}">
                            <div class="search-result-image">
                                <div class="search-output-img" style="background-image: url('${BASE_PATH}/${post.image_path}')">
                                </div>
                            </div>
                            <div class="search-result-info">
                                <h3>${highlightText(post.dish_name, query)}</h3>
                                ${descriptionHtml}
                                ${eatingTipsHtml}
                                <p class="author">Post by: ${highlightText(post.username || 'Unknown', query)}</p>
                            </div>
                        </a>
                    </div>
                `;
            }).join('');
            
            searchContent.innerHTML = results;

        } catch (error) {
            console.error('Search error:', error);
            showError(`Search error: ${error.message}`);
        }
    }

    // 添加事件監聽器
    if (searchButton) searchButton.addEventListener('click', openSearch);
    if (searchClose) searchClose.addEventListener('click', closeSearch);
    
    // 添加搜尋輸入框的事件監聽器
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        // 當使用者手動輸入時，清除所有標籤的 active 狀態
        filterTags.forEach(tag => tag.classList.remove('active'));
        
        // 使用防抖處理搜尋
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 300);
    });
}



// 確保在點擊標籤時正確設置 filterchose
function setupFilterTags() {
    const filterTags = document.querySelectorAll('.filter-tag');
    filterTags.forEach(tag => {
        tag.addEventListener('click', function() {
            const isCurrentlyActive = this.classList.contains('active');
            filterTags.forEach(t => t.classList.remove('active'));
            
            if (!isCurrentlyActive) {
                this.classList.add('active');
                filterchose = true;
                console.log('標籤被選中，filterchose 設為:', filterchose);
            } else {
                filterchose = false;
                console.log('標籤取消選中，filterchose 設為:', filterchose);
            }
        });
    });
}



function checkFlexWrap() {
    const container = document.getElementById('index-button-area'); // 獲取父容器
    const children = container.children; // 獲取子容器（第一層子元素）
    const firstChild = children[0]; // 第一個子容器
    const secondChild = children[1]; // 第二個子容器
    // 判斷第二個子容器是否換行（透過 offsetTop 比較）
    if (secondChild.offsetTop > firstChild.offsetTop) {
        container.style.marginTop = '6%';
        introductiontop = 1.1;
    } else {
        container.style.marginTop = '5%';
        introductiontop = 1.4;
    }
}


// 初始化檢查
checkFlexWrap();

// 監聽螢幕大小調整，重新檢查
window.addEventListener('resize', () => {
    checkFlexWrap();
});

function introduction(){
    const introduction = document.querySelectorAll(".introduction");
    introduction.forEach(element =>{
        let elementHeight = parseFloat(getComputedStyle(element).height);
        element.style.top = - (introductiontop * elementHeight) + 'px';

    });
}


function indexButton() {
    const buttons = document.querySelectorAll(".index-button");
    const buttonArea = document.getElementById("index-button-area");
    const firstPart = document.getElementById("first-part");
    const secondPart = document.getElementById("second-part");
    const buttonIMG = document.querySelectorAll(".button-img");
    const text = document.querySelectorAll(".button-text");
        // 確保寬度有效，然後設置高度
        if (window.innerWidth < 850){

            firstPart.style.width = '100%';
            secondPart.style.width = '100%';
            firstPart.style.height = '47%';
            secondPart.style.height = '47%';
            
            document.getElementById("index-button-area").style.height ='60%';
            buttons.forEach(button => {
                // 獲取實際的寬度（使用 getComputedStyle）
                button.style.width = '45%';
            });
            buttonIMG.forEach ( element => {
                let Width = 80;
                element.style.height = 'auto';
                element.style.width = '80%';
                let buttonWidth = parseFloat(getComputedStyle(buttons[0]).width);
                let elementWidth = parseFloat(getComputedStyle(element).width);
                let buttonHeight = parseFloat(getComputedStyle(buttons[0]).height);
                let elementHeight = parseFloat(getComputedStyle(element).height);
                while (elementWidth + 15 > buttonWidth){
                    Width-- ;
                    element.style.width = `${Width}%`;
                    elementWidth = parseFloat(getComputedStyle(element).width);
                }
                if (elementHeight + 70  >  buttonHeight){
                    elementHeight = buttonHeight - 70;
                }
                element.style.width = `auto`;
                element.style.height = elementHeight +'px';

            })
            if (window.innerWidth < 400){
                text.forEach(element => {
                    element.style.fontSize = '4vw';
                })
            }
            else {
                text.forEach(element => {
                    element.style.fontSize = '2.5vw';
                })
            }
            
            
        }
        else{
            firstPart.style.width = '49%';
            secondPart.style.width = '49%';
            firstPart.style.height = '100%';
            secondPart.style.height = '100%';
            document.getElementById("index-button-area").style.height ='40%';
            buttons.forEach(button => {
                // 獲取實際的寬度（使用 getComputedStyle）
                button.style.width = '40%';
                
            });
            buttonIMG.forEach ( element => {
                element.style.width = '80%';
                element.style.height = 'auto';
            })
            text.forEach(element => {
                element.style.fontSize = '1.8vw';
            })
        }
    
    
}




function profileImgSize() {
    const BGImg = document.getElementById("header-img");
    BGImg.style.height = window.innerHeight + 'px';

    const computedHeight = parseFloat(getComputedStyle(header).height);
    const headerWhite = document.getElementById("headerWhite");

    // 使用 `getComputedStyle` 取得 BGImg 的實際寬度
    const BGImgWidth = getComputedStyle(BGImg).width;

    headerWhite.style.width = BGImgWidth;
    headerWhite.style.height = BGImg.style.height;
}


function backgroundChange(change = 0.3) {
    const headerWhite = document.getElementById("headerWhite");
    let speed = 0.0036;

    // 根據 change 的值調整 speed
    if (change > 0.7) {
        speed = 0.001;
    } else {
        speed = 0.0036;
    }

    // 當 change 小於 1 時執行動畫
    if (change < 1) {
        headerWhite.style.background = `linear-gradient(rgba(248, 217, 171, ${0.1+change * 0.35}), rgba(240, 236, 230, ${0.1+change * 0.45}),white)`;
        headerWhite.style.backgroundSize = "cover";
        headerWhite.style.backgroundPosition = "center 40%";
        // 增加 change
        change += speed;

        // 使用 requestAnimationFrame 繼續動畫，並傳遞更新後的 change
        requestAnimationFrame(() => backgroundChange(change));
    }
}


function setupHoverEffects() {
    // 選取所有的容器
    const containers = document.querySelectorAll('.dish_card');

    containers.forEach(container => {
        // 在每個容器內選取對應的元素
        const dishImg = container.querySelector('.dish_img');
        const connectLine = container.querySelector('.connectLine');
        const dishName = container.querySelector('.dish_name');

        // 定義滑鼠進入容器時的效果
        container.addEventListener('mouseenter', () => {
            dishImg.style.transform = 'scale(1.05)';
            dishName.style.fontSize = '22px';
            dishName.style.transform = 'translateY(7.5px)';
            // connectLine.style.transform = 'scale(1.1) translateY(10.5px)';
        });

        // 定義滑鼠離開容器時的效果
        container.addEventListener('mouseleave', () => {
            dishImg.style.transform = '';
            dishName.style.fontSize = '';
            dishName.style.transform = '';
            // connectLine.style.transform = '';
        });
    });
}

function food_marquee() {
    // 當視窗大小改變時，重新計算 translateValue
    window.addEventListener('resize', updateTranslateValue);
    window.addEventListener('resize', updateIMG);
    window.addEventListener('resize', updateSize);
    let translateValue;
    let currentTranslateX = 0; // 記錄當前的位移量
    var right_button = document.getElementById("right_button");
    var left_button = document.getElementById("left_button");
    let maxTranslateX = 0 ; //往右最大距離
    const titleTop = document.getElementById("marquee_title");
    let rightSpace = -17;

    updateIMG();
    updateTranslateValue();
    updateSize();
    function updateSize(){
        const el =document.querySelectorAll('.el');
        const row = document.querySelectorAll('.row');
        const food_marquee = document.getElementById('food_marquee');
        // 確保 titleTop 的高度值存在，並移除單位進行計算
        const computedStyle = getComputedStyle(titleTop);
        const titleheight = parseFloat(computedStyle.height); // 50
        const marginTop = parseFloat(computedStyle.marginTop)/2 || 0; // 10

        const titleHeight = titleheight + marginTop; // 60


        // 將計算後的高度值（帶單位）賦值給 food_marquee 的 marginTop
        // document.getElementById("NewPost").style.marginTop = `${titleHeight}px`;

        const height = parseFloat(getComputedStyle(food_marquee).height);
        el.forEach(element =>{
            element.style.height = (height ) / 37 +'px';
        })
        row.forEach(element =>{
            element.style.height = (height  ) / 37 +'px';
        })
        let changePiece = 13;
        for (var i = 0 ; i < changePiece ; i++){
            // linear-gradient(to bottom, rgba(255,255,255,1), rgba(248, 203, 144, 0.928));
            var k = i+1;
            el[i].style.background = `linear-gradient(to bottom, rgba(${255-(255-248)*i/changePiece},${255-(255-203)*i/changePiece},${255-(255-144)*i/changePiece},${1-(1-0.928)*i/changePiece}), rgba(${255-(255-248)*k/changePiece},${255-(255-203)*k/changePiece},${255-(255-144)*k/changePiece},${1-(1-0.928)*k/changePiece}))`;
        }
    }


    function updateIMG() {
        const food_marquee = document.getElementById('food_marquee');
        const dishCards = document.querySelectorAll('.dish_img');
    
        // 取得 food_marquee 的高度並轉換為數字
        const height = parseFloat(getComputedStyle(food_marquee).height);
    
        // 判斷高度的 80% 是否小於 500
        if (height * 0.66 < 500) {
            dishCards.forEach(element => {
                // 設置 dish_card 的高度
                element.style.height = (height * 0.66) + 'px';
                element.style.width = (height * 0.66 * 0.8) + 'px';
            });
        }


    }
    

    function updateTranslateValue() {
        const food_marquee = document.getElementById('food_marquee');
        const food_marqueeHeight = food_marquee.offsetHeight; // 獲取 food_marquee 的寬度
        const food_marqueeWidth = food_marquee.offsetWidth; // 獲取 food_marquee 的寬度
        const dishCards = document.querySelectorAll('.dish_card');
        
        let dishWidth = parseFloat(getComputedStyle(dishCards[0]).width);
        
        var right_button = document.getElementById("right_button");
        var left_button = document.getElementById("left_button");
        // right_button.style.width = 0.015 * food_marqueeWidth + 50 + 'px';
        // left_button.style.width = 0.015 * food_marqueeWidth + 50 + 'px';
        right_button.style.height = 0.1 *food_marqueeHeight +'px';
        left_button.style.height = 0.1 *food_marqueeHeight +'px';
        translateValue = dishWidth + food_marqueeWidth * marquee_gap; 
        window.addEventListener("resize", () => {})
        if (window.innerWidth < 500){
            dishCards[0].style.marginLeft = '10px';
            dishCards[dishCards.length-1].style.marginRight = '10px';
        }
        else{
            dishCards[0].style.marginLeft = '45px';
            dishCards[dishCards.length-1].style.marginRight = '45px'; 
        }
        if (window.innerWidth < 500){
            rightSpace = 55 ;
        }
        else{
            rightSpace = -17;
        }
        
    }

    // 初始化計算



    




    function moveRight() {
        const food_marquee = document.getElementById('food_marquee');
        const food_marqueeWidth = food_marquee.offsetWidth; 
        const dishCards = document.querySelectorAll('.dish_img');
        let dishWidth = parseFloat(getComputedStyle(dishCards[0]).width); // 確保是數值
        if (currentTranslateX - translateValue > -(dishWidth*(dishCards.length)+(food_marqueeWidth * marquee_gap*(dishCards.length-1))-window.innerWidth-17)){
            currentTranslateX -= translateValue; // 向右移動，位移量減少
            anime({
                targets: '#all_cards',
                translateX: currentTranslateX, // 基於累加的目標位置
                duration: 1500,
            });
        }
        else{
            
            let fake_currentTranslateX = -(dishWidth*(dishCards.length)+(food_marqueeWidth * marquee_gap*(dishCards.length-1))-window.innerWidth-rightSpace);
            if (maxTranslateX == 0 || currentTranslateX > maxTranslateX){
                currentTranslateX -= translateValue;
                maxTranslateX = currentTranslateX;


            }
            
            anime({
                targets: '#all_cards',
                translateX: fake_currentTranslateX-90, // 基於累加的目標位置
                duration: 1500,
            });
        }
        

    }

    function moveLeft() {
        const food_marquee = document.getElementById('food_marquee');
        const food_marqueeWidth = food_marquee.offsetWidth; 
        const dishCards = document.querySelectorAll('.dish_img');
        let dishWidth = parseFloat(getComputedStyle(dishCards[0]).width); // 確保是數值
        translateValue = dishWidth + food_marqueeWidth * marquee_gap; // 計算 350 + 4% 的值



        if (currentTranslateX + translateValue < 0){
            currentTranslateX += translateValue; // 向左移動，位移量增加
        }
        else{
            currentTranslateX = 0;
        }
        anime({
            targets: '#all_cards',
            translateX: currentTranslateX, // 基於累加的目標位置
            duration: 1500,
        });
        
    }

    // 綁定按鈕點擊事件
    right_button.addEventListener('click', moveRight);
    left_button.addEventListener('click', moveLeft);

    // 綁定鍵盤事件
    window.addEventListener('keydown', (event) => {
        if (event.key === "ArrowRight") { // 檢測是否按下右方向鍵
            moveRight();
        } else if (event.key === "ArrowLeft") { // 檢測是否按下左方向鍵
            moveLeft();
        }
    });
}




function weBelieve_detect(){     // 偵測文章已經滑到哪裡 然後執行動畫
    let show = false;
    window.addEventListener('scroll', () => {
        const col_distance_element = document.getElementById("NewPost-area");
        const windowHeight = window.innerHeight; // 視窗的高度
        const elementRect = col_distance_element.getBoundingClientRect(); //取得元素的位置
        const elementTop = Math.abs(elementRect.top); //取絕對值
        const threshold = 240; // 計算元素上方的像素數
        const pixelsAbove =  Math.abs(windowHeight - threshold);
        if (250 > elementTop && show == false){
            show = true;
            animaJs();
        }  
        }
    )};

function animaJs() {
// 第一個動畫
    anime.set('.dish_card', {
        translateY: 100  // 設置初始位置
    });
    // 第一個動畫
    anime({
        delay: 0,
        targets: '.el',
        width: '100%',
        delay: function (el, i) {
            return i * 15;
        },
        duration: 1500,
        easing: 'easeInOutQuad',
        complete: function () {
            // 後續動畫
            anime({
                targets: '.dish_card',
                translateY: [100, 0],  // 從 100px 移動到 0px
                opacity: [0, 1],       // 從 0 變到 1
                duration: 1800,
                delay: function (dish_card, i) {
                    return i * 650;
                },
                easing: 'easeInOutQuad'
            });
            anime({
                targets: '#marquee_title',
                translateY: [-50, 0],
                opacity: [0, 1],
                duration: 2500,
                easing: 'easeInOutQuad'
            });
            anime({
                targets: '.marquee_change_button',
                translateY: [50, 0],
                opacity: [0,0.5],
                duration: 2500,
                easing: 'easeInOutQuad'
            });
        }
    });
}

function preloader(){ //網頁讀取前動畫
    document.getElementById('preloader').style.background = `white url(${BASE_PATH}/img/logo_animation.webp?=${Math.random()}) no-repeat center`;
    disableScroll();
    setTimeout(enableScroll,5500);
    setTimeout(() => {
        document.getElementById("hidebody").style.visibility="visible";
        setTimeout(()=> {
            backgroundChange();
        },3000);
        // 開始圓形遮罩動畫
        anime({
            targets: '#preloader',
            clipPath: ['circle(150% at 50% 50%)', 'circle(0% at 50% 50%)'],
            duration: 2500,
            easing: 'easeInOutQuad',
            
            complete: function() {
                // 動畫完成後移除 GIF 層
                document.querySelector('#preloader').style.display = 'none';
                anime({
                    targets: '#header-logo',
                    translateY: [-50, 0],  
                    opacity: [0, 0.9],    
                    duration: 1800,
                    easing: 'easeInOutQuad',
                    complete: function(){
                        anime({
                            targets: '#header-line',
                            translateY: [-50, 0],  
                            opacity: [0, 0.9],    
                            duration: 1200,
                            easing: 'easeInOutQuad'

                        });
                        setTimeout(() => {
                            anime({
                                targets: '.index-button',
                                opacity: [0, 1],    
                                duration: 1000,
                                delay: function (el, i) {
                                    return i * 600;
                                },
                                easing: 'easeInOutQuad',
                                complete: indexNavbar()
                                
                            });
                        }, 500);
                    }
                });
                
            }
        });
    }, 3500);
}






// 禁止滾動的函數
function disableScroll() {
    document.addEventListener('wheel', preventDefault, { passive: false });
    document.addEventListener('touchmove', preventDefault, { passive: false });
}

// 啟用滾動的函數
function enableScroll() {
    document.removeEventListener('wheel', preventDefault);
    document.removeEventListener('touchmove', preventDefault);
}

// preventDefault 函數
function preventDefault(e) {
    e.preventDefault();
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