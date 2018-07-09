    var topFlag = true;
    const scrollToTop = () => {
        const c = document.documentElement.scrollTop || document.body.scrollTop;
        if(c > 0) {
            window.requestAnimationFrame(scrollToTop);
            window.scrollTo(0, c - c/8);
        }
    };
    window.onscroll = e => {
        const c = document.documentElement.scrollTop || document.body.scrollTop;
        if(c > 300 && topFlag) {
            const toTop = document.createElement('div');
            toTop.className = 'toTop';
            toTop.style.transition = "all .5s ease";
            toTop.innerHTML = `
                <button style="width: 5vh;height: 2vw;min-height:30px;background: #fff;border: 0;position: fixed;bottom: 2vw;right: 2vh;cursor:pointer" onclick="scrollToTop()">Top</button>
            `;
            document.body.appendChild(toTop);
            topFlag = false;
        } else if(c <= 300 && !topFlag) {
            const toTop = document.getElementsByClassName('toTop')[0];
            document.body.removeChild(toTop);
            topFlag = true;
        }
    }