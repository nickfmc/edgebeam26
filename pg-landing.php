<?php
/**
 * Template Name: Landing Page
 * Description: Single page landing page with anchor navigation
 */
?>
<!DOCTYPE html>
<html class="no-js" lang="<?php language_attributes(); ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  
  <?php wp_head(); ?>
</head>
 
<body <?php body_class('landing-page ' . pretty_body_class()); ?> itemscope itemtype="https://schema.org/WebPage">

<!-- Skip links -->
<div class="skip-links" role="navigation" aria-label="Skip links navigation">
  <a href="#main-content" class="skip-link">Skip to main content</a>
</div>

<!-- Landing Page Header -->
<header id="c-landing-header" class="c-landing-header" role="banner">
  <div class="o-wrapper-wide c-landing-header__inner">
    <div class="c-landing-header__logo">
      <a href="<?php echo home_url(); ?>" rel="home">
        <img src="<?php bloginfo('template_url') ?>/img/edgebeam-logo.svg" alt="<?php bloginfo('name'); ?>" />
      </a>
    </div>
    
    <nav class="c-landing-nav" role="navigation" aria-label="Landing page navigation">
      <ul class="c-landing-nav__list">
        <li><a href="#about" class="c-landing-nav__link">About</a></li>
        <li><a href="#leadership" class="c-landing-nav__link">Leadership</a></li>
        <li><a href="#careers" class="c-landing-nav__link">Careers</a></li>
        <li><a href="#faqs" class="c-landing-nav__link">FAQs</a></li>
      </ul>
    </nav>
    
    <div class="c-landing-header__cta">
      <a href="#contact" class="c-btn c-btn--primary">Let's Talk</a>
    </div>
    
    <!-- Mobile Menu Toggle -->
    <button class="c-landing-header__mobile-toggle" id="landing-mobile-toggle" aria-expanded="false" aria-controls="landing-mobile-nav">
      <span class="sr-only">Menu</span>
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M3 12H21M3 6H21M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
  </div>
  
  <!-- Mobile Navigation -->
  <div id="landing-mobile-nav" class="c-landing-mobile-nav" aria-hidden="true">
    <nav role="navigation">
      <ul class="c-landing-mobile-nav__list">
        <li><a href="#about">About</a></li>
        <li><a href="#leadership">Leadership</a></li>
        <li><a href="#careers">Careers</a></li>
        <li><a href="#faqs">FAQs</a></li>
        <li><a href="#contact" class="c-btn c-btn--primary">Let's Talk</a></li>
      </ul>
    </nav>
  </div>
</header>

<main id="main-content" class="c-landing-main">
  
  <!-- Hero Section -->
  <section id="hero" class="c-landing-hero">
    <div class="c-landing-hero__bg c-animated-gradient c-animated-gradient--hero"></div>
    <div class="c-landing-hero__decorations">
      <div class="c-landing-hero__circle c-landing-hero__circle--1"></div>
      <div class="c-landing-hero__circle c-landing-hero__circle--2"></div>
    </div>
    <div class="o-wrapper c-landing-hero__content">
      <p class="c-eyebrow">Broadcast-Built. Enterprise-Ready.</p>
      <h1 class="c-landing-hero__title">The Power of One-to-Many, Applied to the Last Mile.</h1>
    </div>
  </section>
  
  <!-- Intro/Coming Soon Section -->
  <section id="intro" class="c-landing-intro">
    <div class="c-landing-intro__bg"></div>
    <div class="o-wrapper-wide c-landing-intro__inner">
      <div class="c-landing-intro__content">
        <p class="c-eyebrow">The World's First Hybrid Network Operator</p>
        <h2>EdgeBeam Wireless — New Website Coming March 2026</h2>
        <div class="c-landing-intro__text">
          <p>EdgeBeam Wireless is transforming broadcast's one-to-many capabilities into a last-mile data distribution network that expands capacity more efficiently and economically for enterprise connectivity — reducing congestion and lowering capital investment. Curious to learn more?</p>
        </div>
        <a href="#contact" class="c-btn c-btn--primary">Let's Talk</a>
      </div>
      <div class="c-landing-intro__visual">
        <div class="c-landing-intro__tower">
          <img src="<?php bloginfo('template_url') ?>/img/broadcast-tower.png" alt="Broadcast Tower" />
        </div>
      </div>
    </div>
  </section>
  
  <!-- About Section -->
  <section id="about" class="c-landing-about">
    <div class="o-wrapper-wide">
      <p class="c-eyebrow">A Domestic, Resilient, Data Distribution Layer</p>
      <h2>At the Intersection of Broadcast and Wireless</h2>
      
      <div class="c-landing-about__grid">
        <div class="c-landing-about__content">
          <p>Formed by four of the nation's leading independent broadcasters — Sinclair, Gray Media, Nexstar Media Group, and The E.W. Scripps Company, EdgeBeam is providing a faster, more secure, and more cost-effective way to move data across the United States.</p>
          <p>With wide-area distribution capabilities designed to complement today's wireless networks, EdgeBeam is offering services no single provider can deliver on its own — revolutionizing what broadcasting can do while creating a game-changing data pipeline for the connected world.</p>
        </div>
        
        <div class="c-landing-about__logos">
          <div class="c-logo-grid">
            <div class="c-logo-grid__item"><img src="<?php bloginfo('template_url') ?>/img/logos/gray-logo.png" alt="Gray Media" /></div>
            <div class="c-logo-grid__item"><img src="<?php bloginfo('template_url') ?>/img/logos/nexstar-logo.png" alt="Nexstar" /></div>
            <div class="c-logo-grid__item"><img src="<?php bloginfo('template_url') ?>/img/logos/scripps-logo.png" alt="Scripps" /></div>
            <div class="c-logo-grid__item"><img src="<?php bloginfo('template_url') ?>/img/logos/sinclair-logo.png" alt="Sinclair" /></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Leadership Section -->
  <section id="leadership" class="c-landing-leadership">
    <div class="c-landing-leadership__bg c-animated-gradient c-animated-gradient--leadership"></div>
    <div class="o-wrapper">
      <h2 class="c-landing-leadership__title">Meet Our Leaders</h2>
      <p class="c-landing-leadership__intro">Driven by innovators who have defined the broadcast, media, telecommunications, and technology landscape, EdgeBeam is being led by:</p>
      
      <div class="c-team-grid">
        <div class="c-team-member">
          <div class="c-team-member__image">
            <img src="<?php bloginfo('template_url') ?>/img/team/conrad.jpg" alt="Conrad Clemson" />
          </div>
          <h3 class="c-team-member__name">Conrad Clemson</h3>
          <p class="c-team-member__title">Chief Executive Officer</p>
        </div>
        
        <div class="c-team-member">
          <div class="c-team-member__image">
            <img src="<?php bloginfo('template_url') ?>/img/team/apoorva.jpg" alt="Apoorva Jain" />
          </div>
          <h3 class="c-team-member__name">Apoorva Jain</h3>
          <p class="c-team-member__title">Chief Product Officer</p>
        </div>
        
        <div class="c-team-member">
          <div class="c-team-member__image">
            <img src="<?php bloginfo('template_url') ?>/img/team/joe.jpg" alt="Joe Fabiano" />
          </div>
          <h3 class="c-team-member__name">Joe Fabiano</h3>
          <p class="c-team-member__title">Chief Technology Officer</p>
        </div>
        
        <div class="c-team-member">
          <div class="c-team-member__image">
            <img src="<?php bloginfo('template_url') ?>/img/team/sasha.jpg" alt="Sasha Javid" />
          </div>
          <h3 class="c-team-member__name">Sasha Javid</h3>
          <p class="c-team-member__title">Vice President EGPS & Professional Services</p>
        </div>
        
        <div class="c-team-member">
          <div class="c-team-member__image">
            <img src="<?php bloginfo('template_url') ?>/img/team/jane.jpg" alt="Jane D'Arcy" />
          </div>
          <h3 class="c-team-member__name">Jane D'Arcy</h3>
          <p class="c-team-member__title">Senior Marketing Manager</p>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Careers Section -->
  <section id="careers" class="c-landing-careers">
    <div class="o-wrapper-wide">
      <p class="c-eyebrow">Let's Build What's Next. Together.</p>
      <h2>Be Part of a Trailblazing Team Revolutionizing Data Delivery</h2>
      
      <div class="c-landing-careers__grid">
        <div class="c-landing-careers__content">
          <p>At EdgeBeam, we believe in empowering people who want to change how the world connects — and we're hiring talented individuals who are ready to make a real impact.</p>
          
          <div class="c-vision-mission">
            <div class="c-vision-mission__item">
              <div class="c-vision-mission__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
              </div>
              <div class="c-vision-mission__text">
                <p>Our <strong>vision</strong> is to enable a global engine of connection through elevated broadcast infrastructure, unlocking new value for industries, communities, and the networks that power them.</p>
              </div>
            </div>
            
            <div class="c-vision-mission__item">
              <div class="c-vision-mission__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <circle cx="12" cy="8" r="7"></circle>
                  <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                </svg>
              </div>
              <div class="c-vision-mission__text">
                <p>Our <strong>mission</strong> is to transform broadcast's powerful one-to-many capabilities into a last-mile data distribution network that expands capacity for enterprise connectivity with greater efficiency and lower cost.</p>
              </div>
            </div>
          </div>
          
          <p><strong>If you want to join a team that values collaboration and fresh thinking, take a look at our current career opportunities.</strong></p>
        </div>
        
        <div class="c-landing-careers__positions">
          <h3 class="c-eyebrow">Open Positions</h3>
          <a href="#" class="c-btn c-btn--primary">View All Open Positions</a>
          <!-- Job listings can be added here -->
        </div>
      </div>
    </div>
  </section>
  
  <!-- FAQs Section -->
  <section id="faqs" class="c-landing-faqs">
    <div class="o-wrapper">
      <h2 class="c-landing-faqs__title">Frequently Asked Questions (FAQs)</h2>
      <p class="c-landing-faqs__subtitle">Get answers to FAQs about EdgeBeam Wireless.</p>
      
      <div class="c-accordion" data-accordion>
        <div class="c-accordion__item" data-accordion-item>
          <button class="c-accordion__trigger" aria-expanded="true" data-accordion-trigger>
            <span class="c-accordion__title">What is EdgeBeam Wireless?</span>
            <span class="c-accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="c-accordion__content" data-accordion-content>
            <p>EdgeBeam Wireless, LLC is the world's first hybrid network operator expanding one-to-many data distribution for today's wireless networks by offering a foundational layer at the edge.</p>
          </div>
        </div>
        
        <div class="c-accordion__item" data-accordion-item>
          <button class="c-accordion__trigger" aria-expanded="false" data-accordion-trigger>
            <span class="c-accordion__title">What is a hybrid network operator?</span>
            <span class="c-accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="c-accordion__content" data-accordion-content hidden>
            <p>A hybrid network operator combines multiple network technologies to deliver data more efficiently across different infrastructure types.</p>
          </div>
        </div>
        
        <div class="c-accordion__item" data-accordion-item>
          <button class="c-accordion__trigger" aria-expanded="false" data-accordion-trigger>
            <span class="c-accordion__title">What does EdgeBeam Wireless do?</span>
            <span class="c-accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="c-accordion__content" data-accordion-content hidden>
            <p>EdgeBeam Wireless provides wide-area data distribution services that complement existing wireless networks, enabling more efficient and cost-effective data delivery.</p>
          </div>
        </div>
        
        <div class="c-accordion__item" data-accordion-item>
          <button class="c-accordion__trigger" aria-expanded="false" data-accordion-trigger>
            <span class="c-accordion__title">What industries does EdgeBeam Wireless serve?</span>
            <span class="c-accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="c-accordion__content" data-accordion-content hidden>
            <p>EdgeBeam serves enterprise customers across various industries requiring reliable, high-capacity data distribution solutions.</p>
          </div>
        </div>
        
        <div class="c-accordion__item" data-accordion-item>
          <button class="c-accordion__trigger" aria-expanded="false" data-accordion-trigger>
            <span class="c-accordion__title">Who is the Chief Executive Officer (CEO) of EdgeBeam Wireless?</span>
            <span class="c-accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="c-accordion__content" data-accordion-content hidden>
            <p>Conrad Clemson serves as the Chief Executive Officer of EdgeBeam Wireless.</p>
          </div>
        </div>
        
        <div class="c-accordion__item" data-accordion-item>
          <button class="c-accordion__trigger" aria-expanded="false" data-accordion-trigger>
            <span class="c-accordion__title">What's happening in March 2026 with EdgeBeam Wireless?</span>
            <span class="c-accordion__icon" aria-hidden="true"></span>
          </button>
          <div class="c-accordion__content" data-accordion-content hidden>
            <p>EdgeBeam Wireless is launching a new comprehensive website in March 2026 with full details about our services and solutions.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- CES Banner Section -->
  <section class="c-landing-ces">
    <div class="c-landing-ces__bg">
      <img src="<?php bloginfo('template_url') ?>/img/ces-bg.jpg" alt="" aria-hidden="true" />
    </div>
    <div class="o-wrapper-wide c-landing-ces__content">
      <h3>See Us at CES</h3>
      <p>What better place than the most powerful tech event in the world, the Consumer Electronics Show (CES), to get a taste of EdgeBeam's frontier edge capabilities? <strong>What's In Store →</strong></p>
    </div>
  </section>
  
  <!-- Contact Section -->
  <section id="contact" class="c-landing-contact">
    <div class="c-landing-contact__image">
      <img src="<?php bloginfo('template_url') ?>/img/contact-image.jpg" alt="" />
    </div>
    <div class="c-landing-contact__form-wrap">
      <div class="c-landing-contact__content">
        <h2>Contact Us</h2>
        <p>Submit the short form below or email <a href="mailto:info@edgebeamwireless.com">info@edgebeamwireless.com</a> to reach out now.</p>
        
        <!-- Gravity Form placeholder - replace [gravityform id="X"] with your form shortcode -->
        <div class="c-contact-form">
          <?php 
          // Check if Gravity Forms shortcode exists
          if ( shortcode_exists( 'gravityform' ) ) {
            // Replace 1 with your actual form ID
            echo do_shortcode('[gravityform id="1" title="false" description="false" ajax="true"]');
          } else {
            // Fallback HTML form for development
          ?>
          <form class="c-form c-form--landing" method="post">
            <div class="c-form__row c-form__row--2col">
              <div class="c-form__field">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" placeholder="Your first name" />
              </div>
              <div class="c-form__field">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" placeholder="Your last name" />
              </div>
            </div>
            
            <div class="c-form__row c-form__row--2col">
              <div class="c-form__field">
                <label for="phone">Phone number</label>
                <input type="tel" id="phone" name="phone" placeholder="+1 (555) 000-0000" />
              </div>
              <div class="c-form__field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="you@company.com" />
              </div>
            </div>
            
            <div class="c-form__row c-form__row--2col">
              <div class="c-form__field">
                <label for="company">Company Name</label>
                <input type="text" id="company" name="company" placeholder="Company Name" />
              </div>
              <div class="c-form__field">
                <label for="position">Position & Title</label>
                <input type="text" id="position" name="position" placeholder="Position & Title" />
              </div>
            </div>
            
            <div class="c-form__row">
              <div class="c-form__field">
                <label for="message">How can we help?</label>
                <textarea id="message" name="message" rows="4" placeholder="Tell us a little about the project..."></textarea>
              </div>
            </div>
            
            <div class="c-form__row c-form__row--submit">
              <button type="submit" class="c-btn c-btn--primary">Let's Talk</button>
            </div>
          </form>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>

</main>

<!-- Landing Page Footer -->
<footer id="c-landing-footer" class="c-landing-footer" role="contentinfo">
  <div class="c-landing-footer__main">
    <div class="o-wrapper-wide c-landing-footer__inner">
      <div class="c-landing-footer__logo">
        <a href="<?php echo home_url(); ?>">
          <img src="<?php bloginfo('template_url') ?>/img/edgebeam-logo-white.svg" alt="<?php bloginfo('name'); ?>" />
        </a>
      </div>
      
      <nav class="c-landing-footer__nav">
        <ul>
          <li><a href="#about">About</a></li>
          <li><a href="#leadership">Leadership</a></li>
          <li><a href="#careers">Careers</a></li>
          <li><a href="#faqs">FAQs</a></li>
        </ul>
      </nav>
      
      <div class="c-landing-footer__contact">
        <div class="c-landing-footer__contact-item">
          <p>1-XXX-XXX-XXXX</p>
        </div>
        <div class="c-landing-footer__contact-item">
          <p>2 Seaport Lane, 8th Floor, Suite XX<br />Boston, MA 02210</p>
        </div>
      </div>
    </div>
  </div>
  
  <div class="c-landing-footer__bottom">
    <div class="o-wrapper-wide">
      <p class="c-landing-footer__copyright">
        &copy;<?php echo date('Y'); ?> EdgeBeam Wireless, LLC. All Rights Reserved. <a href="#">Privacy Policy</a>.
      </p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
