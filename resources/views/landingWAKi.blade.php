<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
<!-- Document Meta
============================================= -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!--IE Compatibility Meta-->
<meta name="author" content="zytheme" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="Html5 Construction Landing Page">
<link href="{{asset('sources/landing/favicon/favicon.ico')}}" rel="icon">

<!-- Fonts
============================================= -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300i,400,400i,500,600,700,800%7CLora:400,400i,700,700i%7COpen+Sans:800" rel="stylesheet">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MMM76L65PE"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-MMM76L65PE');
</script>

<!-- Stylesheets
============================================= -->
<link href="{{asset('css/landing/external.css')}}?ver=1.0" rel="stylesheet">
<link href="{{asset('css/landing/cons.css')}}" rel="stylesheet">
<link href="{{asset('css/landing/bootstrap.min.css')}}" rel="stylesheet">
<link href="{{asset('css/landing/style.css')}}?ver=1.0" rel="stylesheet">

<!-- Document Title
============================================= -->
<title>WAKI DIRUMAH AJA</title>
</head>
<style>
.WAKI{
  color: #00844a;
  font-size: 68px;
  line-height: 1;
  font-weight: bolder;
  -webkit-text-stroke: 1.5px black;
}
.testimonial {
  background-color: white;
  text-align: center;
  padding: 20px 10px 40px;
  margin: 0 15px 10px;
  position: relative;
  border-radius: 1em;
}

.testimonial .icon {
  display: inline-block;
  font-size: 80px;
  color: #016d9b;
  margin-bottom: 20px;
  opacity: 0.6;
}

.testimonial .description {
  font-size: 14px;
  color: #777;
  text-align: justify;
  margin-bottom: 30px;
}

.testimonial .testimonial-content {
  width: 100%;
  left: 0;
  position: absolute;
}

.testimonial .pic {
  display: inline-block;
  border: 4px solid white;
  overflow: hidden;
  z-index: 1;
  position: relative;
}

.testimonial .pic img {
  width: 100%;
  height: auto;
}

.testimonial .name {
  font-size: 15px;
  font-weight: bold;
  color: #2d2d2d;
  text-transform: capitalize;
  margin: 10px 0 5px 0;
}

.testimonial .title {
  display: block;
  font-size: 14px;
  color: #ffd9b8;
}

.owl-controls {
  margin-top: 20px;
}

.owl-pagination {
  display: flex;
  justify-content: center;
}

.owl-page {
  height: 10px;
  width: 40px;
  background-color: rgba(255, 255, 255, 0.2);
  border-radius: 10%;
}

.owl-page:hover,
.owl-page.active {
  background-color: rgba(255, 255, 255, 0.3);
}

.owl-page:not(first-item) {
  margin-left: 10px;
}

.owl-carousel {
-ms-touch-action: none;
touch-action: none;
}

.modal-window {
	 position: fixed;
	 background-color: rgba(255, 255, 255, 0.25);
	 top: 0;
	 right: 0;
	 bottom: 0;
	 left: 0;
	 z-index: 999;
	 visibility: hidden;
	 opacity: 0;
	 pointer-events: none;
	 transition: all 0.3s;
}
 .modal-window:target {
	 visibility: visible;
	 opacity: 1;
	 pointer-events: auto;
}
 .modal-window > div {
	 width: 400px;
	 position: absolute;
   bottom: 2em;
	 left: 2em;
	 padding: 2em;
	 background: white;
   border-radius: 1em;
   background-color: #ccffeb;
}
 .modal-window header {
	 font-weight: bold;
}
 .modal-window h5 {
	 font-size: 16px;
   margin: 0;
   font-weight: 600;
}
 .modal-close {
	 color: #aaa;
	 line-height: 50px;
	 position: absolute;
	 right: 0;
	 text-align: center;
	 top: 0;
	 width: 70px;
	 text-decoration: none;
   font-weight: bold;
   font-family: "Open Sans", sans-serif;
}
 .modal-close:hover {
	 color: black;
}

@media (min-width: 768px) {
  .mobadjust {
    display: flex;
    justify-content: center;
    align-items: center;
  }
}
</style>
<body class="body-scroll">
<!-- Document Wrapper
============================================= -->
<div id="wrapper" class="wrapper clearfix">

<!-- Hero #12
============================================= -->
<section id="hero" class="section hero hero-12 text-center bg-overlay bg-overlay-light bg-parallax fullscreen">
    <div class="bg-section">
        <img src="{{asset('sources/landing/hero/bgtop.jpg')}}" alt="Background" />
    </div>
    <div class="pos-vertical-center">
        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="hero--content col-xs-12 col-sm-8 col-md-8">
                        <img src="https://waki-indonesia.co.id/sources/Logo Since.png" alt="" class="img-fluid logoWK">
                        <div class="clearfix"></div>
                        <h1 class="hero--headline">WAKi semakin dekat dengan anda, langsung ke RUMAH!</h1>
                        <p class="hero--bio">Dapatkan fisioterapi gratis<br> selama <strong>5 hari</strong> di rumah anda!</p>
                        <!-- <a href="#" class="btn btn--primary btn--rounded">Get A Quote</a> -->
                    </div>

                    <div class="form--top col-xs-12 col-sm-4 col-md-4">
                      <h5 class="form--title">
                        Isi Form Kami
                      </h5>
                      <p class="form--para">Dapatkan ekstra bonus voucher wakimart senilai 280.000 untuk 100 orang pendaftar pertama. <br>Promo berakhir dalam</p>
                      <div id="countdown" class="countdown py-4 form--title" style="margin: 0;"></div>
                      <form id="actionAdd" action="{{ route('store_registrationPromotion') }}" method="POST">
                          @csrf
                          <div class="col-xs-12 col-sm-12 col-md-12" style="padding: 0;">
                            <div class="input-group">
                              <span class="usericon">
                                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Nama Depan" required />
                              </span>
                            </div>
                            <div class="input-group">
                              <span class="usericon">
                                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Nama Akhir" required/>
                              </span>
                            </div>
                            <div class="input-group">
                              <span class="usericon2">
                                  <input type="text" class="form-control" id="address" name="address" placeholder="Alamat" required/>
                              </span>
                            </div>
                            <div class="input-group">
                              <span class="usericon3">
                                  <input type="email" class="form-control" id="email" name="email" placeholder="Email" required/>
                              </span>
                            </div>
                            <div class="input-group">
                              <span class="usericon4">
                                  <input type="phone" class="form-control" id="phone" name="phone" placeholder="No Telp" required/>
                              </span>
                            </div>
                          </div>

                          <div class="col-xs-12 col-sm-12 col-md-12" style="margin: auto;">
                            <button id="addRegistrationPromo" type="submit" class="btn btn--customwk btn--rounded">Daftar</button>
                          </div>
                      </form>
                    </div>
                </div>
                <!-- .col-md-10 end -->
            </div>
            <!-- .row end -->
        </div>
    </div>
    <!-- .container end -->
</section>
<!-- #hero12 end -->

<!-- Feature #1
============================================= -->
<section id="feature" class="section feature feature-1 bg-white text-center">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
                <div class="heading mb-70">
                    <h1 class="heading--title">Apa itu WAKi?</h2>
                    <p class="heading--subtitle">WAKi adalah perusahaan alat kesehatan yang mengunggulkan kualitas terjamin, untuk membantu keluarga menuju kehidupan yang lebih baik.
                      <br><span class="diff" style="font-family: 'Brush Script Std'; font-size: 1.5em;">Bukan Janji Tapi Pasti !</span></p>
                </div>
            </div>
        </div>
        <!-- .row end -->
        <div class="row">
            <!-- Panel #1 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <h1 class="WAKI">W</h1>
                    </div>
                    <div class="feature--content">
                        <h3>(Willingness)</h3>
                        <p>Kami percaya kemauan menaklukkan segalanya</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #2 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <h1 class="WAKI">A</h1>
                    </div>
                    <div class="feature--content">
                        <h3>(Action)</h3>
                        <p>Kami percaya bahwa tindakan adalah kekuatan</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #3 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <h1 class="WAKI">K</h1>
                    </div>
                    <div class="feature--content">
                        <h3>(Knowledge)</h3>
                        <p>Kami percaya bahwa pengetahuan adalah bagian penting dari keberhasilan</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #4 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <h1 class="WAKI" style="color: #fcb813;">i</h1>
                    </div>
                    <div class="feature--content">
                        <h3>(Innovation)</h3>
                        <p>Kami percaya bahwa inovasi akan membawa peluang yang tak terbatas kepada kami</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #feature  end -->

<!-- cta2
============================================= -->
<section id="cta2" class="section cta cta-2 bg-theme">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 mobadjust">
                <div class="col-xs-12 col-sm-7 col-md-7 heading" style="text-align: center;">
                    <h2 class="heading--title" style="margin: 10px 0;">SEHAT DI RUMAH AJA!</h2>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-5" style="text-align: center;">
                    <a class="btn btn--customwk btn--rounded" href="#">Daftar Sekarang</a>
                </div>
            </div>
            <!-- .col-md-8 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>

<!-- Feature #2
============================================= -->
<section id="feature" class="section feature feature-1 bg-white text-center">
  <div class="bg-section">
      <img src="{{asset('sources/landing/hero/bgmid.jpg')}}" alt="Background" />
  </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
                <div class="heading mb-70">
                    <h1 class="heading--title">Apa yang anda dapatkan selama 5 hari?</h2>
                    <p class="heading--subtitle">Kami membantu anda untuk merawat kesehatan keluarga anda dengan berbagai jagkauan mulai dari pemurni udara, air minum, hingga pemijatan tubuh teknologi modern.</p>
                </div>
            </div>
        </div>
        <!-- .row end -->
        <div class="row">
            <!-- Panel #1 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                      <img src="{{asset('sources/landing/advantage/Asset1.png')}}" />
                    </div>
                    <div class="feature--content">
                        <p>Bisa konsultasi kesehatan bersama tim</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #2 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <img src="{{asset('sources/landing/advantage/Asset2.png')}}" />
                    </div>
                    <div class="feature--content">
                        <p>Mendapatkan fasilitas kesehatan sesuai kebutuhan</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #3 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <img src="{{asset('sources/landing/advantage/Asset3.png')}}" />
                    </div>
                    <div class="feature--content">
                        <p>Mendapatkan informasi beserta fasilitas WAKi dan WAKimart</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->

            <!-- Panel #4 -->
            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="feature-panel">
                    <div class="feature--icon">
                        <img src="{{asset('sources/landing/advantage/Asset4.png')}}" />
                    </div>
                    <div class="feature--content">
                        <p>Bisa mendapatkan voucher gratis di WAKimart</p>
                    </div>
                </div>
                <!-- .feature-panel end -->
            </div>
            <!-- .col-md-4 end -->
        </div>
        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #feature  end -->

<!-- cta3
============================================= -->
<section id="cta2" class="section cta cta-2 bg-theme">
    <div class="container">
        <div class="col-xs-12 col-sm-12 col-md-12 mobadjust" style="text-align: center">
            <div class="col-xs-12 col-sm-12 col-md-12 heading">
                <h2 class="heading--title" style="margin: 10px 0;">Mulai Hidup Sehat<br> Sekarang Juga!</h2>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <a class="btn btn--customwk btn--rounded" href="#">Daftar Sekarang</a>
            </div>
        </div>
    </div>
    <!-- .container end -->
</section>

<!-- Testimoni #1
============================================= -->
<section id="portfolio" class="section portfolio portfolio-grid portfolio-2 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
                <div class="heading text-center mb-70">
                    <h2 class="heading--title">Testimonial WAKi</h2>
                </div>
            </div>
            <!-- .col-md-6 end -->
        </div>

        <div class="row">
            <div id="testimonial-slider" class="owl-carousel">
              <div class="testimonial">
                <div class="pic">
                  <img src="{{asset('sources/landing/testimonials/1.jpg')}}" alt="">
                </div>
                <p class="description">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>
                <div class="testimonial-content">
                  <h3 class="name">Michele Miller</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="pic">
                  <img src="{{asset('sources/landing/testimonials/2.jpg')}}" alt="">
                </div>
                <p class="description">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>
                <div class="testimonial-content">
                  <h3 class="name">Patricia Knott</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="pic">
                  <img src="{{asset('sources/landing/testimonials/3.jpg')}}" alt="">
                </div>
                <p class="description">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>
                <div class="testimonial-content">

                  <h3 class="name">Justin Ramos</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="pic">
                  <img src="{{asset('sources/landing/testimonials/4.jpg')}}" alt="">
                </div>
                <p class="description">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>
                <div class="testimonial-content">
                  <h3 class="name">Mary Huntley</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="pic">
                  <img src="{{asset('sources/landing/testimonials/5.jpg')}}" alt="">
                </div>
                <p class="description">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>
                <div class="testimonial-content">
                  <h3 class="name">Aaron Newell</h3>
                </div>
              </div>
              <div class="testimonial">
                <div class="pic">
                  <img src="{{asset('sources/landing/testimonials/6.jpg')}}" alt="">
                </div>
                <p class="description">
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>
                <div class="testimonial-content">
                  <h3 class="name">Lizzie Geren</h3>
                </div>
              </div>
            </div>
        </div>

        <!-- .row end -->
    </div>
    <!-- .container end -->
</section>
<!-- #Testimoni end -->

<!-- Footer #9
============================================= -->

<footer id="footer9" class="footer footer-5 footer-8 footer-9 bg-white text-center-xs pt-0 pb-0">

    <!-- .footer-widget end -->
    <div class="footer-bar">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-4">
                  <div class="logoWKbawahcon">
                    <img src="https://waki-indonesia.co.id/sources/Logo Since.png" alt="" class="img-fluid logoWKbawah">
                  </div>
                </div>
                <!-- .col-md-6 end -->
                <div class="col-xs-12 col-sm-8 col-md-8">

                    <div class="footer--content">
                        <p>WAKi Darmo Park 1, Blok 2B no 1-6, Jalan Mayjend Sungkono, Surabaya 60225.</p>
                    </div>
                    <div class="widget--social">
                        <a class="website" href="#">
                          <i class="fa fa-globe"></i>
                          <p>waki-indonesia.co.id</p>
                        </a>
                        <a class="whatsApp" href="#">
                          <i class="fa fa-whatsapp"></i>
                          <p>081234511881</p>
                         </a>
                        <a class="email" href="#">
                          <i class="fa fa-envelope-o"></i>
                          <p>wakimart.id@gmail.com</p>
                        </a>
                        <!-- <a class="instagram" href="#">
                            <i class="fa fa-instagram"></i>
                        </a> -->
                    </div>
                </div>
                <!-- .col-md-6 end -->
            </div>
            <!-- .row end -->
        </div>
        <!-- .container end -->
    </div>
    <!-- .footer bar end -->
</footer>
<!-- #footer9 end -->

<!-- modal success -->
<div class="modal fade" role="dialog" tabindex="-1" id="modal-Success">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header heading" style="display: flex; flex-direction: row;">
                <h4 class="modal-title heading--title" style="width: 90%; margin: 0;">Terima Kasih</h4>
                <button type="button" style="width: 10%; float: right" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body heading">
                <p id="txt-success" class="heading--subtitle">Pendaftaran anda telah berhasil. Terima kasih telah mendaftar, petugas kami akan segera menghubungi Anda.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn--customwk btn--rounded" type="button" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<div class="container">
  <div class="interior">
    <a class="btn regisnotif" href="#open-modal">Basic CSS-Only Modal</a>
  </div>
</div>
<div id="open-modal" class="modal-window">
  <div>
    <a href="#" title="Close" class="modal-close">X</a>
    <h5>Jatmiko Gunawan telah berhasil mendaftar WAKi dirumah saja !</h5>
    <!-- <div>A CSS-only modal based on the :target pseudo-class. Hope you find it helpful.</div> -->
    </div>
</div>

</div>
<!-- #wrapper end -->



<!-- Footer Scripts
============================================= -->
<script src="{{asset('js/landing/jquery-2.2.4.min.js')}}"></script>
<script src="{{asset('js/landing/plugins.js')}}"></script>
<script src="{{asset('js/landing/functions.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js"></script>
<script>
$(document).ready(function() {
  $("#testimonial-slider").owlCarousel({
    items: 4,
    pagination: true,
    autoplay: true,
    loop: true,
    navigation: true,
    dots: false,
    responsive:{
      1000: {items: 4},
      768: {items: 3},
      640: {items: 2},
      370: {items: 1}
    }
  });

  $("#success-alert").show();
  // $("#myWish").click(function showAlert() {
  //   $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
  //     $("#success-alert").slideUp(500);
  //   });
  // });
});
// set the date we're counting down to
var target_date = new Date('Mar 25, 2021 00:00:00').getTime();

// variables for time units
var days, hours, minutes, seconds;

// get tag element
var countdown = document.getElementById('countdown');

// update the tag with id "countdown" every 1 second
setInterval(function () {

    // find the amount of "seconds" between now and target
    var current_date = new Date().getTime();
    var seconds_left = (target_date - current_date) / 1000;

    // do some time calculations
    days = parseInt(seconds_left / 86400);
    seconds_left = seconds_left % 86400;

    hours = parseInt(seconds_left / 3600);
    seconds_left = seconds_left % 3600;

    minutes = parseInt(seconds_left / 60);
    seconds = parseInt(seconds_left % 60);

    // format countdown string + set tag value
    countdown.innerHTML = ''
      + '<span class="h1 font-weight-bold">' + days +  ' </span><span class="separator">: </span>'
      + '<span class="h1 font-weight-bold">' + (hours<10 ? "0" + hours : hours) + ' </span><span class="separator">: </span>'
      + '<span class="h1 font-weight-bold">' + (minutes<10 ? "0" + minutes : minutes) + ' </span><span class="separator">: </span>'
      + '<span class="h1 font-weight-bold">' + (seconds<10 ? "0" + seconds : seconds) + ' </span>';
}, 1000);
</script>

<script>
setInterval(function () {
   document.querySelector("a.regisnotif").click();
}, 5000);
setInterval(function () {
   document.querySelector("a.modal-close").click();
}, 6000);
</script>

@if(Session::has('success_registration'))
    <script type="text/javascript">
        $("#modal-Success").modal("show");
    </script>
    @php
        Session::forget('success_registration');
    @endphp
@endif

</body>

</html>
