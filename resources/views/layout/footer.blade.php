 <footer class="footer">
        <div class="Main-foot">
            <div class="container">
                <div class="foot-d">
                    <div class="firstcol">
                        <a href="/">
                            <img class="foot-logo" src="{{ asset('assets/images/logo2.png') }}" alt="">
                        </a>
                        <h4>Follow Our Social Media</h4>
                        <div class="socials">
                            <a href="#">
                                <img src="{{ asset('assets/images/Facebook.png') }}" alt="">
                            </a>
                            <a href="#">
                                <img src="{{ asset('assets/images/Youtube.png') }}" alt="">
                            </a>
                            <a href="#">
                                <img src="{{ asset('assets/images/Instagram.png') }}" alt="">
                            </a>
                            <a href="#">
                                <img src="{{ asset('assets/images/Pinterest.png') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="secondcol">
                        <h4>Location</h4>
                        <ul class="footul">
                            <li><a href="#">789 LOREM IPSUM CURIOUS CITY, AAA 0000 UK</a></li>
                        </ul>
                    </div>
                    <div class="thirdcol">
                        <h4>About Us</h4>
                        <ul class="footul">
                            <li><a href="{{ route('program') }}">Program</a></li>
                            <li><a href="{{ route('blog') }}">News & Blog</a></li>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="fourthcol">
                        <h4>Quick Links</h4>
                        <ul class="footul">
                            <li><a href="{{ route('membership') }}">Membership Options</a></li>
                            <li><a href="{{ route('lesson') }}">Quick Lessons</a></li>
                            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div class="fifthcol">
                        <h4>Subscribe to Our Newsletter!</h4>
                        <form>
                            <input type="email" class="emfield" placeholder="ENTER YOUR EMAIL ADDRESS" required
                                name="email">
                            <input type="submit" value="Subscribe" class="subsbtn">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="reserve">
                <p>Copyright © 2025 All Right Reserved</p>
            </div>
        </div>
    </footer>
