@extends('layout.app')

@section('content')
    <section class="lesson-main">
        <section class="sec1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-12">
                        <div class="innersec1">
                            <h2 class="black-head">Quick Lessons</h2>
                        </div>
                    </div>
                    <div class="col-lg-6 "></div>
                </div>
            </div>
        </section>
        <section class="sec2">
            <div class="container">
                <div class="inner-sec2">
                    <h3 class="black-head">Big learning. Small time commitment.</h3>
                    <p>Our quick lessons are short, engaging, and packed with value, perfect for busy parents, curious kids, and on-the-go educators. From bite-sized science experiments to focus techniques and creative learning hacks, each video turns everyday moments into meaningful education. Whether you’ve got five minutes or fifteen, there’s always something new to explore.</p>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-4 col-lg-4">
                        <div class="lesson">
                            <a href="#">
                                <div class="lesson-head">
                                    <img src="{{ asset('assets/images/lesson/lesson1.png') }}" alt="">
                                </div>
                                <div class="lesson-body">
                                    <h5 class="lesson-h6">Mastering Focus with the 5-Minute Rule</h5>
                                    <p>Struggling to get your child to sit still and concentrate? This quick lesson introduces the “5-Minute Rule,” a simple trick that turns any task into a manageable, bite-sized challenge. Great for developing attention spans and building productive study habits, one short session at a time. An ideal start before jumping into sequential order activities for kids.</p>
                                    <a class="lesson-rm" href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="lesson">
                            <a href="#">
                                <div class="lesson-head">
                                    <img src="{{ asset('assets/images/lesson/lesson2.png') }}" alt="">
                                </div>
                                <div class="lesson-body">
                                    <h5 class="lesson-h6">Math Made Magical with Household Items</h5>
                                    <p>Learn how to turn spoons, socks, and cereal boxes into powerful math tools! This hands-on lesson shows how everyday objects can help kids understand addition, subtraction, and even patterns, making math both visual and fun. It’s the perfect companion to math learning trains for kids and other foundational number sense songs for children.</p>
                                    <a class="lesson-rm" href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="lesson">
                            <a href="#">
                                <div class="lesson-head">
                                    <img src="{{ asset('assets/images/lesson/lesson3.png') }}" alt="">
                                </div>
                                <div class="lesson-body">
                                    <h5 class="lesson-h6">The Sound of Science With Balloons!</h5>
                                    <p>Science doesn’t need a lab. Using just a balloon and a few basic items, this lesson explores how sound travels. Kids will learn a core physics concept through play, helping develop critical thinking alongside fun and discovery. A great complement to pattern recognition activities that build cognitive connections.</p>
                                    <a class="lesson-rm" href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="lesson">
                            <a href="#">
                                <div class="lesson-head">
                                    <img src="{{ asset('assets/images/lesson/lesson4.png') }}" alt="">
                                </div>
                                <div class="lesson-body">
                                    <h5 class="lesson-h6">Breathe In, Blow Out: A Calm-Down Technique That Works</h5>
                                    <p>Emotions can run high, especially during learning. This quick emotional regulation lesson teaches children a simple breathing method to regain calm, focus, and control. Perfect before homework, exams, or even engaging with interactive lessons like kindergarten graphing lessons and activities.</p>
                                    <a class="lesson-rm" href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="lesson">
                            <a href="#">
                                <div class="lesson-head">
                                    <img src="{{ asset('assets/images/lesson/lesson5.png') }}" alt="">
                                </div>
                                <div class="lesson-body">
                                    <h5 class="lesson-h6">The Power of Storytelling for Better Vocabulary</h5>
                                    <p>In this short story-based video, children learn new words through context, tone, and visuals. It’s an immersive language-building technique that boosts comprehension and encourages kids to fall in love with reading, one story at a time. An engaging follow-up to any lesson involving sequential order activities for kids or word-based pattern recognition.</p>
                                    <a class="lesson-rm" href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                        <div class="lesson">
                            <a href="#">
                                <div class="lesson-head">
                                    <img src="{{ asset('assets/images/lesson/lesson6.png') }}" alt="">
                                </div>
                                <div class="lesson-body">
                                    <h5 class="lesson-h6">From Seed to Sprout: A Mini Botany Lesson</h5>
                                    <p>This short experiment takes kids through the magic of germination. Using a paper towel, a jar, and a few seeds, they’ll witness nature in action and learn the basic science behind how plants grow, right from their kitchen window. It’s also a great way to teach observation, sequencing, and basic science patterns.</p>
                                    <a class="lesson-rm" href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
  @endsection
