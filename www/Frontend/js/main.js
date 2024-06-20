

document.addEventListener("DOMContentLoaded", function() 
{ // ON LOAD

   
    ASOinit();
    fixedHeader();
   // fixedFooter() 
    slideCarousel(10000);
    counter();
    accordition();
    accordition_setDetailHeight (); 
    expandBoxes();
    lightbox();
    scrollSpy();
    smoothAnchorScroll();

    cookiesConsent();

}); 


window.addEventListener("resize", function () 
{ // ON RESIZE

    fixedHeader();
   // fixedFooter();

});



// AOS init
function ASOinit() {
    AOS.init({
        offset: 120,
        delay: 100,
        duration: 1000,
        easing: 'ease-out',
        once: true,
        anchorPlacement: 'top-bottom',
    });
}


function lightbox() {
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxTitle = document.getElementById('lightbox-title');
    const closeBtn = document.getElementById('lightbox-close');
    const prevBtn = document.getElementById('lightbox-prev');
    const nextBtn = document.getElementById('lightbox-next');
    const thumbnails = [...document.querySelectorAll('.foto img')];
    let currentIndex = 0;

    const showLightbox = (index) => {
        lightbox.classList.add('active');
        lightboxImg.src = thumbnails[index].src;
        lightboxTitle.textContent = thumbnails[index].title || '';
        currentIndex = index;
    };

    const hideLightbox = () => {
        lightbox.classList.remove('active');
        lightboxImg.src = '';
        lightboxTitle.textContent = '';
    };

    const handleKeydown = (e) => {
        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            e.preventDefault();
            currentIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
            showLightbox(currentIndex);
        } else if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % thumbnails.length;
            showLightbox(currentIndex);
        } else if (e.key === 'Escape') {
            hideLightbox();
        }
    };

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', () => showLightbox(index));
    });

    closeBtn.addEventListener('click', hideLightbox);
    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + thumbnails.length) % thumbnails.length;
        showLightbox(currentIndex);
    });
    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % thumbnails.length;
        showLightbox(currentIndex);
    });

    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            hideLightbox();
        }
    });

    document.addEventListener('keydown', handleKeydown);
}




let scrollspy_offset = 140;

function scrollSpy()
{
    const sections = document.querySelectorAll('.anchor');
    const navLi = document.querySelectorAll('nav ul#mainMenu li');

    window.addEventListener('scroll', () => {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.getBoundingClientRect().top + window.pageYOffset;
            let scrollspy_offset2 = scrollspy_offset; 

            if (section.getAttribute("id")) {
                scrollspy_offset2 = document.querySelector('#mainMenu a[href="#'+section.getAttribute("id")+'"]').getAttribute("data-offset")  ?? scrollspy_offset;
            }

            if (pageYOffset >= sectionTop - scrollspy_offset2) {
                current = section.getAttribute('id');
            }
        });

        if (pageYOffset == 0 || current == "hero") {
            current = "domu";
        } 

        navLi.forEach(li => {
            li.classList.remove('active');
            if (li.querySelector('a').getAttribute('href').substring(1) === current) {
                li.classList.add('active');
            }
        });
    });
}

/** Scrollovani mezi kotvami */
function smoothAnchorScroll ()
{
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            let targetId = this.getAttribute('href');
            let offsetPosition = 0;
            let scrollspy_offset2 = this.getAttribute('data-offset') ? this.getAttribute('data-offset') : scrollspy_offset;

            if(targetId !== "#domu") { // osetreni kvuli fixni hlavicky
                let targetElement = document.querySelector(targetId);
                let offset = scrollspy_offset2 - 5;
                let elementPosition = targetElement.getBoundingClientRect().top;
                    offsetPosition = elementPosition + window.pageYOffset - offset;
            }

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });

            document.getElementById('menu-collapsed').checked = false;

        });
    });
}

 
function expandBoxes() {
    const readMoreBtns = document.querySelectorAll('.read-more-btn');
    const readMoreTexts = document.querySelectorAll('.read-more-text');

    readMoreBtns.forEach((btn, index) => {
        btn.addEventListener('click', function () {
            const moreInfo = readMoreTexts[index];
            const status = moreInfo.classList.contains('expanded');

            if (moreInfo.scrollHeight > 0) {
                if (!status) {
                    moreInfo.style.height = moreInfo.scrollHeight + "px";
                    moreInfo.classList.add('expanded');
                } else {
                    moreInfo.style.height = "0px";
                    moreInfo.classList.remove('expanded');
                }
            }
        });
    });
}

/**
 * 
 */
function accordition() {
    const labels = document.querySelectorAll('.accordition-item-label');
    const viewportWidth = window.innerWidth || document.documentElement.clientWidth;

    
        labels.forEach(label => {
            label.addEventListener('click', (e) => {

                //accordition_SetHeightOfDetail (label);
                setTimeout(() => {
                    const input = document.getElementById(label.getAttribute('for'));

                    if (input.checked) {
                      //  if (viewportWidth < 700) {
                            const labelTop = label.getBoundingClientRect().top + window.scrollY;
                            const offset = labelTop - 110;

                            window.scrollTo({
                                top: offset,
                                behavior: 'smooth'
                            });
                  //      }
                    }
                    // create annotation
                }, 300);
            });
        });
    
}
function accordition_setDetailHeight () {

    let boxes = document.querySelectorAll(".accordition-item-detail");
    if (boxes) {
        boxes.forEach(box => {
            let boxInner     = box.querySelector(".accordition-item-detail-inner")
            box.style.height = (boxInner.offsetHeight+50)+"px";;
        });
    }
}







function sendContactForm() {
    let form   = document.getElementById('contactForm');
    let action = form.getAttribute('action');
    let status = document.getElementById('contactForm_status');

    var formData = {
        type:    document.getElementById('contactForm_type').value,       
        name:    document.getElementById('contactForm_name').value,
        email:   document.getElementById('contactForm_email').value,
        message: document.getElementById('contactForm_message').value    
    };

    if (!formData.name || !formData.email || !formData.message) {
        status.classList.add('error');
        status.innerHTML = 'Vyplňte prosím všechna pole!';
        return;
    }

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var phoneRegex = /^(\+?\d+)?\s?\d{3}\s?\d{3}\s?\d{3}$/;
    if (!emailRegex.test(formData.email) && !phoneRegex.test(formData.email)) {
        status.classList.add('error');
        status.innerHTML = 'Vložte platnou e-mailovou adresu nebo telefon!';
        return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', action, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);
            status.innerHTML = response.status;
            status.classList.remove('error');   
            form.reset();
            form.classList.add('thank');  
            
            setTimeout(function () {
                form.classList.remove('thank');
                status.innerHTML = '';
            }, 5000);
        }
    };
    xhr.send(JSON.stringify(formData));
}


function presetContactForm (type) 
{
    let message = '';

    console.log('presetContactForm '+type);

    switch (type)
    {    
        case "development":
            message = 'Mám zájem o tvorbu webových stránek.';
        break;  
        case "audit":
            message = 'Mám zájem o audit mých webových stránek na adrese: ';
        break;
    }

    document.getElementById('contactForm_type').value    = type;
    document.getElementById('contactForm_message').value = message;
    

    document.querySelectorAll('#contactMe .message_types .btn').forEach(btn => {
        btn.classList.remove('btn-primary3');
        btn.classList.add('btn-primary3-outline');    
    });
    document.querySelectorAll('#contactMe .message_types .'+type).forEach(btn => btn.classList.add('btn-primary3')); 
    document.querySelectorAll('#contactMe .message_types .'+type).forEach(btn => btn.classList.remove('btn-primary3-outline')); 

    document.querySelectorAll('#contactForm_texts > div').forEach(div => div.classList.add('hidden'));   
    document.getElementById('contactForm_text_'+type).classList.remove('hidden');

    setTimeout(function () {
        document.getElementById('contactForm_name').focus();
   }, 800);
   

}


/** CAROUSEL
 *  - rotuje ovládání html/css carouselu manuálně
 *  - po manuálním překliku resetuje timeout
 *
 * @param {number} delay - Zpoždění mezi automatickým přepínáním snímků v milisekundách
 */
function slideCarousel(delay) {
    let interval;
    const slides = document.querySelectorAll('input[name=carousel-item]');

    const startSlideShow = () => {
        clearInterval(interval);
        interval = setInterval(nextSlide, delay);
    };

    const nextSlide = () => {
        const actual = document.querySelector('input[name=carousel-item]:checked');
        const next  = document.querySelector(`input[name=carousel-item][value="${parseInt(actual.value) + 1}"]`) || slides[0];
        next.checked = true;
    };

    startSlideShow();

    slides.forEach(slide => {
        slide.addEventListener('change', startSlideShow);
    });
}

// FIXED HEADER
/**
 * Přidává nebo odebírá třídu 'fixed' na hlavičce stránky při scrollování.
 */
function fixedHeader() {
    const header = document.querySelector('header');
    const banner = document.getElementById('banner');
    const offset = banner.offsetHeight / 2; 


    const onScroll = () => {
        const scrolled = window.pageYOffset || document.documentElement.scrollTop;

        if (scrolled > offset) {
            header.classList.add('fixed');
        } else {
            header.classList.remove('fixed');
        }
    };

    window.addEventListener('scroll', () => {
        onScroll();
    });
    onScroll();
}

// FIXED FOOTER
/**
 * Přidává nebo odebírá třídu 'fixed' na patičce stránky a nastavuje výšku placeholderu pro patičku.
 */
function fixedFooter() {
    const footer = document.querySelector("footer");
    if (footer.offsetHeight > window.innerHeight-100) {
        footer.classList.remove('fixed');
    } else {
        footer.classList.add('fixed');
        const footerPlaceholder = document.querySelector(".footer-placeholder");
        footerPlaceholder.style.height = footer.offsetHeight + "px";
    }
}

/**
 * COUNTER
 * Spustí animaci jakmile se counter objeví ve viewportu.
 * Možnost nastavení délky animace pro jednotlivé položky pomoci atributu data-duration.
 *
 * Priklad html pocitadla:
 *   <div class="counter-box" data-target="50" data-duration="1000">
 *       <span><span class="placeholder">50</span>+</span>
 *       <p>webů hotovo</p>
 *   </div>
 */
function counter() {
    const counters = document.querySelectorAll('.counter-box');

    const countUp = (element, end, duration) => {
        let startTime = null;
        const step = (timestamp) => {
            if (!startTime) startTime = timestamp;
            const progress = Math.min((timestamp - startTime) / duration, 1);
            element.innerText = Math.floor(progress * end);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    };

    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observerCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = counter.dataset.target ? parseInt(counter.dataset.target) : 100;
                const placeholder = counter.querySelector('.placeholder');
                const duration = counter.dataset.duration ? parseInt(counter.dataset.duration) : 2000;
                countUp(placeholder, target, duration);
                observer.unobserve(counter);
            }
        });
    };

    const observer = new IntersectionObserver(observerCallback, observerOptions);

    counters.forEach(counter => {
        observer.observe(counter);
    });
}





function cookiesConsent() {
    let consent = getCookie('_cookies') || 0;

    if (consent) { 
        document.getElementById('cookiesConsent').classList.remove('consent_show');  
    } else {
        setTimeout(() => {
            document.getElementById('cookiesConsent').classList.add('consent_show');
        }, "1000");         
    }
}

function saveCookiesConsent(consent) 
{
    
    let cookies_necessary = true;
    let cookies_analytics = false;  
    
    if (consent === 'check') {
        cookies_analytics = document.getElementById("cookies_analytics").checked || false; 
    } else {
        cookies_analytics = consent;       
    }

    let cookieVal = 'a:'+(cookies_analytics?1:0)+';';
    
   // hideCookiesInfo();
    setCookie('_cookies', cookieVal, 14);
    cookiesConsent();

    if (cookies_analytics) {
        loadGoogleAnalytics();
    }

} 


function showCookiesInfo() {
    document.getElementById('cookiesConsent_info').classList.remove('hidden');
    document.getElementById('cookiesConsent_bar').classList.add('hidden');       
}

function hideCookiesInfo() {
    document.getElementById('cookiesConsent_info').classList.add('hidden');
    document.getElementById('cookiesConsent_bar').classList.remove('hidden');         
}

// Dynamické načtení Google Analytics kódu po udeleni souhlasu s analytickymi cookies
function loadGoogleAnalytics() {
    const script = document.createElement('script');
    script.src = "https://www.googletagmanager.com/gtag/js?id=APIKEY";
    script.async = true;
    document.head.appendChild(script);

    script.onload = function() {
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'APIKEY');
    };
 
    gtag('consent', 'default', {
      'ad_storage': 'denied',
      'ad_user_data': 'denied',
      'ad_personalization': 'denied',
      'analytics_storage': 'granted'
    });    
}


function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
