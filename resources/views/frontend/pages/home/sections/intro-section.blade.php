<section class="intro-section py-80 pt_120 xs_pt_80" >


<style>
.intro-section{
  position: relative; overflow: hidden;
  color:#1f2845;
  background: linear-gradient(180deg,#fff 0%,#f4f7ff 100%);
  border-top:1px solid rgba(223,163,89,.30);
}

/* БОЛЕЕ СИЛЬНОЕ свечение слева/справа */
.intro-section::before,
.intro-section::after{
  content:"";
  position:absolute; top:-25%; bottom:-25%;
  width:80%;                 /* шире = заметнее */
  pointer-events:none; z-index:0;
  filter: blur(18px);        /* меньше blur = четче и ярче */
  opacity: .95;              /* сильнее общее свечение */
  /* лёгкая пульсация яркости */
  animation-duration: 8s;
  animation-timing-function: ease-in-out;
  animation-iteration-count: infinite;
}

/* движение + пульсация */
@keyframes glowL { from{ transform:translateX(-4%) scale(1.05) } to{ transform:translateX(-12%) scale(1.10) } }
@keyframes glowR { from{ transform:translateX(4%)  scale(1.05) } to{ transform:translateX(12%)  scale(1.10) } }
@keyframes pulse  { 0%,100%{ opacity:.90 } 50%{ opacity:1 } }

.intro-section::before{
  left:-12%;
  background: radial-gradient(ellipse at left center,
    rgba(223,163,89,.55) 0%,      /* ярче центр */
    rgba(223,163,89,.32) 20%,
    rgba(223,163,89,.14) 42%,
    rgba(223,163,89,0)   64%);
  animation-name: glowL, pulse;   /* движение + пульс */
}

.intro-section::after{
  right:-12%;
  background: radial-gradient(ellipse at right center,
    rgba(223,163,89,.55) 0%,
    rgba(223,163,89,.32) 20%,
    rgba(223,163,89,.14) 42%,
    rgba(223,163,89,0)   64%);
  animation-name: glowR, pulse;
}

/* контент поверх */
.intro-section > *{ position: relative; z-index: 1; }

@media (prefers-reduced-motion: reduce){
  .intro-section::before, .intro-section::after{ animation: none; }
}
</style>


    <style>

@scroll-timeline slide-horizontal {
    source: body;          /* <- ВАЖНО: источник — вся страница */
    orientation: block;    /* вертикальная прокрутка */
}



        .intro-logos-item-1,
.intro-logos-item-2{
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  width: clamp(220px, 26vw, 360px); /* одинаковая ширина карточек */
}

/* фиксируем высоту подзаголовков, чтобы низ карточек был в одной линии */
.intro-logos-item-1 h1,
.intro-logos-item-2 h1{
  margin-top: 14px;
  font-size: clamp(14px, 1.6vw, 20px);
  font-weight: 300;
  color: rgb(113,113,113);
  line-height: 1.35;
  max-width: 420px;
  min-height: 3.2em; /* подравнивает высоту текста между карточками */
}

/* на всякий случай переопределим старые !important высоты картинок внутри бейджа */
.intro-logos .logo-badge__inner img{
  height: auto !important;
  width: auto !important;
  max-height: 100% !important;
  max-width: 100% !important;
}
        /* Контейнер логотипов */
.intro-logos {
  flex-wrap: wrap;
  gap: clamp(16px, 3vw, 40px);
}

/* Карточка подписи под логотипом */
.intro-logos-item-1 h1,
.intro-logos-item-2 h1{
  margin-top: 14px;
  font-size: clamp(14px, 1.6vw, 20px);
  font-weight: 300;
  color: rgb(113,113,113);
  line-height: 1.35;
  max-width: 420px;
}

/* ==== КРУГЛОЕ ВЫДЕЛЕНИЕ ==== */
.logo-badge{
  /* легко настраиваемые переменные */
  --size: clamp(84px, 12vw, 140px);   /* диаметр белой «таблетки» внутри */
  --ring: 5px;                        /* толщина кольца */
  --pad: 10px;                        /* внутренний отступ от кольца до белого круга */

  position: relative;
  display: grid;
  place-items: center;
  width: calc(var(--size) + var(--ring)*2 + var(--pad)*2);
  height: calc(var(--size) + var(--ring)*2 + var(--pad)*2);
  border-radius: 50%;

  /* градиентное кольцо */
  background:
    /* слой содержимого */
    linear-gradient(#fff, #fff) padding-box,
    /* слой границы (градиент-колечко) */
    conic-gradient(from 180deg, #3aa8ff, #153b8a, #3aa8ff) border-box;
  border: var(--ring) solid transparent;

  /* лёгкая глубина */
  box-shadow:
    0 8px 24px rgba(21, 59, 138, 0.16),
    0 2px 6px rgba(21, 59, 138, 0.10);
  transition: transform .2s ease, box-shadow .2s ease;
}

.logo-badge:hover{
  transform: translateY(-2px);
  box-shadow:
    0 12px 28px rgba(21, 59, 138, 0.20),
    0 4px 10px rgba(21, 59, 138, 0.12);
}

/* белая круглая «таблетка» внутри кольца */
.logo-badge__inner{
  width: var(--size);
  height: var(--size);
  border-radius: 50%;
  background: #fff;
  display: grid;
  place-items: center;
  padding: clamp(10px, 2.2vw, 16px); /* чтобы логотипы «дышали» внутри круга */
}

/* сам логотип: встаёт по центру, не обрезается */
.logo-badge__inner img{
  max-width: 100%;
  max-height: 100%;
  height: auto;
  display: block;
}

/* Мобильные мелочи */
@media (max-width: 576px){
  .intro-logos { gap: 16px; }
}


@scroll-timeline slide-horizontal {
    source: body;          /* <- ВАЖНО: источник — вся страница */
    orientation: block;    /* вертикальная прокрутка */
}


.programs-v2 {
    max-width: 920px;
    margin: 120px auto;
    padding: 96px 80px;
    background: #ffffff;
    border: 1.8px solid #e2e8f0;
    border-radius: 32px;
    box-shadow: 0 40px 80px -16px rgba(15, 23, 42, 0.09);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    /* text-align: center; */
    position: relative;
    overflow: hidden;
    /* opacity: 0; */
    /* transform: translateY(40px); */
    /* animation: reveal 1.1s cubic-bezier(0.4, 0, 0.2, 1) forwards; */
    animation-name: slideX;
    animation-duration: 1s;      /* обязательная заглушка */
    animation-timeline: slide-horizontal;
    animation-fill-mode: both;
}

@keyframes slideX {
    0%   { transform: translateX(0); }
    50%  { transform: translateX(-40px); }
    100% { transform: translateX(0); }
}
 

.programs-v2::before {
    content: '';
    position: absolute;
    top: 0; left: 50%;
    width: 100px;
    height: 5px;
    background: #0f172a;
    transform: translateX(-50%);
    border-radius: 0 0 4px 4px;
}


.programs-v2__title {
    font-size: 44px;
    font-weight: 850;
    letter-spacing: -1.6px;
    color: #0f172a;
    margin: 0 0 20px 0;
    line-height: 1.1;
}

.programs-v2__lead {
    font-size: 20px;
    line-height: 1.75;
    color: #475569;
    /* margin: 0 0 56px 0; */
    font-weight: 400;
    text-align: left;
}

.programs-v2__price {
    font-size: 28px;
    font-weight: 500;
    color: #1e293b;
    margin: 28px 0;
    padding: 8px 0;
    position: relative;
}

.programs-v2__price strong {
    font-size: 42px;
    font-weight: 800;
    color: #1E3A8A;
    letter-spacing: -1px;
}

.programs-v2__price::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    width: 60px;
    height: 2px;
    background: #cbd5e1;
    transform: translateX(-50%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.programs-v2:hover .programs-v2__price::before {
    opacity: 1;
}

.programs-v2__includes {
    font-size: 18px;
    line-height: 1.8;
    color: #64748b;
    margin: 48px 0 40px 0;
    padding: 0 40px;
}

.programs-v2__footer {
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
    font-style: italic;
    margin: 0;
    opacity: 0;
    animation: fadeIn 1s ease-out 0.8s forwards;
}

/* Анимации */
@keyframes reveal {
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
    to { opacity: 1; }
}

/* Мобильная версия */
@media (max-width: 768px) {
    .programs-v2 {
        margin: 80px 20px;
        padding: 64px 32px;
        border-radius: 28px;
    }
    .programs-v2__title { font-size: 36px; }
    .programs-v2__lead { font-size: 18px; }
    .programs-v2__price { font-size: 24px; }
    .programs-v2__price strong { font-size: 36px; }
    .programs-v2__footer { font-size: 21px; }
}



</style>



<div class="container text-center">
        <div class="d-flex justify-content-center align-items-center gap-5 mb-4 wow fadeInUp intro-logos">


        <div class="intro-logos-item-1">
  <div class="logo-badge">
    <div class="logo-badge__inner">
      <img src="{{ asset('frontend/assets/images/ipk-logo.png') }}" alt="IPK">
    </div>
  </div>
  <h1>{{ __('Institute of Professional Development and Continuing Education') }}</h1>
</div>

<div class="intro-logos-item-2">
  <div class="logo-badge">
    <div class="logo-badge__inner">
      <img src="{{ asset('frontend/assets/images/farabi-logo.png') }}" alt="Farabi">
    </div>
  </div>
  <h1>{{ __('Al-Farabi Kazakh National University') }}</h1>
</div>



        </div>

        <h1 class="display-5 fw-bold mb-3 wow fadeInUp" data-wow-delay="0.05s">
            {{ __('Institute title') }}
        </h1>
        <p class="lead text-muted mb-4 wow fadeInUp" data-wow-delay="0.1s" style="max-width:900px;margin:0 auto;">
            {{ __('Institute subtitle') }}
        </p>

     <!--     <div class="d-flex justify-content-center gap-3 mt-3 wow fadeInUp" style="margin-bottom:100px;" data-wow-delay="0.15s">
            <a href="{{ localizedRoute('services') }}" class="common_btn">{{ __('Our programs') }}</a>
            <a href="{{ localizedRoute('application-form') }}" class="btn btn-outline-primary px-4" style="border-radius:8px; background:#1E3A8A;color:#ffffff;   ">
                {{ __('Apply') }}
            </a>
        </div>
    -->


<div class="d-flex justify-content-center gap-3 mt-3 wow fadeInUp"
     style="margin-bottom:100px;" data-wow-delay="0.15s">

  <a href="{{ localizedRoute('services') }}" class="common_one_btn">
    {{ __('Our programs') }}
  </a>

  <a href="{{ localizedRoute('application-form') }}"
     class="btn btn-outline-primary px-4 apply-btn">
    {{ __('Apply') }}
  </a>
</div>

<style>
  /* Style for Apply button */
  .apply-btn {
    border-radius: 8px;
    background: #1E3A8A;
    color: #ffffff;
    border: 2px solid #1E3A8A;
    display: flex;
  align-items: center;   /* vertical centering */
  justify-content: center; /* horizontal centering */
  }

  .apply-btn:hover {
    background: #ffffff;
    color: #1E3A8A;
    border-color: #1E3A8A;
  }

  /* Optional: add a hover effect to 'Our programs' too */
  .common_one_btn {
   background: #1E3A8A !important;
    color: #ffffff;
    border-radius: 8px;
    padding: 10px 25px;
    text-decoration: none;
    border: 2px solid #1E3A8A;
    display: flex;
  align-items: center;
  justify-content: center;
  }

  .common_one_btn:hover {
    background: #ffffff !important;
    color: #1E3A8A !important ;
    border-color: #1E3A8A;
  }
</style>




    </div>
</section>

    <section class="diploma">
        <div class="diploma__container">
            <div class="diploma__content">
                <h1 class="diploma__title">{{ __('ABOUT THE CERTIFICATE') }}</h1>
                <p class="diploma__text">
                    {{ __('Upon successful completion of the training program at the Institute for Advanced Training and Continuing Education of Al-Farabi KazNU, participants receive an official state-recognized certificate. This document confirms your professional growth, the acquisition of new competencies, and your successful completion of a certified educational program. The certificate is officially recognized by universities, colleges, and educational institutions across Kazakhstan, as well as by public and private organizations. It demonstrates your commitment to lifelong learning and continuous development.') }}
                </p>
                <p class="diploma__text diploma__text--highlight">
                    <span class="diploma__plus"></span>
                </p>
            </div>
            <div class="diploma__image-wrapper">
                <div class="diploma__image">
                    <img class="diploma__image-img" src="{{ asset('frontend/assets/images/diplom.png') }}" alt="Образец диплома">
                </div>
                <p class="diploma__caption">{{ __('Образец сертификата') }}</p>
                Sample certificate
                Сертификат үлгісі
            </div>
        </div>
    </section>


    <section class="brands">
        <p class="brands__description">
            {{ __('OUR PARTICIPANTS AND PARTNERS The Institute for Advanced Training and Continuing Education of Al-Farabi KazNU collaborates with over 50 universities, colleges, schools, and organizations across Kazakhstan and abroad. Our programs attract participants from all regions of the country, as well as from international partner universities. Among our participants are ALMA University, Satbayev University, Toraighyrov University, Atyrau University, Dulaty University, Astana International University, and representatives from universities in Uzbekistan, Turkey, and Europe. Major companies and organizations such as RG Brands, KazTransOil, and Zolotoe Yabloko also take part in our training programs. The Institute maintains active partnerships with international institutions, enabling the exchange of experience, collaborative projects, and joint educational initiatives. Our growing network of listeners and partners reflects the Institute’s commitment to quality education, innovation, and global cooperation.') }}
</p>
        <div class="brands__list">
            <div class="brands__item">
                <img class="brands__item-img" src="https://optim.tildacdn.pro/tild3130-3162-4434-b365-303932363139/-/resize/728x/-/format/webp/image-removebg-previ.png.webp" alt="BUSINESS GRADUATES ASSOCIATION">
            </div>
            <div class="brands__item">
                <img class="brands__item-img" src="https://optim.tildacdn.pro/tild6164-6332-4063-b461-333536333831/-/resize/572x/-/format/webp/image-removebg-previ.png.webp" alt="Coursera">
            </div>
            <div class="brands__item">
                <img class="brands__item-img" src="https://optim.tildacdn.pro/tild3130-3162-4434-b365-303932363139/-/resize/728x/-/format/webp/image-removebg-previ.png.webp" alt="Trust Me">
            </div>
            <div class="brands__item">
                <img class="brands__item-img" src="https://optim.tildacdn.pro/tild3130-3162-4434-b365-303932363139/-/resize/728x/-/format/webp/image-removebg-previ.png.webp" alt="INTERTEACH">
            </div>
            <div class="brands__item">
                <img class="brands__item-img" src="https://optim.tildacdn.pro/tild3130-3162-4434-b365-303932363139/-/resize/728x/-/format/webp/image-removebg-previ.png.webp" alt="ACQUIN">
            </div>
            <div class="brands__item">
                <img class="brands__item-img" src="https://optim.tildacdn.pro/tild3130-3162-4434-b365-303932363139/-/resize/728x/-/format/webp/image-removebg-previ.png.webp" alt="HR CAPITAL">
            </div>
        </div>
    </section>




<div class="programs-v2">
    @if(app()->getLocale() === 'kk')
        <h1 class="programs-v2__title">БАҒДАРЛАМАЛАР МЕН БАҒА ТУРАЛЫ АҚПАРАТ</h1>
        <div class="programs-v2__content">
            <p class="programs-v2__lead">
                Әл-Фараби атындағы ҚазҰУ-дың Біліктілікті арттыру және қосымша білім беру институты<br>
                барлық деңгейдегі білім беру мекемелеріне арналған икемді және қолжетімді бағдарламалар ұсынады.<br>
                Біз әрбір тыңдаушыға сапалы білім мен заманауи тәжірибе арқылы кәсіби өсуге мүмкіндік береміз.
            </p>

            <div class="programs-v2__price">Мектеп пен колледж қызметкерлеріне арналған курстар — <strong>25 000 ₸</strong>-дан басталады</div>
            <div class="programs-v2__price">ЖОО оқытушылары мен мамандарына арналған бағдарламалар — <strong>72 000 ₸</strong>-дан жоғары</div>

            <p class="programs-v2__includes">
                Бұл бағаларға тәжірибелі профессорлардан сабақ алу, онлайн және офлайн форматтағы икемді оқу жүйесі,<br>
                сонымен қатар мемлекеттік үлгідегі сертификат кіреді.
            </p>

            <p class="programs-v2__footer">
                Білімге салынған инвестиция — болашаққа салынған ең сенімді капитал!
            </p>
        </div>

    @elseif(app()->getLocale() === 'ru')
        <h1 class="programs-v2__title">ИНФОРМАЦИЯ О ПРОГРАММАХ И СТОИМОСТИ</h1>
        <div class="programs-v2__content">
            <p class="programs-v2__lead">
                Институт повышения квалификации и дополнительного образования КазНУ имени аль-Фараби<br>
                предлагает гибкие и доступные программы для всех уровней образования.<br>
                Мы создаем возможности для профессионального роста, сочетая академические знания и практические навыки.
            </p>

            <div class="programs-v2__price">Для школ и колледжей — курсы <strong>от 25 000 ₸</strong></div>
            <div class="programs-v2__price">Для преподавателей и специалистов вузов — программы <strong>от 72 000 ₸</strong></div>

            <p class="programs-v2__includes">
                В стоимость входит обучение у опытных преподавателей КазНУ, удобные онлайн и офлайн форматы,<br>
                а также сертификат государственного образца.
            </p>

            <p class="programs-v2__footer">
                Инвестиции в образование — это инвестиции в уверенное будущее!
            </p>
        </div>

    @else
        <h1 class="programs-v2__title">COURSE FEES AND PROGRAM INFORMATION</h1>
        <div class="programs-v2__content">
            <p class="programs-v2__lead">
                The Institute for Advanced Training and Continuing Education<br>
                at Al-Farabi Kazakh National University offers flexible and affordable programs<br>
                for schools, colleges, and universities.
            </p>

            <div class="programs-v2__price">For schools and colleges — courses <strong>from 25,000 KZT</strong></div>
            <div class="programs-v2__price">For university lecturers and professionals — programs <strong>from 72,000 KZT</strong></div>

            <p class="programs-v2__includes">
                All programs include instruction from qualified KazNU professors,<br>
                flexible online/offline formats, and an official state-recognized certificate.
            </p>

            <p class="programs-v2__footer">
                Education is the best investment in your future!
            </p>
        </div>
    @endif
</div>


    

<section class="advantages">
    <h2 class="advantages__title">
        <span class="advantages__title-part">{{ __('Frequently Asked') }}</span>
        <span class="advantages__title-part advantages__title-part--accent">{{ __('Questions') }}</span>
    </h2>
    <div class="advantages__list">
        <!-- Вопрос 1 -->
        <div class="advantages__item advantages__item--active">
            <div class="advantages__item-header">
                <h3 class="advantages__item-title">{{ __('faq_q1_title') }}</h3>
                <span class="advantages__item-icon">+</span>
            </div>
            <div class="advantages__item-content">
                <p class="advantages__item-text">{{ __('faq_q1_answer') }}</p>
            </div>
        </div>

        <!-- Вопрос 2 -->
        <div class="advantages__item">
            <div class="advantages__item-header">
                <h3 class="advantages__item-title">{{ __('faq_q2_title') }}</h3>
                <span class="advantages__item-icon">+</span>
            </div>
            <div class="advantages__item-content">
                <p class="advantages__item-text">{{ __('faq_q2_answer') }}</p>
            </div>
        </div>

        <!-- Вопрос 3 -->
        <div class="advantages__item">
            <div class="advantages__item-header">
                <h3 class="advantages__item-title">{{ __('faq_q3_title') }}</h3>
                <span class="advantages__item-icon">+</span>
            </div>
            <div class="advantages__item-content">
                <p class="advantages__item-text">{{ __('faq_q3_answer') }}</p>
            </div>
        </div>

        <!-- Вопрос 4 -->
        <div class="advantages__item">
            <div class="advantages__item-header">
                <h3 class="advantages__item-title">{{ __('faq_q4_title') }}</h3>
                <span class="advantages__item-icon">+</span>
            </div>
            <div class="advantages__item-content">
                <p class="advantages__item-text">{{ __('faq_q4_answer') }}</p>
            </div>
        </div>

        <!-- Вопрос 5 -->
        <div class="advantages__item">
            <div class="advantages__item-header">
                <h3 class="advantages__item-title">{{ __('faq_q5_title') }}</h3>
                <span class="advantages__item-icon">+</span>
            </div>
            <div class="advantages__item-content">
                <p class="advantages__item-text">{{ __('faq_q5_answer') }}</p>
            </div>
        </div>

        <!-- Вопрос 6 -->
        <div class="advantages__item">
            <div class="advantages__item-header">
                <h3 class="advantages__item-title">{{ __('faq_q6_title') }}</h3>
                <span class="advantages__item-icon">+</span>
            </div>
            <div class="advantages__item-content">
                <p class="advantages__item-text">{{ __('faq_q6_answer') }}</p>
            </div>
        </div>

        <!-- Вопрос 7 -->
        <div class="advantages__item">
            <div class="advantages__item-header">
                <h3 class="advantages__item-title">{{ __('faq_q7_title') }}</h3>
                <span class="advantages__item-icon">+</span>
            </div>
            <div class="advantages__item-content">
                <p class="advantages__item-text">{{ __('faq_q7_answer') }}</p>
            </div>
        </div>

        <!-- Вопрос 8 -->
        <div class="advantages__item">
            <div class="advantages__item-header">
                <h3 class="advantages__item-title">{{ __('faq_q8_title') }}</h3>
                <span class="advantages__item-icon">+</span>
            </div>
            <div class="advantages__item-content">
                <p class="advantages__item-text">{{ __('faq_q8_answer') }}</p>
            </div>
        </div>
    </div>
</section>





<style>
    /* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #ffffff;
}

/* Diploma Section */
.diploma {
    width: 100%;
    margin: 0;
    padding: 0;
    background: #1A263C;
    overflow: hidden;
}

.diploma__container {
    display: flex;
    min-height: 600px;
    max-width: 1400px;
    margin: 0 auto;
}

.diploma__content {
    flex: 0 0 65%;
    background: #282c34;
    padding: 80px 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.diploma__title {
    font-size: 48px;
    font-weight: 700;
    color: #E5BB67;
    margin-bottom: 40px;
    text-align: left;
    line-height: 1.2;
}

.diploma__text {
    font-size: 18px;
    color: #ffffff;
    margin-bottom: 25px;
    text-align: left;
    line-height: 1.8;
    max-width: 100%;
}

.diploma__text--highlight {
    margin-top: 10px;
}

.diploma__plus {
    color: #E5BB67;
    font-size: 24px;
    font-weight: 700;
    margin-right: 10px;
}

.diploma__image-wrapper {
    flex: 0 0 35%;
    background: #E5BB67;
    padding: 60px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: relative;
}

.diploma__image {
    width: 100%;
    max-width: 500px;
    background: #ffffff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    transform: rotate(-3deg);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 30px;
}

.diploma__image:hover {
    transform: rotate(-1deg) scale(1.02);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.4);
}

.diploma__image-img {
    width: 100%;
    height: auto;
    border-radius: 4px;
    display: block;
}

.diploma__caption {
    color: #ffffff;
    font-size: 18px;
    text-align: center;
    margin: 0;
    font-weight: 400;
}










/* Brands Section */
.brands {
    max-width: 1400px;
    margin: 0 auto;
    padding: 100px 60px;
    background-color: #ffffff;
}

.brands__description {
    font-size: 18px;
    color: #1a1a1a;
    line-height: 1.9;
    margin-bottom: 80px;
    text-align: left;
    max-width: 100%;
    font-weight: 400;
}

.brands__list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 60px 80px;
    margin-top: 0;
}

.brands__item {
    background: transparent;
    border-radius: 0;
    padding: 0;
    box-shadow: none;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: auto;
    border: none;
}

.brands__item:hover {
    transform: none;
    box-shadow: none;
    border-color: transparent;
    opacity: 0.8;
}

.brands__item-img {
    max-width: 100%;
    max-height: 100px;
    width: auto;
    height: auto;
    object-fit: contain;
    filter: none;
    transition: opacity 0.3s ease;
}

.brands__item:hover .brands__item-img {
    filter: none;
    opacity: 0.8;
}

/* Form Section */
.form {
    width: 100%;
    margin: 0;
    padding: 0;
    background: #ffffff;
    overflow: hidden;
}

.form__container {
    display: flex;
    min-height: 600px;
    max-width: 1400px;
    margin: 0 auto;
}

.form__form-wrapper {
    flex: 0 0 40%;
    background: #282c34;
    padding: 80px 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.form__title {
    font-size: 32px;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 40px;
    text-align: left;
    line-height: 1.3;
}

.form__form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 20px;
}

.form__input {
    padding: 16px 20px;
    font-size: 16px;
    border: none;
     border-radius: 0px;
    background: #ffffff;
    color: #333;
    transition: all 0.3s ease;
    outline: none;
    width: 100%;
}

/* .form__input:focus { } */

.form__input::placeholder {
    color: #999;
}

.form__button {
    padding: 18px 30px;
    font-size: 18px;
    font-weight: 600;
    color: hsl(0, 0%, 15%);
    background: #E5BB67;
    border: none;
    /* border-radius: 8px; */
    cursor: pointer;
    /* transition: all 0.3s ease; */
    width: 100%;
    margin-top: 10px;
}

.form__button:hover {
    background: #212121;
    color: #eeeeee;
    /* transform: translateY(-2px); */
    /* box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); */
}

/* .form__button:active { } */

.form__disclaimer {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.5;
    margin-top: 15px;
    text-align: left;
}

.form__installment {
    flex: 0 0 60%;
    background: #f8f8f8;
    padding: 80px 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.form__installment-title {
    font-size: 42px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 20px;
    line-height: 1.2;
}

.form__installment-duration {
    display: flex;
    align-items: baseline;
    gap: 15px;
    margin-bottom: 40px;
    flex-wrap: wrap;
}

.form__installment-months {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
}

.form__installment-details {
    font-size: 18px;
    color: #555;
    font-weight: 400;
}

.form__installment-price {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-top: 20px;
}

.form__installment-price-label {
    font-size: 32px;
    font-weight: 600;
    color: #1a1a1a;
}

.form__installment-price-amount {
    font-size: 56px;
    font-weight: 700;
    color: #1E3A8A;
    line-height: 1;
}

.form__installment-price-currency {
    font-size: 32px;
    font-weight: 600;
    color: #1a1a1a;
}

/* Advantages Section */
.advantages {
    max-width: 1400px;
    margin: 0 auto;
    padding: 100px 60px;
    background-color: #fafafa;
}

.advantages__title {
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 60px;
    text-align: left;
    line-height: 1.2;
}

.advantages__title-part {
    display: inline-block;
}

.advantages__title-part:first-child {
    color: #1E3A8A;
    opacity: 0.8;
}

.advantages__title-part--accent {
    color: #1E3A8A;
    opacity: 1;
}

.contacts {
    width: 100%;
    background: #1A263C;
    color: #ffffff;
}

.contacts__container {
    display: flex;
    max-width: 1400px;
    margin: 0 auto;
}

.contacts__left {
    flex: 0 0 45%;
    padding: 80px 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 28px;
}

.contacts__title {
    font-size: 56px;
    font-weight: 800;
    margin: 0 0 10px 0;
}

.contacts__block {
    margin-top: 10px;
}

.contacts__subtitle {
    font-size: 18px;
    opacity: 0.8;
    margin-bottom: 8px;
}

.contacts__text {
    font-size: 24px;
    font-weight: 600;
}

.contacts__phone {
    display: inline-block;
    font-size: 28px;
    font-weight: 800;
    color: #E5BB67;
    text-decoration: none;
    margin-top: 6px;
}

.contacts__socials {
    display: flex;
    gap: 16px;
    margin-top: 10px;
}

.contacts__social {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 2px solid rgba(255, 255, 255, 0.5);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: background 0.2s ease, border-color 0.2s ease;
    position: relative;
    color: #ffffff;
}

.contacts__social::before {
    content: "";
    font-size: 18px;
    font-weight: 700;
}

.contacts__social--yt::before { content: "▶"; }
.contacts__social--fb::before { content: "f"; }
.contacts__social--ig::before { content: "ig"; font-size: 14px; }
.contacts__social--tg::before { content: "✈"; }

.contacts__social:hover {
    background: rgba(229, 187, 103, 0.2);
    border-color: #E5BB67;
}

.contacts__email {
    color: #ffffff;
    text-decoration: none;
    opacity: 0.9;
}

.contacts__brand {
    margin-top: 10px;
}

.contacts__brand-img {
    width: 160px;
    height: auto;
}

.contacts__map {
    flex: 1;
    background: #E5BB67;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
}

.contacts__map-inner {
    width: 100%;
    height: 520px;
    background: #ffffff;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
}

.contacts__map-frame {
    width: 100%;
    height: 100%;
    border: 0;
}

.advantages__list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.advantages__item {
    background: transparent;
    border-bottom: 1px solid #e0e0e0;
    padding: 25px 0;
    transition: all 0.3s ease;
}

.advantages__item:last-child {
    border-bottom: none;
}

.advantages__item-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    gap: 20px;
}

.advantages__item-title {
    font-size: 24px;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
    line-height: 1.4;
    flex: 1;
    text-align: left;
}

.advantages__item-icon {
    font-size: 32px;
    font-weight: 300;
    color: #1E3A8A;
    line-height: 1;
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.advantages__item--active .advantages__item-icon {
    background: #1E3A8A;
    color: #ffffff;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    transform: rotateZ(45deg);
}

.advantages__item-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
    padding: 0;
}

.advantages__item--active .advantages__item-content {
    max-height: 1000px;
    padding-top: 20px;
}

.advantages__item-text {
    font-size: 18px;
    color: #555;
    line-height: 1.7;
    margin: 0;
}

.advantages__documents-list {
    list-style: none;
    margin: 20px 0 0 0;
    padding: 0;
}

.advantages__documents-item {
    font-size: 18px;
    color: #555;
    padding: 10px 0;
    padding-left: 30px;
    position: relative;
    line-height: 1.6;
}

.advantages__documents-item::before {
    content: "•";
    position: absolute;
    left: 0;
    color: #1E3A8A;
    font-weight: bold;
    font-size: 24px;
    line-height: 1;
}

/* Responsive Design */
@media (max-width: 968px) {
    .diploma__container {
        flex-direction: column;
        min-height: auto;
    }

    .diploma__content {
        flex: 1;
        padding: 60px 40px;
    }

    .diploma__image-wrapper {
        flex: 1;
        padding: 60px 40px;
    }

    .diploma__title {
        font-size: 36px;
        text-align: center;
    }

    .diploma__text {
        text-align: center;
    }
}

@media (max-width: 768px) {
    .diploma__content {
        padding: 50px 30px;
    }

    .diploma__image-wrapper {
        padding: 50px 30px;
    }

    .diploma__title {
        font-size: 32px;
    }

    .diploma__text {
        font-size: 16px;
    }

    .brands {
        padding: 60px 40px;
    }

    .brands__description {
        font-size: 16px;
        text-align: left;
        margin-bottom: 50px;
    }

    .brands__list {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px 50px;
    }

    .form__container {
        flex-direction: column;
        min-height: auto;
    }

    .form__form-wrapper {
        flex: 1;
        padding: 60px 40px;
    }

    .form__installment {
        flex: 1;
        padding: 60px 40px;
    }

    .form__title {
        font-size: 28px;
        text-align: center;
    }

    .form__installment-title {
        font-size: 32px;
        text-align: center;
    }

    .form__installment-duration {
        justify-content: center;
        text-align: center;
    }

    .form__installment-price {
        justify-content: center;
    }

    .advantages {
        padding: 60px 40px;
    }

    .advantages__title {
        font-size: 36px;
        margin-bottom: 40px;
    }

    .advantages__item {
        padding: 20px 0;
    }

    .advantages__item-title {
        font-size: 20px;
    }

    .advantages__item-icon {
        font-size: 28px;
        vertical-align: display;
        display: inline-block;



        transform: translateY(-50%);
        /* display: inline-block; */
        width: 40px;
        height: 40px;


        transition-property: fill, stroke;
        transition-duration: 0.2s;
        transition-timing-function: ease;

    }

    .advantages__item--active .advantages__item-icon {

    display: inline-block;
    position: absolute;
    right: 0;
    top: 50%;

    width: 40px;
    height: 40px;
    stroke: #222;



    }

    .advantages__item-text {
        font-size: 16px;
    }

    .advantages__documents-item {
        font-size: 16px;
    }

    .contacts__container {
        flex-direction: column;
    }

    .contacts__left {
        padding: 60px 40px;
    }

    .contacts__map {
        padding: 30px;
    }

    .contacts__title {
        font-size: 42px;
    }
}

@media (max-width: 480px) {
    .diploma__content {
        padding: 40px 20px;
    }

    .diploma__image-wrapper {
        padding: 40px 20px;
    }

    .diploma__title {
        font-size: 26px;
    }

    .diploma__image {
        transform: rotate(-2deg);
    }

    .form__form-wrapper {
        padding: 50px 30px;
    }

    .form__installment {
        padding: 50px 30px;
    }

    .form__title {
        font-size: 24px;
    }

    .form__installment-title {
        font-size: 28px;
    }

    .form__installment-months {
        font-size: 20px;
    }

    .form__installment-details {
        font-size: 16px;
    }

    .form__installment-price-amount {
        font-size: 42px;
    }

    .form__installment-price-label,
    .form__installment-price-currency {
        font-size: 24px;
    }

    .form__input {
        padding: 14px 16px;
        font-size: 15px;
    }

    .brands {
        padding: 50px 30px;
    }

    .brands__list {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .brands__item-img {
        max-height: 80px;
    }

    .form__button {
        padding: 16px 25px;
        font-size: 16px;
    }

    .advantages {
        padding: 50px 30px;
    }

    .advantages__title {
        font-size: 28px;
    }

    .advantages__item-title {
        font-size: 18px;
    }

    .advantages__item-icon {
        font-size: 24px;
        width: 32px;
        height: 32px;
    }

    .advantages__item--active .advantages__item-icon {
        width: 32px;
        height: 32px;
    }

    .contacts__left {
        padding: 40px 20px;
    }

    .contacts__map {
        padding: 20px;
    }

    .contacts__title {
        font-size: 36px;
    }
}


</style>



<style>
    .intro-section{

         margin-top: 100px;
    }
        .intro-logos-item-1 h1{
            padding-top: 10px;
            font-size: 20px;
             font-weight: 200;

            color:rgb(113, 113, 113);
        }
        .intro-logos-item-2 h1{
            padding-top: 10px;
            font-size: 20px;
             font-weight: 200;

            color:rgb(113, 113, 113);
        }

    .intro-logos img{height:100px !important;width:auto !important}
    @media (max-width: 576px){.intro-logos img{height:40px}}
    @media (max-width: 1200px){.intro-logos img{height:30px}}
    @media (max-width: 992px){.intro-logos img{height:20px}}
    @media (max-width: 768px){.intro-logos img{height:15px}}
    @media (max-width: 576px){.intro-logos img{height:10px}}
    @media (max-width: 480px){.intro-logos img{height:8px}}
    @media (max-width: 320px){.intro-logos img{height:6px}}
    @media (max-width: 240px){.intro-logos img{height:4px}}
    @media (max-width: 160px){.intro-logos img{height:2px}}
    @media (max-width: 120px){.intro-logos img{height:1px}}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Получаем все элементы FAQ
        const faqItems = document.querySelectorAll('.advantages__item');

        faqItems.forEach(item => {
            const header = item.querySelector('.advantages__item-header');

            header.addEventListener('click', function() {
                // Закрываем все остальные элементы (опционально - если хотите чтобы открывался только один)
                // faqItems.forEach(otherItem => {
                //     if (otherItem !== item) {
                //         otherItem.classList.remove('advantages__item--active');
                //     }
                // });

                // Переключаем активный класс
                item.classList.toggle('advantages__item--active');
            });
        });
    });
</script>
