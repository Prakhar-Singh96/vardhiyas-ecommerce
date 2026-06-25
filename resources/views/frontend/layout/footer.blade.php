{{-- Trust Bar --}}
<div class="trust">
  <div class="container">
    <div class="trust-wrap">
      @foreach([
        ['bi-truck',        'Free Shipping',  'Orders above ₹999'],
        ['bi-shield-check', '100% Original',  'Genuine products only'],
        ['bi-arrow-repeat', 'Easy Returns',   '7 day return policy'],
        ['bi-headset',      '24/7 Support',   'Always here for you'],
        ['bi-credit-card',  'Secure Payment', 'Safe & encrypted'],
        ['bi-cash-coin',    'COD Available',  'Pay on delivery'],
      ] as [$ico, $t, $s])
      <div class="trust-item">
        <div class="trust-ico"><i class="bi {{ $ico }}"></i></div>
        <div class="trust-text"><h6>{{ $t }}</h6><p>{{ $s }}</p></div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- Main Footer --}}
<footer class="site-footer">
  <div class="container">

    {{-- ✅ Custom CSS Grid — Bootstrap row/col ki jagah --}}
    <div class="ft-grid">

      {{-- Brand --}}
      <div class="ft-brand">
        <a href="{{ route('home') }}" class="footer-logo">
          VAR<em>DI</em>YASH
        </a>
        <p class="footer-desc">
          India's premium destination for sportswear, martial arts &amp; combat sports.
          Gear up. Train hard. Dominate.
        </p>
        <div class="social-row">
          <a href="#"><i class="bi bi-instagram"></i></a>
          <a href="#"><i class="bi bi-facebook"></i></a>
          <a href="#"><i class="bi bi-youtube"></i></a>
          <a href="#"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>

      {{-- Company --}}
      <div>
        <h6 class="ft-head">Company</h6>
        <div class="ft-links">
          <a href="#">About Us</a>
          <a href="#">Careers</a>
          <a href="#">Blog</a>
          <a href="#">Press</a>
          <a href="#">Contact Us</a>
        </div>
      </div>

      {{-- Need Help --}}
      <div>
        <h6 class="ft-head">Need Help</h6>
        <div class="ft-links">
          <a href="#">Track Order</a>
          <a href="#">Returns &amp; Exchange</a>
          <a href="#">Size Guide</a>
          <a href="#">FAQs</a>
          <a href="#">Privacy Policy</a>
        </div>
      </div>

      {{-- Contact + Newsletter --}}
      <div>
        <h6 class="ft-head">Get in Touch</h6>
        <ul class="ft-contact">
          <li>
            <i class="bi bi-geo-alt-fill"></i>
            <span>Delhi, India — 110001</span>
          </li>
          <li>
            <i class="bi bi-telephone-fill"></i>
            <a href="tel:+919876543210">+91 98765 43210</a>
          </li>
          <li>
            <i class="bi bi-envelope-fill"></i>
            <a href="mailto:support@vardiyash.com">support@vardiyash.com</a>
          </li>
        </ul>

        <h6 class="ft-head" style="margin-top:22px">Newsletter</h6>
        <div class="ft-nl">
          <input type="email" placeholder="Enter your email address">
          <button>Subscribe</button>
        </div>
      </div>

    </div>
  </div>

  <div class="ft-bottom">
    <div class="container ft-bottom-inner">
      <span>© {{ date('Y') }} Vardiyash. All rights reserved.</span>
      <div class="ft-bottom-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Shipping Policy</a>
      </div>
    </div>
  </div>
</footer>