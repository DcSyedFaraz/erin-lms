@extends('layout.app')

@section('content')
    <section class="about-main">
        <section class="sec1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="innersec1">
                            <h2 class="black-head">About Us</h2>
                        </div>
                    </div>
                    <div class="col-lg-6 "></div>
                </div>
            </div>
        </section>
        <section class="sec2">
            <div class="container">
                <div class="about-div" style="padding-bottom:200px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="sec5-headings">
                                <h4 class="purple-head">About Ally Dog Depot</h4>
                                <h2 class="black-head">Where Learning Sounds Like Fun and Feels Like Growth</h2>
                                <p>We’re a team of educators, creators, and parents with one mission: to make early learning joyful, effective, and unforgettable. Built by teachers and loved by families, our platform combines proven teaching strategies with playful learning to create an award-winning math program for kids.<br>
                                At the heart of our approach is a unique math curriculum with music, movement, and storytelling, turning abstract concepts into engaging, real-world understanding. From interactive STEM lessons for preschool to number sense songs, every resource is designed to spark curiosity and build confidence in young learners.<br>
                                Unlike traditional screen-heavy apps, we believe learning should be multi-sensory and rooted in real connection. Whether it’s a preschool math video program that helps kids understand patterns or a quick-buy kids learning activity you can try at home, we make sure every experience is both educational and enjoyable.<br>
                                While others focus on gamification or drill-based learning, we blend creativity with core skills. Think of us as your go-to for engaging math for kindergarten, backed by expert educators and inspired by real classrooms. And yes, we’re proud to be among the few programs educators compare to an Emmy-winning kids math program in terms of impact and innovation.<br>
                                If you’re a parent, teacher, or just someone who believes learning should be fun, you’re in the right place.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <img src="{{ asset('assets/images/lesson/lesson4.png') }}" style="width:100%" alt="">
                        </div>
                    </div>
                </div>
                <div class="sec3-inner">
                    <div class="sec3-head">
                        <div class="sec3-headings">
                            <h4 class="purple-head">What Video Learning Looks Like</h4>
                            <h2 class="black-head">Discover The Difference</h2>
                        </div>

                        <div class="sec3-btns">
                            <a href="#" class="vubtn">Video Upload</a>
                        </div>

                    </div>
                    <div class="sec3-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="vid1">
                                    <video src="{{ asset('assets/images/dummy_video_test.mp4') }}" controls></video>
                                    <video src="{{ asset('assets/images/dummy_video_test.mp4') }}" controls></video>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="vid2">
                                    <video src="{{ asset('assets/images/dummy_video_test.mp4') }}" controls></video>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sec3-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="vid1">
                                    <video src="{{ asset('assets/images/dummy_video_test.mp4') }}" controls></video>
                                    <video src="{{ asset('assets/images/dummy_video_test.mp4') }}" controls></video>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="vid2">
                                    <video src="{{ asset('assets/images/dummy_video_test.mp4') }}" controls></video>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sec3-body">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="vid1">
                                    <video src="{{ asset('assets/images/dummy_video_test.mp4') }}" controls></video>
                                    <video src="{{ asset('assets/images/dummy_video_test.mp4') }}" controls></video>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="vid2">
                                    <video src="{{ asset('assets/images/dummy_video_test.mp4') }}" controls></video>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection
