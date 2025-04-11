var currentHeight = 0 ;

var oldBG = false ;

var OldImg = false ;

if (typeof BASE_PATH == 'undefined') {
    var BASE_PATH = window.location.origin;
} 



preloader();

function pageload() {

    user_detect();  

    userList();

    settingButton();

    changeIMGarea();

    uploadIMGchange();

    toggleDividingLineOnWrap();

    setupPhotoCompareResizing();

    uploadBGchange();

    BGchangeIMGarea();

    setupBGPhotoCompareResizing();

    window.addEventListener("resize",()=>{
        setupBGPhotoCompareResizing();
        setupPhotoCompareResizing();    
    })

}





function userList(){ // 此function可以使滑鼠滑到user按鈕時會延伸出更多list

    var user_list = document.querySelectorAll(".user-list");

    var user_button = document.getElementById("user-button");

    var buttonWidth = parseFloat(window.getComputedStyle(user_button).width); //取得user button 的寬

    var buttonHeight = parseFloat(window.getComputedStyle(user_button).height); //取得user button 的高

    

    user_list.forEach(element => {

        var elementWidth = parseFloat(window.getComputedStyle(element).width);

        if (elementWidth < buttonWidth){

        element.style.width = buttonWidth; //設定每個list的寬和user button一樣高

        

        }

        element.style.marginTop = parseInt(buttonHeight) + "px"; //設定list 的預設位置在user底下 利用marginTop

        

       

    });

}



function userList_ani(){ //將user_List 打開

    var user_list = document.getElementById("user-list");

    user_list.classList.add('show');

}



function re_userList_ani(){ //將user_List 收起

    var user_list = document.getElementById("user-list");

    user_list.classList.remove('show');

}



function user_detect(){ //偵測滑鼠

    var user_area = document.getElementById("user-button");

    var user_button= document.getElementById("user-button-area"); 

    user_button.addEventListener("mouseover", userList_ani ); 

    user_area.addEventListener("mouseenter", userList_ani);

    user_button.addEventListener("mouseleave", re_userList_ani);

    user_area.addEventListener("mouseleave", re_userList_ani);

}









function preloader(){ //網頁讀取前動畫

    // preloader

    // window.addEventListener('load', function() {

    //     setTimeout(function() {

    //         let body = document.querySelectorAll(".inBody");

    //         body.array.forEach(element => {

    //             element.style.opacity = '1' ;

    //         });

    //     }, 2000); // 1秒後顯示文字

    // });

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



function settingButton() { //settinglist的按鈕

    let buttons = document.querySelectorAll(".setting-button"); // 選取所有帶有 class "setting-button" 的按鈕

    let nowShow = document.getElementById("profile-setting"); // 預設顯示的設定區域

    let nowShowButton = document.getElementById("profile"); // 預設顯示的按鈕





    buttons.forEach(element => {

        element.addEventListener('click', () => {

            // 檢查 nowShowButton 是否存在，避免操作 undefined

            buttons.forEach(button => {

                button.style.background = 'none';

            });



            // 更新目前顯示的按鈕和設定區域

            nowShowButton = element; // 將目前按下的按鈕設為 nowShowButton

            nowShow = document.getElementById(`${element.id}-setting`); // 根據按鈕 ID 找到對應的設定區域



            // 檢查 nowShowButton 和 nowShow 是否存在

            if (nowShowButton) {

                nowShowButton.style.background = '#E6B566'; // 更新新按鈕的背景樣式

            }



            if (nowShow) {

                // 顯示新的設定區域，並隱藏其他設定區域

                document.querySelectorAll('.setting-content').forEach(area => {

                    area.style.display = 'none'; // 隱藏所有設定區域

                });

                nowShow.style.display = 'flex'; // 顯示當前設定區域

            }

        });

    });

}







function BGchangeIMGarea(){//背景圖片上傳介面

    let IMG = document.getElementById("Background-Container");

    let exitButton = document.getElementById("BGexit");

    let ChangePage = document.getElementById("BGchangeIMG");

    const fileInput = document.getElementById('BGIMGbutton');

    const imageElement = document.getElementById('NewBGImg');

    const defaultImagePath = imageElement.src;

    IMG.addEventListener('click', () => {

        ChangePage.style.display = 'flex';

    });

    exitButton.addEventListener('click', () => {

        // const fileInput = document.getElementById('profileIMGbutton');

        // const profilePreview = document.getElementById('NewprofileImg');

        // fileInput.value = "";

        // profilePreview.src = defaultImagePath;

        ChangePage.style.display = 'none';

        fileInput.value = "";

        const profilePreview = document.getElementById('NewBGImg');

        profilePreview.style.backgroundImage = 'url(' + BASE_PATH + 'img/unknow_BG.webp)';

        

        

    });



}



function setupBGPhotoCompareResizing() {

    // 選取 DOM 元素

    const photoCompareContainer = document.getElementById("BGphoto-compare");

    const oldProfileImg = document.getElementById("OldBGImg");

    const newProfileImg = document.getElementById("NewBGImg");

    const photoArrow = document.getElementById("BG-arrow");

    const compareDivs = document.querySelectorAll(".BGcompare");



    // 初始設置 photo-compare 容器樣式

    Object.assign(photoCompareContainer.style, {

        display: "flex",
        flexDirection: "column",

        justifyContent: "center",

        alignItems: "center",

        gap: "10px", // 元素間距

        padding: "10px 0 10px 0",

        margin: "0 auto",

        borderRadius: "8px",

        maxWidth: "90%",

        overflow: "hidden",

    });



    // 初始設置 compare 子容器樣式

    compareDivs.forEach(compare => {

        Object.assign(compare.style, {

            display: "flex",

            flexDirection: "row",

            justifyContent: "center", //////BG//////////

            alignItems: "center",

            textAlign: "center",

        });

    });



    // 初始設置圖片樣式

    const setImageStyles = (imgElement) => {

        Object.assign(imgElement.style, {

            width: "500px",

            height: "200px",

            objectFit: "cover",

            border: "2px solid #ccc",

            boxShadow: "0 2px 8px rgba(0, 0, 0, 0.1)",

            transition: "transform 0.3s ease",

        });

    };



    setImageStyles(oldProfileImg);

    setImageStyles(newProfileImg);



    // 初始設置箭頭樣式

    // Object.assign(photoArrow.style, {

    //     width: "50px",

    //     height: "50px",

    //     objectFit: "contain",

    //     transition: "transform 0.3s ease",

    // });



    // 動態調整大小函數

    function adjustSize() {

        const windowWidth = window.innerWidth; // 視窗寬度

        const containerWidth = Math.min(windowWidth * 0.9, 950); // 最大寬度 950px



        // 調整容器寬度

        photoCompareContainer.style.width = `${containerWidth}px`;



        const imageSize = Math.max(containerWidth * 0.47-250, 85); // 圖片大小不超過容器寬度的 20%

        oldProfileImg.style.width = `${imageSize*2}px`;

        oldProfileImg.style.height = `${imageSize*0.8}px`;

        newProfileImg.style.width = `${imageSize*2}px`;

        newProfileImg.style.height = `${imageSize*0.8}px`;



        // 調整箭頭大小

        // const arrowSize = Math.min(containerWidth * 0.05, 50); // 箭頭大小不超過容器寬度的 5%

        // photoArrow.style.width = `${arrowSize}px`;

        // photoArrow.style.height = `${arrowSize}px`;

    }

    // 初始化大小

    adjustSize();

    // 當視窗大小改變時，動態調整

    window.addEventListener("resize", adjustSize);



}



function uploadBGchange(){

    // 獲取文件上傳按鈕和圖片預覽區域

    const fileInput = document.getElementById('BGIMGbutton');

    const profilePreview = document.getElementById('NewBGImg');

    const submitButton = document.getElementById('changeBGbutton');

    // 當用戶選擇文件時觸發事件

    fileInput.addEventListener('change', function () {

        var file = this.files[0]; // 獲取選中的文件

        

        if (file) {

            const reader = new FileReader(); // 創建 FileReader 來讀取文件

            

            // 當文件讀取完成時執行

            reader.readAsDataURL(file); // 開始讀取文件內容，觸發 onload

            reader.onload = function (e) {

                profilePreview.style.backgroundImage = 'url(' + e.target.result + ')';

            };



            

        }

    });

    submitButton.addEventListener('click', () => {

        var file = fileInput.files[0];

        let ChangePage = document.getElementById("BGchangeIMG");

        const settingIMG = document.getElementById("backgroundContainer");

        if (file){

            ChangePage.style.display = 'none';

            const reader = new FileReader(); // 創建 FileReader 來讀取文件

            reader.readAsDataURL(file); // 開始讀取文件內容，觸發 onload

            // 當文件讀取完成時執行

            reader.onload = function (e) {

                settingIMG.style.backgroundImage = 'url(' + e.target.result + ')';

            };         

        }

        else{

            alert("Upload a image.");

        }



        

        

    });





    var windowSize = document.getElementById("changeIMG");

    function updateChildSize() {

      const bodyWidth = document.body.offsetWidth;

      const bodyHeight = document.body.offsetHeight;



      windowSize.style.width = `${bodyWidth}px`;

      windowSize.style.height = `${bodyHeight}px`;

      windowSize.style.top = 0;

      windowSize.style.left = 0;

    }



    // 初始化大小

    updateChildSize();



    // 當視窗大小改變時更新

    window.addEventListener('resize', updateChildSize);



}













function changeIMGarea(){//圖片上傳介面

    let IMG = document.getElementById("ImgContianer");

    let exitButton = document.getElementById("exit");

    let ChangePage = document.getElementById("changeIMG");

    const imageElement = document.getElementById('NewprofileImg');

    const defaultImagePath = imageElement.src;

    IMG.addEventListener('click', () => {



        ChangePage.style.display = 'flex';

    });

    exitButton.addEventListener('click', () => {

        const fileInput = document.getElementById('profileIMGbutton');

        const profilePreview = document.getElementById('NewprofileImg');

        

        profilePreview.style.backgroundImage = 'url(' + BASE_PATH + 'img/unknow.webp)';

        ChangePage.style.display = 'none';

        fileInput.value = "";

        

        

    });



}



function uploadIMGchange(){

    // 獲取文件上傳按鈕和圖片預覽區域

    const fileInput = document.getElementById('profileIMGbutton');

    const profilePreview = document.getElementById('NewprofileImg');

    const submitButton = document.getElementById('changeIMGbutton');

    // 當用戶選擇文件時觸發事件

    fileInput.addEventListener('change', function () {

        var file = this.files[0]; // 獲取選中的文件

        

        if (file) {

            const reader = new FileReader(); // 創建 FileReader 來讀取文件

            

            // 當文件讀取完成時執行

            reader.readAsDataURL(file); // 開始讀取文件內容，觸發 onload

            reader.onload = function (e) {

                profilePreview.style.backgroundImage = 'url(' + e.target.result + ')';

            };



            

        }

    });

    submitButton.addEventListener('click', () => {

        var file = fileInput.files[0];

        let ChangePage = document.getElementById("changeIMG");

        const settingIMG = document.getElementById("profileImg");

        if (file){

            ChangePage.style.display = 'none';

            const reader = new FileReader(); // 創建 FileReader 來讀取文件

            reader.readAsDataURL(file); // 開始讀取文件內容，觸發 onload

            // 當文件讀取完成時執行

            reader.onload = function (e) {

                settingIMG.style.backgroundImage = 'url(' + e.target.result + ')';



            };         

        }

        else{

            alert("Upload a image.");

        }



        

        

    });





    var windowSize = document.getElementById("changeIMG");

    function updateChildSize() {

      const bodyWidth = document.body.offsetWidth;

      const bodyHeight = document.body.offsetHeight;



      windowSize.style.width = `${bodyWidth}px`;

      windowSize.style.height = `${bodyHeight}px`;

      windowSize.style.top = 0;

      windowSize.style.left = 0;

    }



    // 初始化大小

    updateChildSize();



    // 當視窗大小改變時更新

    window.addEventListener('resize', updateChildSize);



}











function setupPhotoCompareResizing() {

    // 選取 DOM 元素

    const photoCompareContainer = document.getElementById("photo-compare");

    const oldProfileImg = document.getElementById("OldprofileImg");

    const newProfileImg = document.getElementById("NewprofileImg");

    const photoArrow = document.getElementById("photo-arrow");

    const compareDivs = document.querySelectorAll(".compare");



    // 初始設置 photo-compare 容器樣式

    Object.assign(photoCompareContainer.style, {

        display: "flex",

        justifyContent: "space-around",

        alignItems: "center",

        gap: "20px", // 元素間距

        padding: "20px",

        margin: "0 auto",

        borderRadius: "8px",

        maxWidth: "90%",

        overflow: "hidden",

    });



    // 初始設置 compare 子容器樣式

    compareDivs.forEach(compare => {

        Object.assign(compare.style, {

            display: "flex",

            flexDirection: "column",

            justifyContent: "center",

            alignItems: "center",

            textAlign: "center",

        });

    });



    // 初始設置圖片樣式

    const setImageStyles = (imgElement) => {

        Object.assign(imgElement.style, {

            width: "150px",

            height: "150px",

            borderRadius: "50%",

            objectFit: "cover",

            border: "2px solid #ccc",

            boxShadow: "0 2px 8px rgba(0, 0, 0, 0.1)",

            transition: "transform 0.3s ease",

        });

    };



    setImageStyles(oldProfileImg);

    setImageStyles(newProfileImg);



    // 初始設置箭頭樣式

    Object.assign(photoArrow.style, {

        width: "50px",

        height: "50px",

        objectFit: "contain",

        transition: "transform 0.3s ease",

    });



    // 動態調整大小函數

    function adjustSize() {

        const windowWidth = window.innerWidth; // 視窗寬度

        const containerWidth = Math.min(windowWidth * 0.9, 800); // 最大寬度 800px



        // 調整容器寬度

        photoCompareContainer.style.width = `${containerWidth}px`;



        // 調整圖片大小

        const imageSize = Math.min(containerWidth * 0.2, 150); // 圖片大小不超過容器寬度的 20%

        oldProfileImg.style.width = `${imageSize}px`;

        oldProfileImg.style.height = `${imageSize}px`;

        newProfileImg.style.width = `${imageSize}px`;

        newProfileImg.style.height = `${imageSize}px`;



        // 調整箭頭大小

        const arrowSize = Math.min(containerWidth * 0.05, 50); // 箭頭大小不超過容器寬度的 5%

        photoArrow.style.width = `${arrowSize}px`;

        photoArrow.style.height = `${arrowSize}px`;

    }

    // 初始化大小

    adjustSize();

    // 當視窗大小改變時，動態調整

    window.addEventListener("resize", adjustSize);



}







function toggleDividingLineOnWrap() {

    const settingArea = document.getElementById("setting-area");

    const dividingLine = document.getElementById("dividingLine");

    const dividingLine_wide = document.getElementById("dividingLine_wide");

    const settingList = document.getElementById("setting-button-area");

    const settingListArea = document.getElementById("setting-list");

    const settingcontent = document.getElementById("setting-content");

    const settingcontents = document.querySelectorAll('.setting-content');

    const settingButton = document.querySelectorAll('.setting-button');

    const inputSpace =  document.querySelectorAll('.input-space');



    if (!settingArea || !dividingLine) {

        return;

    }

    function checkWrap() {

        const children = Array.from(settingArea.children);

        let isWrapped = false;

        // 強制觸發瀏覽器重排，確保布局最新

        settingArea.offsetHeight;

        const prevRect = children[0].getBoundingClientRect();

        const currRect = children[children.length - 1].getBoundingClientRect(); 

           // 判斷是否換行

        if (currRect.top > prevRect.top) {

            isWrapped = true;

        }

        // 根據檢測結果隱藏或顯示 dividingLine

        if (isWrapped == true){

            dividingLine.style.visibility = 'hidden';

            dividingLine_wide.style.display = 'block';

            dividingLine.style.height = '0px';

            settingList.style.setProperty('flex-direction', 'row');

            settingListArea.style.width = '20%';

            settingListArea.style.alignItems = 'center' ;

            settingcontent.style.marginRight = '70px';

            settingcontent.style.paddingTop = '20px';



            settingButton.forEach (button => {

                button.style.justifyContent = 'center';

            })

            settingcontents.forEach (element =>{

                element.style.paddingTop = '30px';

            })

            // inputSpace.forEach (element =>{

            //     element.style.marginLeft = '0px';

            // })





        }

        else{

            dividingLine.style.visibility = 'visible';

            dividingLine_wide.style.display = 'none';

            dividingLine.style.height = '90%';

            settingList.style.flexDirection = 'column';

            settingList.style.setProperty('flex-direction', 'column');

            settingListArea.style.width = '20%';

            settingListArea.style.alignItems = 'flex-end' ;

            settingcontent.style.marginRight = '0px';

            settingButton.forEach (button => {

                button.style.justifyContent = 'left';

            })

            settingcontents.forEach (element =>{

                element.style.paddingTop = '0px';

            })

            // inputSpace.forEach (element => {

            //     element.style.marginLeft = 'auto';

            // })





        }

        // dividingLine.style.display = isWrapped ? "none" : "block";

    }



    // 初次檢查

    checkWrap();



    // 監聽窗口大小變化

    window.addEventListener("resize", () => {

        checkWrap();

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