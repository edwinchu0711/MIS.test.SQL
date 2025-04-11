// 全局變數
if (typeof BASE_PATH === 'undefined') {
    var BASE_PATH = window.location.origin;
}

let menupreviwRUN = false;

var smalluserlist = document.getElementById("small-user-list");
var smallmenulist = document.getElementById("small-menu-list");
var smallTypeList = document.getElementById('small-type-list');

function initNavbar() {
    // 檢查是否已經初始化
    window.navbarInitialized = true;
    menuList();
    menu_detect();
    userList();
    user_detect();
    typeList();
    type_detect();
    menuImgAnimation(); 
    while (menupreviwRUN == false){
        menupreview();
    }

    // 搜尋功能初始化
    initSearch();

    // 原有的事件監聽器
    document.getElementById("more-list").addEventListener("click", SmallListBtn);
    if (document.getElementById("small-menu")) {
        document.getElementById("small-menu").addEventListener("click", SmallMenuList);
    }
    
    if (document.getElementById("small-user-button")) {
        document.getElementById("small-user-button").addEventListener("click", SmallUserList);
    }
    if (document.getElementById("small-type-button")) {
        document.getElementById("small-type-button").addEventListener("click", SmalltypeList);
    }
    if (document.getElementById("small-menu-list")) {
        document.getElementById("small-menu-list").addEventListener("click", function(e) {
            e.stopPropagation();
        });
    }
    
    document.getElementById("small-type-list").addEventListener("click", function(e) {
        e.stopPropagation();
    });
    if (document.getElementById("small-user-list")) {
        document.getElementById("small-user-list").addEventListener("click", function(e) {
            e.stopPropagation();
        });
    }

    
}







// 搜尋功能初始化
function initSearch() {
    const searchButton = document.getElementById('search-button');
    const smallSearchButton = document.getElementById('small-search-button');
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
    if (smallSearchButton) smallSearchButton.addEventListener('click', openSearch);
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



// 確保 DOM 完全加載後再初始化
document.addEventListener('DOMContentLoaded', initSearch);



/************************縮小後的List */
function SmallListBtn() {
    var smallList = document.getElementById("small-list");
    smallList.classList.toggle("show");
}

function SmallMenuList() {
    smallmenulist.classList.toggle("show");
    if (smallmenulist.matches('.show')) {
        smallmenulist.style.opacity = '1';
        if (smallTypeList) {
            smallTypeList.classList.remove('show');
            setTimeout(() => {
                smallTypeList.style.opacity = '0';
            }, 300);
        }
        if (document.getElementById("small-user-button")) {
            smalluserlist.classList.remove('show');
            setTimeout(() => {
                smalluserlist.style.opacity = '0';
            }, 300);
        }
    } else {
        setTimeout(() => {
            smallmenulist.style.opacity = '0';
        }, 300);
    }
}

function SmallUserList() {
    smalluserlist.classList.toggle("show");
    if (smalluserlist.matches('.show')) {
        smalluserlist.style.opacity = '1';
        if (smallTypeList) {
            smallTypeList.classList.remove('show');
            setTimeout(() => {
                smallTypeList.style.opacity = '0';
            }, 300);
        }
        if (smallmenulist) {
            smallmenulist.classList.remove('show');
            setTimeout(() => {
                smallmenulist.style.opacity = '0';
            }, 300);
        }
    } else {
        setTimeout(() => {
            smalluserlist.style.opacity = '0';
        }, 300);
    }
}

function SmalltypeList() {
    if (smallTypeList.matches('.show')) {
        smallTypeList.classList.remove('show');
        setTimeout(() => {
            smallTypeList.style.opacity = '0';
        }, 300);
    } else {
        smallTypeList.classList.add('show');
        setTimeout(() => {
            smallTypeList.style.opacity = '1';
        }, 0);
        if (smallmenulist) {
            smallmenulist.classList.remove('show');
            setTimeout(() => {
                smallmenulist.style.opacity = '0';
            }, 300);
        }
        if (document.getElementById("small-user-button")) {
            smalluserlist.classList.remove('show');
            setTimeout(() => {
                smalluserlist.style.opacity = '0';
            }, 300);
        }
    }
}

/*****************************圖片跑出 */
function menuImgAnimation() {
    const menuItems = document.querySelectorAll('.w3-bar-item');

    menuItems.forEach(parent => {
        const hoverImage = parent.querySelector('.hover-image');
        
        if (hoverImage) {
            // 當滑鼠進入時觸發
            parent.addEventListener("mouseenter", () => {
                hoverImage.style.display = "flex";
                hoverImage.style.opacity = "1";
            });

            // 當滑鼠離開時觸發
            parent.addEventListener("mouseleave", () => {
                hoverImage.style.opacity = "0";
                setTimeout(() => {
                    hoverImage.style.display = "none";
                }, 300);
            });

            // 當滑鼠移動時觸發
            parent.addEventListener("mousemove", (e) => {
                const offsetX = e.clientX;
                const offsetY = e.clientY;
                const isNoodleSteakLatte = parent.classList.contains('noodle') || 
                                         parent.classList.contains('steak') || 
                                         parent.classList.contains('latte');

                hoverImage.style.position = "fixed";
                hoverImage.style.left = `${offsetX - hoverImage.width}px`;
                hoverImage.style.top = isNoodleSteakLatte ? 
                                     `${offsetY - hoverImage.height}px` : 
                                     `${offsetY}px`;
            });

            // 新增點擊事件處理邏輯
            parent.addEventListener("click", () => {
                // 隱藏所有其他的 hover-image
                document.querySelectorAll('.hover-image').forEach(image => {
                    if (image !== hoverImage) {
                        image.style.display = "none";
                        image.style.opacity = "0";
                    }
                });

                // 顯示當前的 hover-image
                hoverImage.style.display = "flex";
                hoverImage.style.opacity = "1";
            });
        }
    });
}


///////////////////////***********************************user的部分 */
function userList() {
    var user_list = document.querySelectorAll(".user-list");
    var user_button = document.getElementById("user-button");

    function userHeight() {
        if (user_button) {
            var buttonWidth = parseFloat(window.getComputedStyle(user_button).width);
            var buttonHeight = parseFloat(window.getComputedStyle(user_button).height);
            user_list.forEach(element => {
                var elementWidth = parseFloat(window.getComputedStyle(element).width);
                if (elementWidth < buttonWidth) {
                    element.style.width = buttonWidth + "px";
                }
                element.style.marginTop = buttonHeight + "px";
            });
        }
    }
    window.addEventListener("resize", userHeight);
    userHeight();
}

function userList_ani() {
    var user_list = document.getElementById("user-list");
    if (user_list) {
        user_list.classList.add('show');
    }
}

function re_userList_ani() {
    var user_list = document.getElementById("user-list");
    if (user_list) {
        user_list.classList.remove('show');
    }
}

function user_detect() {
    var user_area = document.getElementById("user-button");
    var user_button = document.getElementById("user-button-area");

    if (user_button && user_area) {
        user_button.addEventListener("mouseover", userList_ani);
        user_area.addEventListener("mouseenter", userList_ani);
        user_button.addEventListener("mouseleave", re_userList_ani);
        user_area.addEventListener("mouseleave", re_userList_ani);
    }
}

///////////////////****************************************menu 的部分 */
function menuList() {
    var menu_list = document.querySelectorAll(".menu-list");
    var menu_button = document.getElementById("menu-button");

    if (!menu_button) {
        return;
    }

    function menuheight() {
        var buttonWidth = parseFloat(window.getComputedStyle(menu_button).width);
        var buttonHeight = parseFloat(window.getComputedStyle(menu_button).height);
        menu_list.forEach(element => {
            var elementWidth = parseFloat(window.getComputedStyle(element).width);
            if (elementWidth < buttonWidth) {
                element.style.width = buttonWidth + "px";
            }
            element.style.marginTop = buttonHeight + "px";
        });
    }
    window.addEventListener("resize", menuheight);
    menuheight();
}

function menuList_ani() {
    var menu_list = document.getElementById("menu-list");
    if (menu_list) {
        menu_list.classList.add('show');
    }
}

function re_menuList_ani() {
    var menu_list = document.getElementById("menu-list");
    if (menu_list) {
        menu_list.classList.remove('show');
    }
}

function menu_detect() {
    var menu_area = document.getElementById("menu-button");
    var menu_button = document.getElementById("menu-button-area");

    if (menu_button && menu_area) {
        menu_button.addEventListener("mouseover", menuList_ani);
        menu_area.addEventListener("mouseenter", menuList_ani);
        menu_button.addEventListener("mouseleave", re_menuList_ani);
        menu_area.addEventListener("mouseleave", re_menuList_ani);
    }
}

///////////////////****************************************type 的部分 */
function typeList() {
    var type_list = document.querySelectorAll(".type-list");
    var type_button = document.getElementById("type-button");

    if (!type_button) {
        console.error("type button not found");
        return;
    }

    function typeheight() {
        var buttonWidth = parseFloat(window.getComputedStyle(type_button).width);
        var buttonHeight = parseFloat(window.getComputedStyle(type_button).height);
        type_list.forEach(element => {
            var elementWidth = parseFloat(window.getComputedStyle(element).width);
            if (elementWidth < buttonWidth) {
                element.style.width = buttonWidth + "px";
            }
            element.style.marginTop = buttonHeight + "px";
        });
    }
    window.addEventListener("resize", typeheight);
    typeheight();
}

function typeList_ani() {
    var type_list = document.getElementById("type-list");
    if (type_list) {
        type_list.classList.add('show');
    }
}

function re_typeList_ani() {
    var type_list = document.getElementById("type-list");
    if (type_list) {
        type_list.classList.remove('show');
    }
}

function type_detect() {
    var type_area = document.getElementById("type-button");
    var type_button = document.getElementById("type-button-area");

    if (type_button && type_area) {
        type_button.addEventListener("mouseover", typeList_ani);
        type_area.addEventListener("mouseenter", typeList_ani);
        type_button.addEventListener("mouseleave", re_typeList_ani);
        type_area.addEventListener("mouseleave", re_typeList_ani);
    }
}

/******************標題logo超連結 */
function backtoindex() {
    window.location.href = "/";
}
function menupreview() {
    menupreviwRUN = true;
    const toggleButtons = document.querySelectorAll('.toggle-preview');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            // 阻止默認行為和事件冒泡
            event.preventDefault();
            event.stopPropagation();

            // 找到與該按鈕相對應的圖片預覽區域
            const imagePreview = button.closest('a').nextElementSibling;

            // 確保圖片預覽區域存在
            if (!imagePreview || !imagePreview.classList.contains('image-preview')) {
                console.error('未找到對應的圖片預覽區域');
                return;
            }

            // 使用 getComputedStyle 判斷圖片預覽的顯示狀態
            const isVisible = window.getComputedStyle(imagePreview).display !== 'none';

            // 隱藏所有其他圖片預覽區域並重置所有按鈕
            const allImagePreviews = document.querySelectorAll('.image-preview');
            allImagePreviews.forEach(preview => {
                preview.style.display = 'none'; // 隱藏
            });
            toggleButtons.forEach(btn => {
                btn.innerHTML = '&#8744;'; // 向下箭頭
            });

            // 切換當前按鈕和圖片的顯示狀態
            if (isVisible) {
                imagePreview.style.display = 'none'; // 隱藏當前圖片
                button.innerHTML = '&#8744;'; // 向下箭頭
            } else {
                imagePreview.style.display = 'block'; // 顯示當前圖片
                button.innerHTML = '&#8743;'; // 向上箭頭
            }
        });
    });
}



// 頁面載入時初始化
document.addEventListener('DOMContentLoaded', function() {
    if (!window.navbarInitialized) {
        initNavbar();
    }
});
