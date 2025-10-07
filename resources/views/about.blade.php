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
                <div class="sec3-inner">
                    <div class="sec3-head">
                        <div class="sec3-headings">
                            <h4 class="purple-head">INTRODUCTION VIDEO</h4>
                            <h2 class="black-head">Discover the Academy Difference</h2>
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
