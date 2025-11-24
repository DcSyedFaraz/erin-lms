@extends('layout.app')

@section('content')
    <section class="program-main">
        <section class="sec1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="innersec1">
                            <h2 class="black-head">Program</h2>
                        </div>
                    </div>
                    <div class="col-lg-6 "></div>
                </div>
            </div>
        </section>
        <section class="sec2">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="service">
                            <img src="{{ asset('assets/images/p1.png') }}" alt="">
                            <h4>Music-Based Learning</h4>
                            <p>A fun math and music learning program designed for pre-k and kindergarteners. Kids sing, move, and play while learning math! Our lessons build number sense, pattern recognition, and problem-solving skills.</p>
                            <a href="#" class="rmbtn">Read More</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service">
                            <img src="{{ asset('assets/images/p2.png') }}" alt="">
                            <h4>Flexible Plans</h4>
                            <p>Subscribe for full access OR try one activity for just $2.99. Each lesson includes an interactive learning video, original music, and downloadable PDFs.</p>
                            <a href="#" class="rmbtn">Read More</a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="service">
                            <img src="{{ asset('assets/images/p3.png') }}" alt="">
                            <h4>Built for Success</h4>
                            <p>Music-based math learning, created by teachers. Loved by kids. This 2x EMMY- winning, research based program is designed to help children ages 3-6 thrive and love learning!</p>
                            <a href="#" class="rmbtn">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="sec4">
            <div class="container">
                <div class="sec4-head">
                    <h4 class="purple-head">Let's Do It The Smart Way</h4>
                    <h2 class="black-head">Early Childhood Learning Plans (Ages 3–6)</h2>
                    <p>Designed for kids 3–6, our math and music learning programs include interactive math videos, music-based lessons, and downloadable activities. We design our learning plans to accommodate all types of learners and households.</p>
                </div>
                <div class="packages">
                    <div class="package">
                        <a href="#">
                            <div class="pack-head">
                                <img src="{{ asset('assets/images/program/package1.png') }}" alt="">
                            </div>
                            <div class="pack-body">
                                <h5>1. Monthly Subscription </h5>
                                <p>Enjoy unlimited access to our full library of kindergarten math with music, songs, and printable activities. Perfect for families who want flexible learning without a long-term commitment.</p>
                                <ul class="pack-ul">
                                    <li><i class="fa-solid fa-circle"></i> Per month: $7</li>
                                    <li><i class="fa-solid fa-user-group"></i> Cancel anytime</li>
                                </ul>
                            </div>
                        </a>
                    </div>
                    <div class="package">
                        <a href="#">
                            <div class="pack-head">
                                <img src="{{ asset('assets/images/program/package2.png') }}" alt="">
                            </div>
                            <div class="pack-body">
                                <h5>2. Yearly Subscription </h5>
                                <p>Get the best value with a full year of unlimited access. This plan includes everything in the monthly subscription. Ideal for parents or educators committed to consistent, long-term early learning.</p>
                                <ul class="pack-ul">
                                    <li><i class="fa-solid fa-circle"></i> Per year: $74.99</li>
                                    <li><i class="fa-solid fa-user-group"></i>Savings over 10%</li>
                                </ul>
                            </div>
                        </a>
                    </div>
                    <div class="package">
                        <a href="#">
                            <div class="pack-head">
                                <img src="{{ asset('assets/images/program/package3.png') }}" alt="">
                            </div>
                            <div class="pack-body">
                                <h5>3. Single Activity Pack </h5>
                                <p>Just getting started? Try one of our activity packs without subscribing. Each pack includes a learning video, downloadable math activities for kids, and MP3 math songs for children, perfect for quick learning at home or on the go.</p>
                                <ul class="pack-ul">
                                    <li><i class="fa-solid fa-circle"></i> Per Activity: $2.99</li>
                                    <li><i class="fa-solid fa-user-group"></i> One-Time Access</li>
                                </ul>
                            </div>
                        </a>
                    </div>
                    <div class="package">
                        <a href="#">
                            <div class="pack-head">
                                <img src="{{ asset('assets/images/program/package4.png') }}" alt="">
                            </div>
                            <div class="pack-body">
                                <h5>4. Family Bundle </h5>
                                <p>Our upcoming Family Bundle will offer a discounted pack of multiple activities, great for families with more than one child or those who want to keep a variety of lessons on hand. More learning, more value, one easy download.</p>
                                <ul class="pack-ul">
                                    <li><i class="fa-solid fa-user-group"></i> All-in-one Family Plan</li>
                                </ul>
                            </div>
                        </a>
                    </div>
                    <div class="package">
                        <a href="#">
                            <div class="pack-head">
                                <img src="{{ asset('assets/images/program/package5.png') }}" alt="">
                            </div>
                            <div class="pack-body">
                                <h5>5. Classroom Plan </h5>
                                <p>Designed for preschools, daycares, or homeschool groups, this plan offers group access, educator-friendly tools, and multi-user licensing. It’s the perfect way to bring music based math learning into a classroom setting.</p>
                                <ul class="pack-ul">
                                    <li><i class="fa-solid fa-user-group"></i> For Educators & Groups</li>
                                </ul>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    <section class="sec6">
        <div class="container">
            <div class="sec6-inner">
                <h2 class="black-head">What’s Said About Us</h2>
            </div>
            <div class="owl-carousel owl-theme testi-caro">
                <div class="item">
                    <div class="testimonial">
                        <!-- <img src="{{ asset('assets/images/prof.png') }}" alt=""> -->
                        <p>My son used to avoid anything ‘educational’ until Ally Dog Depot. Now he begs to watch the math train videos! The mix of music, movement, and learning is genius. He’s counting everything in sight, even his chicken nuggets.</p>
                        <h5 class="name">Ayesha R. <span style="font-size:12px">Mom from Houston</span></h5>
                    </div>
                </div>
                <div class="item">
                    <div class="testimonial">
                        <p>As an educator, I love how this math and music learning program blends foundational math skills with songs that actually stick. It aligns with early learning standards but feels like play for the kids. I’ve started using the activities in my classroom a total game changer!</p>
                        <h5 class="name">Mr. Daniel <span style="font-size:12px">Pre-K Teacher, Chicago</span></h5>
                    </div>
                </div>
                <div class="item">
                    <div class="testimonial">
                        <p>Babysitting my little brother just got way easier. I put on Ally Dog Depot while I prep his lunch, and he sings along while learning about numbers and patterns. He thinks it’s just cartoons; I know it’s low-key making him smarter.</p>
                        <h5 class="name">Sana F. <span style="font-size:12px">Age 19, Karachi</span></h5>
                    </div>
                </div>
                <div class="item">
                    <div class="testimonial">
                        <p>What I appreciate most is that this isn’t just flashy animations; it’s real, structured learning. The kids I tutor respond so well to the rhythm-based approach, and I can track their understanding as we go. Highly recommend to fellow tutors.</p>
                        <h5 class="name">Ali M. <span style="font-size:12px">Math Tutor, London</span></h5>
                    </div>
                </div>
                <div class="item">
                    <div class="testimonial">
                        <p>I wanted to gift my granddaughter something meaningful, and this was perfect. She learns new math skills without even realizing it, and we get to sing the preschool learning math songs together. It’s become our little bonding ritual.</p>
                        <h5 class="name">Nusrat B. <span style="font-size:12px">Grandma of 4-year-old Maya</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <section class="sec7">
            <div class="container">
                <div class="sec7-inner">
                    <h2 class="black-head">Let’s Talk Learning</h2>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="contact-form">
                            <h3>Contact Us</h3>
                            <form class="c-form">
                                <input type="text" required name="f-name" class="form-field" placeholder="Name">
                                <input type="tel" required name="number" class="form-field" placeholder="Phone Number">
                                <input type="text" required name="date" class="form-field" placeholder="Enter Date">
                                <textarea name="msg" class="msg-field" placeholder="Message"></textarea>
                                <input type="submit" class="sub-btn" value="Send Message">
                            </form>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="cta">
                            <img src="{{ asset('assets/images/program/form-beside.png') }}" alt="">
                            <div class="cta-content">
                                <h5>GET IN TOUCH</h5>
                                <p>Let’s Talk Learning</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
