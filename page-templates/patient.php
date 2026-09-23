<?php
/**
 * Template Name: Patient Page
 * 
 * @package IDDI_Academy
 */

get_header(); ?>

<main id="primary" class="site-main patient-page">

    <!-- Section 1 & 2: Hero & What is an Implant? -->
    <section class="patient-intro-wrapper">
        
        <div class="patient-hero">
            <div class="container">
                <span class="patient-hero__subtitle">Resources</span>
                <h1 class="patient-hero__title">For Patients</h1>
                <p class="patient-hero__description">
                    Cowellmedi delivers differentiated value through a growth factor-based regenerative platform, 
                    a state-of-the-art AI production line, and a quality system compliant with global regulations.
                </p>
            </div>
        </div>

        <div class="implant-intro">
            <div class="container">
                <div class="implant-intro__container">
                    
                    <!-- Left: Text Content -->
                    <div class="implant-intro__content">
                        <h2 class="implant-intro__title">What is an <span>Implant?</span></h2>
                        <div class="implant-intro__text">
                            <p>
                                Implants are a dental technology that employs artificial structures similar to teeth, 
                                replacing or supporting missing teeth and gum tissue.
                            </p>
                            <p>
                                An implant typically consists of three main components: the fixture, abutment, and crown. 
                                The term "implant" usually refers to the fixture. It is an artificial structure that, 
                                once the fixture is placed in the jawbone where teeth are missing or have been extracted, 
                                connects to a prosthetic component to function similarly to a natural tooth.
                            </p>
                            <p>
                                Implants are primarily made by titanium, a material with a specially treated surface. 
                                Titanium is highly biocompatible, allowing it to integrate well with surrounding dental tissues, 
                                thus effectively replicating the function and stability of a tooth root.
                            </p>
                        </div>
                    </div>

                    <!-- Right: Visual Diagram -->
                    <div class="implant-intro__visual">
                        
                    </div>

                </div>
            </div>
        </div>

    </section>
    <!-- Section 3: Korea's First Dental Implant -->
    <section class="korea-implant">
        <div class="container">
            <div class="center-text">
                <span class="korea-implant__subtitle">Cowellmedi</span>
                <h2 class="korea-implant__title">Korea's First Dental Implant</h2>
            </div>
            <div class="korea-implant__media">
                <img src="/wp-content/uploads/2026/05/cowellmedi_koreas_first_dental_implant.png" alt="Korea's First Dental Implant X-Ray" class="korea-implant__image">
            </div>
        </div>
    </section>
    <!-- Section 4: Product Systems -->
    <section class="product-systems">
        <div class="container">
            <div class="center-text">
                <span class="korea-implant__subtitle">Cowellmedi</span>
                <h2 class="korea-implant__title">Korea's First Dental Implant</h2>
            </div>
            
            <div class="product-grid">
                <!-- Card 1 -->
                <div class="product-card">
                    <div class="product-card__figure">
                        <img src="/wp-content/uploads/2026/05/Bioplant-System.png" alt="Bioplant System" class="product-card__image">
                    </div>
                    <span class="product-card__tag">#35</span>
                    <h3 class="product-card__title">BIOPLANT</h3>
                    <p class="product-card__desc">
                        The foundation of our expertise, developed in 1994. Korea's first dental implant, setting the standard for local manufacturing.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="product-card">
                    <div class="product-card__figure">
                        <img src="/wp-content/uploads/2026/05/Atlas-Implant-System.png" alt="Atlas Implant System" class="product-card__image">
                    </div>
                    <span class="product-card__tag">#25, 36 and 37</span>
                    <h3 class="product-card__title">ATLAS IMPLANT SYSTEM</h3>
                    <p class="product-card__desc">
                        The foundation of our expertise, developed in 1994. Korea's first dental implant, setting the standard for local manufacturing.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="product-card">
                    <div class="product-card__figure">
                        <img src="/wp-content/uploads/2026/05/Inno-Implant-System.png" alt="Inno Implant System" class="product-card__image">
                    </div>
                    <span class="product-card__tag">#32, 33 and 47</span>
                    <h3 class="product-card__title">INNO IMPLANT SYSTEM</h3>
                    <p class="product-card__desc">
                        The foundation of our expertise, developed in 1994. Korea's first dental implant, setting the standard for local manufacturing.
                    </p>
                </div>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
