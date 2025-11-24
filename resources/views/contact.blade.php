@extends('layout.app')

@section('content')
    <section class="contact-main">
        <section class="sec1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="innersec1">
                            <h2 class="black-head">Contact Us</h2>
                        </div>
                    </div>
                    <div class="col-lg-6 "></div>
                </div>
            </div>
        </section>
        <section class="sec2">
            <div class="container">
                <div class="sec7-inner">
                    <h2 class="black-head">Contact Us</h2>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <div class="contact-form">
                            <h3>Let’s Talk Learning</h3>
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
                            <img src="{{ asset('assets/images/contact/form-beside.png') }}" alt="">
                            <div class="cta-content">
                                <h5>GET IN TOUCH</h5>
                                <p>Learn Math with Music</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    @endsection
