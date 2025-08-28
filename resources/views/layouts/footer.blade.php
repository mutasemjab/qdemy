<footer class="footer">
    <div class="footer-container">

        <!-- Logo + Description -->
        <div class="footer-logo-section">
            <img src="{{ asset('assets_front/images/logo-white.png') }}" alt="Qdemy Logo" class="footer-logo">
            <p class="footer-desc">
                Lorem ipsum dolor sit amet consectetur. Porttitor molestie sapien dictum quam semper a sed auctor turpis.
                Quam iaculis fringilla eros erat. Purus dui aliquet eget blandit enim nunc accumsan quis.
            </p>
        </div>

        <!-- Column 1: Technical Support + Quick Links -->
        <div class="footer-column">
            <div>
                <h4>Technical Support</h4>
                <ul>
                    <li><a href="#">Frequently Asked Questions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">User Guide</a></li>
                    <li><a href="#">Our Service Values</a></li>
                </ul>
            </div>

            <div>
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="{{ route('international-programms') }}">International Program</a></li>
                    <li><a href="{{ route('grades_basic-programm') }}">Basic Grades Program</a></li>
                    <li><a href="{{ route('universities-programm') }}">Universities and Colleges Program</a></li>
                    <li><a href="{{ route('tawjihi-programm') }}">Tawjihi & Secondary Program</a></li>
                    <li><a href="{{ route('packages-offers') }}">Packages && Offers</a></li>
                </ul>
            </div>
        </div>

        <!-- Column 2: Contact Us + Follow Us -->
        <div class="footer-column">
            <div class="footer-column-Contact">
                <h4>Contact Us</h4>
                <ul>
                    <li>Jordan – Amman</li>
                    <li>Technical Support: support@qdemy.com</li>
                    <li>Careers: jobs@qdemy.com</li>
                </ul>
            </div>

            <div>
                <h4>Follow Us on Social Media</h4>
                <ul>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Instagram</a></li>
                </ul>
            </div>
        </div>

    </div>
</footer>
