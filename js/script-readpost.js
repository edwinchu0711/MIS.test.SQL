initNavbar();







function initNavbar() {

    // 檢查是否已經初始化

    if (window.navbarInitialized) ;

    window.navbarInitialized = true;

    post_detect();



}



function post_detect() { // 偵測文章已經滑到哪裡 然後執行動畫

    const handleScroll = () => {

        const col_distance_element = document.querySelectorAll(".post");

        const windowHeight = window.innerHeight; // 視窗的高度
        let threshold = 240; // 計算元素上方的像素數
        let showDistance = 1000;

        if (window.innerWidth < 1000) {
            showDistance *= 2.8;
            threshold = 350;
        }



        for (var i = 0; i < col_distance_element.length; i++) {

            const elementRect = col_distance_element[i].getBoundingClientRect(); // 取得元素的位置

            const elementTop = Math.abs(elementRect.top); // 取絕對值
            const elementBottom = Math.abs(elementRect.bottom); // 取絕對值


            const pixelsAbove = Math.abs(windowHeight - threshold);




            if ((pixelsAbove > elementTop  || elementBottom > threshold) && col_distance_element[i].classList.contains("hide_right")) {

                movein_animation(col_distance_element[i]);

                col_distance_element[i].classList.remove('hide_right');

                col_distance_element[i].classList.add('show_right');

            } else if (pixelsAbove > elementTop && col_distance_element[i].classList.contains("hide_left")) {

                movein_animation(col_distance_element[i]);

                col_distance_element[i].classList.remove('hide_left');

                col_distance_element[i].classList.add('show_left');

            } else if (showDistance < elementTop && col_distance_element[i].classList.contains("show_left")) {

                col_distance_element[i].classList.remove('show_left');

                col_distance_element[i].classList.add('hide_left');

            } else if (showDistance < elementTop && col_distance_element[i].classList.contains("show_right")) {

                col_distance_element[i].classList.remove('show_right');

                col_distance_element[i].classList.add('hide_right');

            }

        }

    };



    // 綁定滾動事件

    window.addEventListener('scroll', handleScroll);



    // 初次執行一次滾動邏輯

    handleScroll();

}





function movein_animation(element){ //淡入動畫

    let moveX = 50;

    let speed = 0.67 ;

    if (element.classList.contains("hide_right")){

        function right_animation(){

            if (moveX > 25 ){

                element.style.transform = `translateX(${moveX}px)`;

                element.style.opacity = `${(50-moveX)/50}`;

                moveX -= speed ;

                requestAnimationFrame(right_animation)

            }

            else if (moveX > 15 ){

                speed = 0.5 ;

                element.style.transform = `translateX(${moveX}px)`;

                element.style.opacity = `${(50-moveX)/50}`;

                moveX -= speed ;

                requestAnimationFrame(right_animation)

            }

            else if (moveX > 7 ){

                speed = 0.3;

                element.style.transform = `translateX(${moveX}px)`;

                element.style.opacity = `${(50-moveX)/50}`;

                moveX -= speed ;

                requestAnimationFrame(right_animation)

            }

            else if (moveX > 0 ){

                speed = 0.18;

                element.style.transform = `translateX(${moveX}px)`;

                element.style.opacity = `${(50-moveX)/50}`;

                moveX -= speed ;

                requestAnimationFrame(right_animation)

            }

    

        }

        right_animation()

    }

    else if(element.classList.contains("hide_left")){

        moveX *= -1 ;

        function left_animation(){

            if (-moveX > 25 ){

                element.style.transform = `translateX(${moveX}px)`;

                element.style.opacity = `${(50+moveX)/50}`;

                moveX += speed ;

                requestAnimationFrame(left_animation)

            }

            else if (-moveX > 15 ){

                speed = 0.5 ;

                element.style.transform = `translateX(${moveX}px)`;

                element.style.opacity = `${(50+moveX)/50}`;

                moveX += speed ;

                requestAnimationFrame(left_animation)

            }

            else if (-moveX > 7 ){

                speed = 0.3;

                element.style.transform = `translateX(${moveX}px)`;

                element.style.opacity = `${(50+moveX)/50}`;

                moveX += speed ;

                requestAnimationFrame(left_animation)

            }

            else if (-moveX > 0 ){

                speed = 0.18;

                element.style.transform = `translateX(${moveX}px)`;

                element.style.opacity = `${(50+moveX)/50}`;

                moveX += speed ;

                requestAnimationFrame(left_animation)

            }

    

        }

        left_animation()

    }

    

}