<?php
use LFramework\Helpers\Html as Html;
use Application\AssetBundles\IaiAsset as IaiAsset;

/* @var $this LFramework\Base\View */
/* @var $iaiAsset IaiAsset */
$iaiAsset = new IaiAsset();
IaiAsset::register($this);
?>
<main role="main" id="mainArea" class="main-base">
    <section class="color-bg-green carousel-img">
        <div class="container container-no-pad">
            <div class="row">
                <!-- Carousel Slideshow -->
                <div id="carousel-example" class="carousel slide" data-ride="carousel">
                    <!-- Carousel Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example" data-slide-to="1"></li>
                        <li data-target="#carousel-example" data-slide-to="2"></li>
                    </ol>
                    <div class="clearfix"></div>
                    <!-- End Carousel Indicators -->
                    <!-- Carousel Images -->
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="<?= LFramework::$application->
                                getAssetManager()->getAssetUrl($iaiAsset, "img/slideshow/slide1.jpg") ?>"
                                class="img-resp">
                        </div>
                        <div class="item">
                            <img src="<?= LFramework::$application->
                                getAssetManager()->getAssetUrl($iaiAsset, "img/slideshow/slide2.jpg") ?>"
                                class="img-resp">
                        </div>
                        <div class="item">
                            <img src="<?= LFramework::$application->
                                getAssetManager()->getAssetUrl($iaiAsset, "img/slideshow/slide3.jpg") ?>"
                                class="img-resp">
                        </div>
                        <div class="item">
                            <img src="<?= LFramework::$application->
                                getAssetManager()->getAssetUrl($iaiAsset, "img/slideshow/slide4.jpg") ?>"
                                class="img-resp">
                        </div>
                    </div>
                    <!-- End Carousel Images -->
                    <!-- Carousel Controls -->
                    <a class="left carousel-control color-bg-transp" href="#carousel-example" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <a class="right carousel-control color-bg-transp" href="#carousel-example" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                    <!-- End Carousel Controls -->
                </div>
                <!-- End Carousel Slideshow -->
            </div>
        </div>
    </section>
    <section class="padding-sm">
        <header class="header">
                    <h2 class="animate fadeIn">News</h2>
                    </header>
            <div class="container">
                <div class="row grid">
                    <div class="">
                    <div class="col-md-4">
                        <div class="card">
                            <a href="/" class="">
                                <img src="<?= LFramework::$application->
                                    getAssetManager()->getAssetUrl($iaiAsset, "img/blog/image1.jpg"); ?>" class="news-img-default"/>
                            </a>
                        </div>
                        <div>
                            <div class="padding-top-10">
                                <span class="fa fa-facebook-square fa-lg color-blue"></span>  
                                <span><b>excellenceNews</b></span>
                            </div>
                            <div class="padding-top-20">
                                <h3 class="lead">2016 Back to school!</h3>
                                <p class="">Enlighten offers you the canvas to turn your imagination in to a reality
                                 giving you the perfect framework for your project!</p>
                            </div>
                            <div>
                                <a href="/">
                                    Read More 
                                    <span class="glyphicon glyphicon-play-circle"></span>
                                </a>
                            </div>
                        </div>
                    </div>  
                    <div class="col-md-4">
                        <div class="card">
                            <a href="/" class="">
                                <img src="<?= LFramework::$application->
                                    getAssetManager()->getAssetUrl($iaiAsset, "img/blog/image2.jpg"); ?>" class="news-img-default"/>
                            </a>
                        </div>
                        <div>
                            <div class="padding-top-10">
                                <span class="fa fa-facebook-square fa-lg color-blue"></span>  
                                <span><b>excellenceNews</b></span>
                            </div>
                            <div class="padding-top-20">
                                <h3 class="lead">2016 Back to school!</h3>
                                <p class="">Enlighten offers you the canvas to turn your imagination in to a reality
                                 giving you the perfect framework for your project!</p>
                            </div>
                            <div>
                                <a href="/">
                                    Read More 
                                    <span class="glyphicon glyphicon-play-circle"></span>
                                </a>
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="card">
                            <a href="/" class="">
                                <img src="<?= LFramework::$application->
                                    getAssetManager()->getAssetUrl($iaiAsset, "img/blog/image3.jpg"); ?>" class="news-img-default"/>
                            </a>
                        </div>
                        <div>
                            <div class="padding-top-10">
                                <span class="fa fa-facebook-square fa-lg color-blue"></span>  
                                <span><b>excellenceNews</b></span>
                            </div>
                            <div class="padding-top-20">
                                <h3 class="lead">2016 Back to school!</h3>
                                <p class="">Enlighten offers you the canvas to turn your imagination in to a reality
                                 giving you the perfect framework for your project!</p>
                            </div>
                            <div>
                                <a href="/">
                                    Read More 
                                    <span class="glyphicon glyphicon-play-circle"></span>
                                </a>
                            </div>
                        </div>
                    </div> 
                </div>
                </div>
            </div>
        </section>
    <section class="color-bg-green padding-sm text-white">
        <header class="header">
                <h2 class="animate fadeIn color-txt-white">Studying at IAI Cameroon</h2>
                </header>
        <div class="container">
            <div class="row grid">
                <!-- Main Text -->
                
                <div class="col-md-4">
                    <div class="">
                    <div class="card">
                        <a href="/">
                            <img scr="<?= $iaiAsset->getBaseUrl(); ?>/img/portfolio/image1.jpg" class="news-img-default"/>
                        </a>
                    </div>
                    <div class="padding-xs">
                        <a href="">
                            <h3 class="">Undergraduate Studies</h3>
                        </a>
                        <p class=""></p>
                    </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="">
                    <div class="card">
                        <a href="/">
                            <img scr="<?= $iaiAsset->getBaseUrl(); ?>/img/portfolio/image2.jpg" class="news-img-default"/>
                        </a>
                    </div>
                    <div class="padding-xs">
                        <a href="">
                            <h3 class="">Undergraduate Admission</h3>
                        </a>
                        <p class=""></p>
                    </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="">
                    <div class="card">
                        <a href="">
                            <img scr="<?= $iaiAsset->getBaseUrl(); ?>/img/portfolio/image3.jpg" class="news-img-default"/>
                        </a>
                    </div>
                    <div class="padding-xs">
                        <a href="">
                            <h3 class="">Continuing Education</h3>
                        </a>
                        <p class=""></p>
                    </div>
                        </div>
                </div>
            </div>
        </div>
    </section>
    <section class="padding-sm">
        <div class="container">
            <div class="row grid">
                <div class="">
                <div class="col-md-4">
                    <header class="header">
                        <h2 class="">
                            About IAI Cameroon
                        </h2>
                    </header>
                        <div class="padding-top-20">
                            <div class="card">
                            <a href="" class="">
                                <img src="<?= $iaiAsset->getBaseUrl(); ?>/img/portfolio/image3.jpg"
                                class="news-img-default"/>
                            </a>
                            </div>
                        <p class="">
                            IAI Cameroon "Centre d'Excellence Technologiue Paul Biya
                        </p>
                        <span class="fa fa-play-circle">More</span>
                    </div>  
                </div> 
                <div class="col-md-4 margin-top-30">
                    <div class="padding-top-50">
                        <p>
                            This Forum examined whether these efforts to extend Medicaid coverage have improved the health care experiences of low-income Americans and have narrowed the gap in access to high-quality care between themselves and other Americans. 
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <header class="header">
                        <h2>Events</h2>
                    </header>
                    <div class="padding-top-20">
                                    <h6 style="margin: 0;">Mon - Wed</h6>
                                    <h4 style="margin: 0;">7:00 am - 1:30 pm</h4>
                                    <p>
                                        <small>Continuing education program classes</small>
                                    </p>
                                    <hr>
                                    <h6 style="margin: 0;">Wednesday</h6>
                                    <h4 style="margin: 0;">12:00 am - 1:30 pm</h4>
                                    <p>
                                        <small>Sport Session</small>
                                    </p>
                                    <hr>
                                </div>
                    </div>
                </div>
            </div>
            </div>
    </section>
    <section class="color-bg-black padding-sm newsletter">
        <div class="container">
            <div class="row text-center padding-vert-30">
                <h2 class="padding-bottom-40 color-txt-white">
                    Get the latest IAI news delivered to your inbox
                </h2>
                <form class="form-inline" role="form">
                    <div class="form-group">
                        <input type="email" class="form-control input-group-lg" id="email"
                            placeholder="youremail@email.com"/>
                    </div> 
                    <button type="submit" class="btn btn-primary btn-lg color-bg-transp">Subscribe</button>
                </form>
            </div>  
        </div>
    </section>
    <section class="padding-sm">
        <header class="header">
            <h2>Multimedia</h2>
        </header>
        <div class="container">
            <div class="row grid">
                <div class="">
                <div class="col-md-8">
                    <div class="card">
                        <iframe src="https://youtu.be/iMK33cGjBIg" height="300" class="center-block"></iframe>
                    </div>
                    <div class="">
                        <div class="padding-top-10">
                                <span class="fa fa-youtube fa-lg color-blue"></span>  
                                <span><b>youtube</b></span>
                            </div>
                            <div class="padding-top-20">
                                <h3 class="lead">2016 Back to school!</h3>
                                <p class="">Enlighten offers you the canvas to turn your imagination in to a reality
                                 giving you the perfect framework for your project!</p>
                            </div>
                    </div>     
                </div>
                <div class="col-md-4">
                    
                </div>
            </div>
            </div>
            
        </div>
        
    </section>
</main>