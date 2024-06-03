<style>
         /* adds some margin below the link sets  */
         .navbar .dropdown-menu div[class*="col"] {
         margin-bottom:1rem;
         }
         .navbar .dropdown-menu {
         border:none;
         }
     
     .navbar .dropdown {
    position: relative !important;
}

li.nav-item.dropdown.mega-menu {
    position: static !important;
}
         /* breakpoint and up - mega dropdown styles */
         @media screen and (min-width: 992px) {
         /* remove the padding from the navbar so the dropdown hover state is not broken */
         .navbar {
         padding-top:0px;
         padding-bottom:0px;
         }
         /* remove the padding from the nav-item and add some margin to give some breathing room on hovers */
  .navbar .nav-item {
    padding: .0rem 0rem;
    margin: 0 0rem;
}
.mega-menu .dropdown-menu a.nav-link {
    padding: 3px 38px !important;
    font-size: 17px;
    font-weight: 600;
  color: #262626;
}

.mega-menu .dropdown-menu ul li a.nav-link  {
    padding: 4px 40px !important;
    font-size: 15px;
  font-weight: 500;
  color: #262626;
}
         /* makes the dropdown full width  */
         .navbar .dropdown {position:static;}
     
.navbar .mega-menu .dropdown-menu {
    width: 98%;
    left: 15px;
    right: 0;
    top: 45px;
    display: block;
    visibility: hidden;
    opacity: 0;
transition: visibility 0s, opacity 0.3s linear;} 

/*         .navbar .mega-menu .dropdown-menu {
    width: 100%;
    left: 0;
    right: 0;
    top: 45px;
    display: block;
    visibility: hidden;
    opacity: 0;
transition: visibility 0s, opacity 0.3s linear;} */


         .navbar .dropdown:hover .dropdown-menu, .navbar .dropdown .dropdown-menu:hover {
         display:block;
         visibility: visible;
         opacity: 1;
         transition: visibility 0s, opacity 0.3s linear;
         }
         .navbar .dropdown-menu {
         border: 1px solid rgba(0,0,0,.15);
         background-color: #fff;
         }
         }
     
li.dropdown.language-selector {
    height: 35px;
    border-radius: 4px;
    padding: 4px;
    top: 0px;
    float: right;
    margin-right: 30px;
    list-style: none;
}

.language-selector .dropdown-menu>li>a {
    padding: 15px 10px;
    margin-right: 0;
    color: #262626;
}

.dropdown.language-selector li {
    margin: 8px 0px;
}

.getconbtnbx .language-selector .dropdown-menu {
  left: auto;
  right: 28px;
  top: 48px;
  border: 0;
  min-width: 179px;
  padding: 10px 10px 10px 10px;
  border-bottom: 2px solid #279eff;
  border-radius: 8px;
  box-shadow: 0 10px 40px 0 rgb(39 158 255 / 15%)
}

.language-selector img {
  width: 20px
}

.getconbtnbx a {
  text-decoration: none;
  display: inline-block;
  margin-right: 20px
}

.language-selector:hover ul.dropdown-menu{display: block;}

li.nav-item {
    position: relative;
}

.mega-menu .dropdown-menu a.nav-link {
    position: relative;
}

.dropdown-item{
    padding:.5rem 1.5rem!important
}
.navbar .dropdown .dropdown-menu:hover,.navbar .dropdown:hover .dropdown-menu{
    display:block!important;
    visibility:visible;
    opacity:1;
    transition:visibility 0s,opacity .3s linear
}
.navbar-nav li:hover>.dropdown-menu{
    display:block
}
.dropdown-submenu{
    position:relative
}
.dropdown-submenu>.dropdown-menu{
    top:0;
    left:100%;
    margin-top:-6px
}
.dropdown-menu>li>a:hover:after{
    text-decoration:underline;
    transform:rotate(-90deg)
}
.page-header{
    background:#f5f5f5;
    width:100%;
    box-shadow:0 0 10px 0 rgba(0,0,0,.2)
}
.rd-navbar-panel{
    position:relative;
    display:flex;
    align-items:center;
    width:100%;
    padding:15px 15px 15px 0;
    margin-bottom:2px;
    max-width:260px;
    min-width:200px;
    z-index:1;
    height:77%
}
.rd-navbar-panel:before{
    position:absolute;
    content:'';
    top:0;
    right:0;
    bottom:0;
    width:70vw;
    background:#fff;
    transform:skewX(16deg);
    transform-origin:46% 22%;
    will-change:transform;
    pointer-events:none;
    z-index:-1;
    border-right:3px solid #2788c9;
    box-shadow:0 5px 7px -8px #777
}
.rd-navbar-aside:after{
    background-color:#3d94cd;
    position:absolute;
    right:-19px;
    width:50px;
    height:59px;
    content:'';
    top:0;
    z-index:-9
}
.rd-navbar-aside{
    position:relative;
    display:flex;
    justify-content:flex-end;
    padding:15px 0 2px 15px;
    z-index:1
}
.rd-navbar-aside::before{
    position:absolute;
    content:'';
    top:0;
    bottom:0;
    left:0;
    width:80vw;
    background:#3d94cd;
    transform:skewX(16deg);
    transform-origin:50% 90%;
    will-change:transform;
    pointer-events:none;
    z-index:-1;
    box-shadow:0 5px 7px -8px #777;
    overflow:hidden
}
.fixed{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    background-color:#fff;
    z-index:9;
    transition:.8s all cubic-bezier(.65,.05,.36,1);
    box-shadow:0 0 6px 3px #dddfe0;
    z-index:9999
}
.fixed .rd-navbar-panel{
    position:relative;
    display:flex;
    align-items:center;
    width:100%;
    padding:15px 15px 10px 0;
    margin-bottom:22px;
    max-width:260px;
    min-width:200px;
    z-index:1;
    height:auto
}
.contact-info{
    font-size:15px;
    width:100%;
    color:#fff
}
.phone_t{
    float:left;
    padding-top:5px;
    letter-spacing:1px;
    font-size:16px;
    margin-left:0;
    color:#fff
}
.phone_t .fa{
    font-size:20px;
    color:#fff
}
ul.socialIcons{
    padding:0;
    text-align:center;
    float:right;
    margin-bottom:0;
    margin-left:0
}
.socialIcons li{
    list-style:none;
    display:inline-block;
    margin:0 2px;
    border-radius:2em;
    overflow:hidden
}
.socialIcons li a{
    display:block;
    padding:.5em;
    max-width:2.3em;
    min-width:2.3em;
    height:2.3em;
    white-space:nowrap;
    line-height:1.5em;
    transition:.5s;
    text-decoration:none;
    font-family:arial;
    color:#fff
}
.google{
    background:#ed4335
}
.linkdin{
    background-color:#0077b7
}
.socialIcons li i{
    margin-right:.5em
}
.socialIcons .facebook{
    background:#3b5998;
    box-shadow:0 0 5px #3b5998
}
.socialIcons .twitter{
    background:#00aced;
    box-shadow:0 0 5px #00aced
}
.socialIcons .instagram{
    background:#cd486b;
    box-shadow:0 0 5px #cd486b
}
.socialIcons .pinterest{
    background:#c92228;
    box-shadow:0 0 5px #c92228
}
.socialIcons .steam{
    background:#ff9007;
    box-shadow:0 0 5px #666;
    text-align:center
}
.socialIcons .steam i{
    margin-right:0!important
}
span.nav-w{
    font-size:17px;
    font-weight:600
}
a.nav-link{
    padding:11px 20px!important;
    font-size:17px
}
.padd-50{
    padding:80px 0 100px
}
.page-header .bg-light{
    padding-right:0!important
}
.dropdown-toggle::after{
    display:none!important
}
a.dropdown-item{
    font-size:14px;
    padding:7px 12px!important
}
.dropdown-menu{
    padding:0 0!important;
    margin:0!important
}
.page-header .bg-light{
    background-color:transparent!important;
    margin-top:2px
}
.dropdown-item:focus,.dropdown-item:hover{
    color:#fff!important;
    text-decoration:none;
    background-color:#ff9f02!important
}
.dropdown-menu{
    border-radius:0!important
}
.navbar-light .navbar-nav .nav-link{
    color:rgba(0,0,0,.6);
    font-weight:500
}
.navbar-light .navbar-nav .active>.nav-link{
    color:#0d78bf!important
}
.fixed .bg-light{
    background-color:transparent!important;
    margin-top:9px
}
      </style>
<header class="page-header">
         <div class="container-fluid"style="height: 63px;">
            <div class="row">
               <div class="col-lg-2 col-md-3 col-xs-3">
                  <div class="rd-navbar-panel">
                     <div class="rd-navbar-brand"><a class="brand" href="/">
                        <img class="brand-logo-dark" src="{{ asset('misha-infotech-brand-logo.png')}}" alt="Misha Infotech" width="200px"></a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-10 col-md-9 col-xs-9">
                  <div class="rd-navbar-aside">
                     <div class="contact-info">
                        <span class="phone_t">   <i class="fa fa-phone"></i><a href="tel: +1-(917) 688-4245" style="color: white;"> <b style="color: white;">+1-(917) 688-4245</b></a></span>
                        <span class="phone_t pl-5">   <i class="fa fa-map-marker" aria-hidden="true"></i>  1299, 4th Street, Suite 508, CA USA</span>
                        <ul class="socialIcons">
					
                           <li class="facebook"><a href="https://www.facebook.com/misha.infotech" target="_blank"><i class="fa fa-fw fa-facebook"></i>Facebook</a></li>
                           <li class="linkdin"><a href="https://www.linkedin.com/company/misha-infotech-private-limited" target="_blank"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                           <li class="twitter"><a href="https://twitter.com/MishaInfotech" target="_blank"><i class="fa fa-fw fa-twitter"></i>Twitter</a></li>
                           <li class="pinterest"><a href="https://www.pinterest.com/mishainfotech/" target="_blank"><i class="fa fa-fw fa-pinterest-p"></i>Pinterest</a></li>
						   	<li><a href="https://misha-infotech.medium.com/" target="_blank" style="padding: 0px;"><img src="images/mediam.png" alt="Mediam"></a></li>
                           <li class="steam"><a href="https://feeds.feedburner.com/Misha-Infotech-Blog" target="_blank"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                        </ul>						
                        <li class="dropdown language-selector">
                          Language:  
                          <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                            <img src="{{asset('images/flag-uk.png')}}" alt="logo" />
                          </a>
                          <ul class="dropdown-menu pull-right">

                            <li>
                             <a href="javascript:void(0);" id="englishLang" onclick="doGTranslate('en|en'); disabledText(this, 'englishLang');" title="English" class="flag nturl flag1" style="">
                             <img src="{{asset('assets/images/flag-uk.png')}}" alt="logo" />
                            </span><span>English</span>
                            </a>                                        
                          </li>
                          <li class="franch">
                           <a href="javascript:void(0);" id="frenchLang" onclick="doGTranslate('en|fr'); disabledText(this, 'frenchLang');" title="French" class="flag nturl flag2" style="">
                            <img src="{{asset('assets/images/flag-fr.png')}}" alt="logo" />
                            <span>French</span>
                          </a>
                        </li>

                        <li>
                         <a href="javascript:void(0);" id="arabicLang" onclick="doGTranslate('en|ar'); disabledText(this, 'arabicLang');" title="Arabic" class="flag nturl flag3" style="">
                          <img src="images/flag-round.png" alt="logo" />
                          <span>Arabic</span>
                        </a>
                      </li>
                      <li class="spanish">
                    <a href="javascript:void(0);" id="spanishLang" onclick="doGTranslate('en|es'); disabledText(this, 'spanishLang');" title="Spanish" class="flag nturl flag4" style="">
                          <img src="images/flag-es.png" alt="logo" />
                          <span>Spanish</span>
                        </a>
                      </li>
                      <li class="german">
                       <a href="javascript:void(0);" id="germanLang" onclick="doGTranslate('en|de'); disabledText(this, 'germanLang');" title="German" class="flag nturl flag5" style="">
                          <img src="images/flag-de.png" alt="logo" />
                          <span>German</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                     </div>
                  </div>
                
               </div>
            </div>
			
			
         </div>
		 
	
			    <nav class="navbar navbar-expand-lg navbar-light bg-light">
				  <div class="container-fluid">
                     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                     <span class="navbar-toggler-icon"></span>
                     </button>
                     <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mx-auto">
						
                          <li class="nav-item">
                              <a class="nav-link" href="/">Home</a>
                           </li>
                     <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target=""> About us</a>
                     
                     <ul class="dropdown-menu about pt-3" aria-labelledby="navbarDropdownMenuLink">
                                 <li><a class="dropdown-item" href="about-company"><span class="icon about-i"></span>About Company</a></li>
                                 <li><a class="dropdown-item" href="why-misha-infotech"><span class="icon why"></span>Why Misha Infotech</a></li>
                                 <li><a class="dropdown-item" href="mission-vision"><span class="icon mis"></span>Mission/Vision</a></li>
                                 <li><a class="dropdown-item" href="our-skill-set"><span class="icon silk"></span>Our Skill Set</a></li>
                                 <li><a class="dropdown-item" href="our-values"><span class="icon valu"></span>Our values</a></li>
                     </ul>
                     
                     </li>
                           <li class="nav-item dropdown mega-menu">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             Services
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                 <div class="container-fluid mt-2">
                                    <div class="row">
                                       <div class="col-md-3 mt-2">
									       <!--<a class="nav-link" href="#"> <span class="nav-w icon mobile-app"></span>Mobile App Development</a>-->
   
											  
                                            <a class="nav-link myBtnUrl" href="ios-application-development" data-val="iOS App Development"><span class="icon iphone"></span>iOS App Development</a>
											   <a class="nav-link" href="android-application-development"><span class="icon android"></span>Android App Development</a>
											   <a class="nav-link" href="hybrid-framework-app-development"> <span class="icon uiux"></span>Hybrid App Development</a>
										  
                                          <ul class="nav flex-column ml-4">
										   <li class="nav-item">
                                                <a class="nav-link" href="react-native-app-development"><span class="icon react"></span>React Native App Development</a>
                                             </li>
                                             <li class="nav-item">
                                                <a class="nav-link" href="ionic-app-development"><span class="icon ionic"></span>Ionic App Development</a>
                                             </li>
											 <li class="nav-item">
                                                <a class="nav-link" href="flutter-app-development"><span class="icon flatter"></span>Flutter App Development</a>
                                             </li>
											  <li class="nav-item">
                                                <a class="nav-link" href="phonegap-app-development"><span class="icon phonegap"></span>PhoneGap App Development</a>
                                             </li>
	
                                          </ul>	
											 	 <a class="nav-link mt-2" href="ui-ux-development"> <span class="icon uiux"></span>UI/UX Development</a>
												  <a class="nav-link" href="pwa-development"> <span class="icon pwa"></span>PWA Development</a>
												   <a class="nav-link" href="ar-vr-development"> <span class="icon cross"></span>AR VR Development</a>
												    <a class="nav-link" href="chatbots-development"> <span class="icon chat"></span>ChatBots Development</a>
											  <ul class="nav flex-column ml-4">
	
                                          </ul>
										
                                       </div>
                                       <!-- /.col-md-3  -->
                                       <div class="col-md-3 mt-3">
                                            <a href="digital-marketing-services" class="nav-link"> <span class="icon smm-icon"></span>Digital Marketing</a>
								
										     <ul class="nav flex-column ml-4">
                                             <li class="nav-item">
                                                <a class="nav-link" href="global-seo"><span class="icon seo-icon"></span>Global SEO</a>
                                             </li>
											  <li class="nav-item">
                                                <a class="nav-link" href="local-seo"><span class="icon seo-l"></span>Local SEO</a>
                                             </li>
											 <li class="nav-item">
                                                <a class="nav-link" href="pay-per-click"><span class="icon ppc-icon"></span>Pay Per Click</a>
                                             </li>
											  <li class="nav-item">
                                                <a class="nav-link" href="social-media-marketing"><span class="icon smm-icon"></span>Social Media Marketing</a>
                                             </li>
    										 
											 <li class="nav-item">
                                                <a class="nav-link" href="link-building"><span class="icon link"></span>Link Building</a>
                                             </li>
	
                                          </ul>
										  
										   <a class="nav-link"> <span class="icon cust"></span> Web Design &amp; HTML</a>
									 
                                          <ul class="nav flex-column ml-4">
                                             <li class="nav-item">
                                                <a class="nav-link" href="logo-designing-and-corporate-identity"><span class="icon logo"></span> Logo Designing & Corporate Identity</a>
                                             </li>
											 <li class="nav-item">
                                                <a class="nav-link" href="graphic-designing-and-illustrations"><span class="icon uiux"></span> Graphic Designing & Illustrations</a>
                                             </li>
										
                                             <li class="nav-item">
                                                <a class="nav-link" href="brochure-designing"><span class="icon brochur"></span> Brochure Designing</a>
                                             </li>
                                            
											 <li class="nav-item">
                                                <a class="nav-link" href="microsites-and-landing-pages"><span class="icon microsites"></span> Microsites and Landing Pages</a>
                                             </li>
                                          </ul>
										  
								
                                       </div>
                                       <!-- /.col-md-3  -->
                                      <div class="col-md-3 mt-2">
									  	

                                         
										 	  <a class="nav-link"> <span class="icon portal"></span> Web/App Development</a>
                                   
                                          <ul class="nav flex-column ml-4">
										  <li class="nav-item">
                                                <a class="nav-link" href="website-development-services"><span class="icon cross"></span>Website Development</a>
                                             </li>
                                           <li class="nav-item">
                                                <a class="nav-link" href="custom-software-development"><span class="icon software"></span>Custom Software Development</a>
                                             </li>
											 <li class="nav-item">
                                                <a class="nav-link" href="asp-net-development"><span class="icon dot"></span>ASP.Net Development</a>
                                             </li>
											  <li class="nav-item">
                                                <a class="nav-link" href="custom-php-development"><span class="icon pph"></span>PHP Development</a>
                                             </li>
                                          <li class="nav-item">
                                                <a class="nav-link" href="ecommerce-solutions"><span class="icon eco"></span>eCommerce Web Development</a>
                                             </li>
                                            <!-- <li class="nav-item">
                                                <a class="nav-link" href="product-development"><span class="icon product"></span>Product Development</a>
                                             </li>	-->
                                          </ul>	
										   <a class="nav-link"> <span class="icon framework"></span> Framework Development</a>
										   <ul class="nav flex-column ml-4">
                                           <li class="nav-item">
                                                <a class="nav-link" href="laravel-cake-zend-framework"><span class="icon laravel"></span>Laravel/Zend/CakePHP</a>
                                             </li>
											 <li class="nav-item">
                                                <a class="nav-link" href="symfony-yii-codeIgniter"><span class="icon symfony"></span>Symfony/Yii/CodeIgniter</a>
                                             </li>											
                                          </ul>	
										  
										   <a class="nav-link"> <span class="icon portal"></span> Opensource Development</a>
										   <ul class="nav flex-column ml-4">
                                           <li class="nav-item">
                                                <a class="nav-link" href="joomla-drupal-content-management-system"><span class="icon joomla"></span>Joomla/Drupal</a>
                                             </li>
											  <li class="nav-item">
                                                  <a class="nav-link" href="cms-web-development"><span class="icon wordp"></span>WordPress</a>
                                             </li>
											 <li class="nav-item">
                                                <a class="nav-link" href="custom-moodle-development"><span class="icon moodle"></span>Moodle</a>
                                             </li>											
                                          </ul>
								  
                                       </div>
									   
									   <div class="col-md-3 mt-2">
							
	<a class="nav-link" href="database-tuning-and-optimization"> <span class="icon Big-Data-icon"></span>Database Consulting</a>
										 
	 <ul class="nav flex-column ml-4">
	 <li class="nav-item">
		<a class="nav-link" href="database-design-and-planning"><span class="icon customer-data"></span>Database Design &amp; Planning</a>
	 </li>
	 <li class="nav-item">
		<a class="nav-link" href="database-troubleshooting-and-triage"><span class="icon download"></span>Database Troubleshooting &amp; Triage</a>
	 </li>
	  <li class="nav-item">
		<a class="nav-link" href="database-tuning-and-optimization"><span class="icon remove-data"></span>Database Tuning &amp; Optimization</a>
	 </li>
	 <li class="nav-item">
		<a class="nav-link" href="database-backup-and-recovery-planning"><span class="icon Big-Data-icon"></span>Database Backup &amp; Recovery</a>
	 </li>
	 <li class="nav-item">
		<a class="nav-link" href="data-warehouse-design"><span class="icon warehouse"></span>Data Warehouse Design</a>
	 </li>

  </ul>	
  
 <a class="nav-link"> <span class="icon maintenance"></span>Product Engineering</a>
										
										     <ul class="nav flex-column ml-4">
                                             <li class="nav-item">
                                                <a class="nav-link" href="new-software-product-development"><span class="icon domain"></span>New Software Product Development</a>
                                             </li>
											 <li class="nav-item">
                                                <a class="nav-link" href="qa-and-testing"><span class="icon testin"></span>QA &amp; Testing</a>
                                             </li>
											  <li class="nav-item">
                                                <a class="nav-link" href="product-maintenance-and-sustenance"><span class="icon maintenance"></span>Product Maintenance &amp; Sustenance</a>
                                             </li>
                                             <li class="nav-item">
                                                <a class="nav-link" href="product-re-engineering-and-migration"><span class="icon maintenance2"></span>Product Re-engineering &amp; Migration</a>
                                             </li>
	
                                          </ul>	
						  
                                       </div>
                                       <!-- /.col-md-3  -->
                                    </div>
                                 </div>
                                 <!--  /.container  -->
                              </div>
                           </li>
						   
					<li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target=""> Solutions</a>
                     
                     <ul class="dropdown-menu about pt-3" aria-labelledby="navbarDropdownMenuLink">
                                 <li><a class="dropdown-item" href="taxi-app-solution"><span class="icon taxi"></span>Taxi App Solution</a></li>
                                 <li><a class="dropdown-item" href="food-grocery-app-solution"><span class="icon food"></span>Food & Grocery App Solution</a></li>
                                 <li><a class="dropdown-item" href="on-demand-video-streaming-app"><span class="icon mis"></span>On Demand Video Streaming App Solution</a></li>
                                 <li><a class="dropdown-item" href="dating-app-solution"><span class="icon silk"></span>Dating App Solution</a></li>
                                 <li><a class="dropdown-item" href="ecommerce-app-solution"><span class="icon eco"></span>eCommerce App Solution</a></li>
								 <li><a class="dropdown-item" href="mlm-app-solution"><span class="icon outsourc2"></span>MLM App Solution</a></li>
								 <li><a class="dropdown-item" href="cryptocurrency-exchange-app"><span class="icon delivery"></span>Cryptocurrency Exchange App Solution</a></li>
                     </ul>
                     </li>
						   
						    <li class="nav-item dropdown mega-menu">
                              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Products
                              </a>
                              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                 <div class="container-fluid mt-2">
                                    <div class="row">
                                       <div class="col-md-4 mt-2">										  
                                          <ul class="nav flex-column ml-4">
										   <li class="nav-item">
                                                <a class="nav-link" href="misha-erp"><span class="icon erp"></span>Misha ERP</a>
                                             </li>
                                             <li class="nav-item">
                                                <a class="nav-link" href="e-vidyalaya-school-management-system"><span class="icon school"></span>E-Vidyalaya-School Management System</a>
                                             </li>
											 <li class="nav-item">
                                                <a class="nav-link" href="human-resource-management-system"><span class="icon hrms"></span>Human Resource Management System</a>
                                             </li>
											  <li class="nav-item">
                                                <a class="nav-link" href="inventory-management-system"><span class="icon inventory"></span>Inventory Management System</a>
                                             </li>
                                          </ul>	
                                       </div>
                                       <!-- /.col-md-3  -->
                                       <div class="col-md-4 mt-3">
                          							
										     <ul class="nav flex-column ml-4">
                                             <li class="nav-item">
                                                <a class="nav-link" href="satya-payroll-management-software"><span class="icon money-transfer"></span>Satya Payroll Management Software</a>
                                             </li>
											  <li class="nav-item">
                                                <a class="nav-link" href="point-of-sale-(pos)"><span class="icon pos"></span>Point Of Sale (POS)</a>
                                             </li>
											 <li class="nav-item">
                                                <a class="nav-link" href="i-display"><span class="icon ppc-icon"></span>I-display</a>
                                             </li>
											  <li class="nav-item">
                                                <a class="nav-link" href="crm"><span class="icon smm-icon"></span>CRM</a>
                                             </li>										 
											
                                          </ul>
                                       </div>
                                       <!-- /.col-md-3  -->
                                      <div class="col-md-4 mt-2">                                   
                                          <ul class="nav flex-column ml-4">
                            
											  <li class="nav-item">
                                                <a class="nav-link" href="learning-management-system"><span class="icon school"></span>Learning Management System</a>
                                             </li>
                                             <li class="nav-item">
                                                <a class="nav-link" href="advantage-queue-management-system"><span class="icon product"></span>Advantage Queue Management System</a>
                                             </li>	
                                          </ul>	
										  
								  
                                       </div>
									   
									   <div class="col-md-3 mt-2">

 </div>
                                       <!-- /.col-md-3  -->
                                    </div>
                                 </div>
                                 <!--  /.container  -->
                              </div>
                           </li>
						   

					 
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target=""> Process</a>

							<ul class="dropdown-menu about" aria-labelledby="navbarDropdownMenuLink">
								<li><a class="dropdown-item" href="development-process"><span class="icon pross"></span> Process</a></li>
								<li><a class="dropdown-item" href="ip-protection"><span class="icon security"></span>IP Protection</a></li>
								 <li><a class="dropdown-item" href="outsourcing-advantage"><span class="icon outsourc"></span>Outsourcing Advantages</a></li>
											<li><a class="dropdown-item" href="outsourcing-mishainfotech"><span class="icon outsourc2"></span>Outsourcing @ Mishainfotech?</a></li>
											 <li><a class="dropdown-item" href="pricing-models"><span class="icon icon money-transfer"></span>Pricing Models</a></li>
											<li><a class="dropdown-item" href="delivery-model"><span class="icon delivery"></span>Delivery Model</a></li>
											<li><a class="dropdown-item" href="offshore-development-centre"><span class="icon offshare"></span>Offshore Development Centre</a></li>
								<li><a class="dropdown-item" href="quality"><span class="icon qulity"></span>Quality</a></li>
							</ul>

						</li>
		
		        <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target=""> Partnership</a>

                    <ul class="dropdown-menu about" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="partners-program"><span class="icon partner"></span>Partners Program</a></li>
                                    <li><a class="dropdown-item" href="reseller-program"><span class="icon reseller2"></span>Reseller Program</a></li>
                    </ul>

        </li>
		
	
        <!--<li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="portfolio" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target=""> Portfolio</a>
        </li>-->
		
		  <li class="nav-item dropdown">
                     <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target=""> Portfolio</a>
                     
                     <ul class="dropdown-menu about pt-3" aria-labelledby="navbarDropdownMenuLink">
						<li><a class="dropdown-item" href="custom-mobile-app-development"><span class="icon about-i"></span>Custom Mobile App Development</a></li>
						<li><a class="dropdown-item" href="web-applications"><span class="icon why"></span>Web Applications Development</a></li>
						<li><a class="dropdown-item" href="web-designing"><span class="icon mis"></span>Website Designing & Development</a></li>
						<li><a class="dropdown-item" href="digital-marketing-portfolio"><span class="icon silk"></span>Online Growth Marketing</a></li>
						<li><a class="dropdown-item" href="javascript:void(0)"><span class="icon valu"></span>Enterprise Level Applications</a></li>
						<li><a class="dropdown-item" href="javascript:void(0)"><span class="icon valu"></span>E-Brochures</a></li>
                     </ul>
                     
                     </li>
			 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target=""> Case Study</a>

                    <ul class="dropdown-menu about" aria-labelledby="navbarDropdownMenuLink">
                                    <li><a class="dropdown-item" href="web-development-case-study"><span class="icon partner"></span>Web Development Case Study</a></li>
                                    <li><a class="dropdown-item" href="digital-marketing-case-study"><span class="icon reseller2"></span>Digital Marketing Case Study</a></li>
                    </ul>

        </li>
		
        <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="https://blog.mishainfotech.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target=""> Blog </a>


        </li>
        <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="jobsandCareersApply" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target="">  Jobs &amp; Career</a>


        </li>
        <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle" href="contact-us" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" target=""> Contact us</a>


        </li>

                        </ul>
                     </div>
					  </div>
                  </nav>
				    
      </header>