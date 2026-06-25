document.addEventListener('DOMContentLoaded', () => {

    /* Back to top */
    const btt = document.getElementById('btt');
    if (btt) {
        window.addEventListener('scroll', () => btt.classList.toggle('show', scrollY > 400));
        btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    }

    /* Hero slider */
    initSlider();

    /* Mobile menu */
    const mobMenu = document.getElementById('mobMenu');
    document.getElementById('mobToggle')
        ?.addEventListener('click', () => {
            mobMenu?.classList.add('open');
            document.body.style.overflow = 'hidden';
        });

    function closeMob() {
        mobMenu?.classList.remove('open');
        document.body.style.overflow = '';
    }
    document.getElementById('mobClose')?.addEventListener('click', closeMob);
    document.getElementById('mobBg')?.addEventListener('click', closeMob);

    /* Size selector */
    document.querySelectorAll('.p-sz').forEach(b => {
        b.addEventListener('click', function (e) {
            e.preventDefault();
            if (this.classList.contains('na')) return;
            this.closest('.p-sz-row').querySelectorAll('.p-sz').forEach(x => x.classList.remove('on'));
            this.classList.add('on');

            // ✅ NAYA — price update karo
            updateCardPrice(this.closest('.p-card'));
        });
    });

    /* Color selector */
    document.querySelectorAll('.p-cl').forEach(d => {
        d.addEventListener('click', function (e) {
            e.preventDefault();
            this.closest('.p-cl-row').querySelectorAll('.p-cl').forEach(x => x.classList.remove('on'));
            this.classList.add('on');

            // ✅ NAYA — price update karo
            updateCardPrice(this.closest('.p-card'));
        });
    });


    /* Cart badge */
    refreshCart();
});

/* ✅ NAYA FUNCTION — yahi card ki price update karta hai */
function updateCardPrice(card) {
  if (!card) return;

  const mrp     = parseFloat(card.dataset.mrp || 0);
  const saleRaw = card.dataset.sale;
  const baseSale = saleRaw !== '' ? parseFloat(saleRaw) : mrp;

  let variants = [];
  try { variants = JSON.parse(card.dataset.variants || '[]'); } catch(e) {}

  const selSizeBtn  = card.querySelector('.p-sz.on');
  const selColorEl  = card.querySelector('.p-cl.on');
  const selSizeId   = selSizeBtn ? parseInt(selSizeBtn.dataset.size) : null;
  const selColorId  = selColorEl ? parseInt(selColorEl.dataset.color) : null;

  let extra = 0;
  if (selSizeId !== null) {
    const match = variants.find(v =>
      v.size_id === selSizeId &&
      (selColorId === null || v.color_id === selColorId)
    ) || variants.find(v => v.size_id === selSizeId);

    if (match) extra = match.extra_price || 0;
  }

  const newSale = baseSale + extra;

  const nowEl = card.querySelector('.p-now');
  const wasEl = card.querySelector('.p-was');
  const pctEl = card.querySelector('.p-pct');

  if (nowEl) {
    nowEl.textContent = '₹' + Math.round(newSale).toLocaleString('en-IN');
  }

  if (saleRaw !== '' && mrp > newSale) {
    const pct = Math.round((mrp - newSale) / mrp * 100);
    if (wasEl) wasEl.textContent = '₹' + Math.round(mrp).toLocaleString('en-IN');
    if (pctEl) pctEl.textContent = pct + '% off';
  }
}

/* Hero Slider */
function initSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    if (!slides.length) return;

    let cur = 0, timer;

    const go = (n) => {
        slides[cur].classList.remove('active');
        dots[cur]?.classList.remove('on');
        cur = (n + slides.length) % slides.length;
        slides[cur].classList.add('active');
        dots[cur]?.classList.add('on');
    };

    slides[0].classList.add('active');
    dots[0]?.classList.add('on');

    if (slides.length < 2) return;

    const reset = () => { clearInterval(timer); timer = setInterval(() => go(cur + 1), 5000); };

    reset();
    document.querySelector('.hero-prev')?.addEventListener('click', () => { go(cur - 1); reset(); });
    document.querySelector('.hero-next')?.addEventListener('click', () => { go(cur + 1); reset(); });
    dots.forEach((d, i) => d.addEventListener('click', () => { go(i); reset(); }));
}

/* Mobile sub-menu */
function mobSub(btn) {
    const sub = btn.nextElementSibling;
    if (!sub) return;
    const show = sub.classList.toggle('show');
    btn.classList.toggle('open', show);
}

/* Cart */
function refreshCart() {
    try {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const n = cart.reduce((s, i) => s + (i.qty || 1), 0);
        document.querySelectorAll('.h-badge').forEach(el => {
            el.textContent = n;
            el.style.display = n ? 'flex' : 'none';
        });
    } catch (e) { }
}

/* Toast */
function toast(msg) {
    let w = document.getElementById('_tw');
    if (!w) {
        w = document.createElement('div');
        w.id = '_tw';
        w.style.cssText = 'position:fixed;bottom:22px;left:50%;transform:translateX(-50%);z-index:9999;pointer-events:none';
        document.body.appendChild(w);
    }
    const t = document.createElement('div');
    t.style.cssText = 'background:#111;color:#fff;padding:10px 20px;margin-top:6px;font-size:13px;font-weight:500;border-left:3px solid var(--primary);box-shadow:0 4px 16px rgba(0,0,0,.25);white-space:nowrap;font-family:Inter,sans-serif';
    t.textContent = msg;
    w.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .25s'; setTimeout(() => t.remove(), 250); }, 2500);
}

document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper('.myHeroSwiper', {
        loop: true,
        speed: 800,
        autoplay: {
            delay: 1000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.hero-next',
            prevEl: '.hero-prev',
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
    });
});

// Product Slider Configuration
document.addEventListener('DOMContentLoaded', function () {
    const productSwiper = new Swiper('.productSwiper', {
        // Global Autoplay configuration (Important)
        autoplay: {
            delay: 1000,
            disableOnInteraction: false,
        },
        loop: true,
        speed: 1000,

        // Default (Mobile) - Yahan slider ko disable rakhenge
        enabled: false,
        slidesPerView: 2,
        spaceBetween: 12,

        breakpoints: {
            // Desktop (768px and above)
            768: {
                enabled: true, // Slider activate ho jayega
                slidesPerView: 4,
                spaceBetween: 30,
            }
        },

        // Initial check to start/stop autoplay
        on: {
            init: function () {
                if (window.innerWidth < 768) {
                    this.autoplay.stop();
                } else {
                    this.autoplay.start();
                }
            },
            resize: function () {
                if (window.innerWidth < 768) {
                    this.autoplay.stop();
                } else {
                    this.autoplay.start();
                }
            }
        }
    });
});

const testimonialSwiper = new Swiper('.testimonialSwiper', {
    slidesPerView: 1.5, // Mobile par side wala thoda dikhega
    centeredSlides: true,
    spaceBetween: 20,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    breakpoints: {
        // Tablet
        640: {
            slidesPerView: 3,
            centeredSlides: false,
        },
        // Desktop
        1024: {
            slidesPerView: 5, // Exact 5 reviews jaisa image mein hai
            centeredSlides: false,
            spaceBetween: 40,
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const feedSwiper = new Swiper('.feedSwiper', {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        loop: true,
        slidesPerView: 'auto', // CSS width use karega
        coverflowEffect: {
            rotate: 0,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: false,
        },
        breakpoints: {
            320: { slidesPerView: 1.5, spaceBetween: 10 },
            768: { slidesPerView: 3, spaceBetween: 20 },
            1024: { slidesPerView: 5, spaceBetween: 30 } // Ek sath 5 show karega
        },
        on: {
            init: function () {
                playActiveVideo(this);
            },
            slideChangeTransitionEnd: function () {
                playActiveVideo(this);
            }
        }
    });

    function playActiveVideo(swiper) {
        // Saari videos pause and reset
        const allVideos = document.querySelectorAll('.feed-video');
        allVideos.forEach(v => {
            v.pause();
            v.currentTime = 0;
        });

        // Current active (raised) slide ki video
        const activeSlide = swiper.slides[swiper.activeIndex];
        const activeVideo = activeSlide.querySelector('video');

        if (activeVideo) {
            activeVideo.play();

            // Jab video khatam ho, automatic next slide par jao
            activeVideo.onended = function () {
                swiper.slideNext();
            };
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const track = document.getElementById('looksTrack');
    const prevBtn = document.getElementById('looksPrev');
    const nextBtn = document.getElementById('looksNext');
    const dots = document.querySelectorAll('.looks-dot');
    const cards = track ? track.querySelectorAll('.look-card') : [];
    const total = cards.length;
    const perPage = window.innerWidth < 600 ? 1 : window.innerWidth < 992 ? 2 : 4;
    let current = 0;
    const maxPage = Math.max(0, Math.ceil(total / perPage) - 1);

    function update() {
        if (!track) return;
        const cardW = cards[0] ? cards[0].offsetWidth + 14 : 0;
        track.style.transform = `translateX(-${current * perPage * cardW}px)`;
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
        if (prevBtn) prevBtn.classList.toggle('disabled', current === 0);
        if (nextBtn) nextBtn.classList.toggle('disabled', current >= maxPage);
    }

    window.looksNav = function (dir) {
        current = Math.max(0, Math.min(maxPage, current + dir));
        update();
    };
    window.looksGoTo = function (idx) {
        current = idx;
        update();
    };

    update();

    /* ── Vardiyash Favourite Slider ── */
    const vfTrack = document.getElementById('vfTrack');
    const vfPrevBtn = document.getElementById('vfPrev');
    const vfNextBtn = document.getElementById('vfNext');
    const vfDots = document.querySelectorAll('.vf-dot');
    const vfCards = vfTrack ? vfTrack.querySelectorAll('.vf-card') : [];
    const vfTotal = vfCards.length;
    const vfPerPage = window.innerWidth < 600 ? 1 : window.innerWidth < 992 ? 2 : 4;
    let vfCurrent = 0;
    const vfMaxPage = Math.max(0, Math.ceil(vfTotal / vfPerPage) - 1);

    function vfUpdate() {
        if (!vfTrack) return;
        const cardW = vfCards[0] ? vfCards[0].offsetWidth + 14 : 0;
        vfTrack.style.transform = `translateX(-${vfCurrent * vfPerPage * cardW}px)`;
        vfDots.forEach((d, i) => d.classList.toggle('active', i === vfCurrent));
        if (vfPrevBtn) vfPrevBtn.classList.toggle('disabled', vfCurrent === 0);
        if (vfNextBtn) vfNextBtn.classList.toggle('disabled', vfCurrent >= vfMaxPage);
    }

    window.vfNav = function (dir) {
        vfCurrent = Math.max(0, Math.min(vfMaxPage, vfCurrent + dir));
        vfUpdate();
    };
    window.vfGoTo = function (idx) {
        vfCurrent = idx;
        vfUpdate();
    };

    vfUpdate();
});


/* ══════════════════════════════════════
   CART — localStorage helpers
══════════════════════════════════════ */
function getCart() {
  try { return JSON.parse(localStorage.getItem('cart') || '[]'); }
  catch(e) { return []; }
}
function setCart(cart) {
  localStorage.setItem('cart', JSON.stringify(cart));
  refreshCart();
}

/* Badge update */
function refreshCart() {
  const cart   = getCart();
  const count  = cart.reduce((s, i) => s + i.qty, 0);
  const badges = document.querySelectorAll('#cartBadge, .h-badge');
  badges.forEach(b => {
    b.textContent = count;
    b.style.display = count > 0 ? 'flex' : 'none';
  });
}

/* ══════════════════════════════════════
   CART DRAWER
══════════════════════════════════════ */
function openCartDrawer() {
  renderCartDrawer();
  document.getElementById('cartDrawer').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeCartDrawer() {
  document.getElementById('cartDrawer').classList.remove('open');
  document.body.style.overflow = '';
}

function renderCartDrawer() {
  const cart   = getCart();
  const body   = document.getElementById('cdBody');
  const foot   = document.getElementById('cdFoot');
  const title  = document.getElementById('cdTitle');

  title.textContent = 'Cart (' + cart.reduce((s,i)=>s+i.qty,0) + ')';

  if (!cart.length) {
    body.innerHTML = '<div class="cd-empty"><i class="bi bi-bag-x"></i><p>Cart khali hai</p></div>';
    foot.style.display = 'none';
    return;
  }

  let subtotal = 0;
  body.innerHTML = cart.map((item, idx) => {
    const line = item.price * item.qty;
    subtotal += line;
    const img = item.image ? `<img src="${item.image}" alt="${item.name}">` :
      '<div style="width:66px;height:84px;background:#f0f0f0;border-radius:6px;flex-shrink:0"></div>';
    const meta = [item.size_label, item.color_label].filter(Boolean).join(' / ');
    return `
      <div class="cd-item">
        ${img}
        <div class="cd-item-info">
          <div class="cd-item-name">${item.name}</div>
          <div class="cd-item-meta">${meta}</div>
          <div class="cd-qty">
            <button onclick="cdQty(${idx},-1)">−</button>
            <span>${item.qty}</span>
            <button onclick="cdQty(${idx},1)">+</button>
          </div>
        </div>
        <div class="cd-item-right">
          <button class="cd-remove" onclick="cdRemove(${idx})">
            <i class="bi bi-trash"></i>
          </button>
          <span class="cd-item-price">₹${line.toLocaleString('en-IN')}</span>
        </div>
      </div>`;
  }).join('');

  document.getElementById('cdSubtotal').textContent = '₹' + subtotal.toLocaleString('en-IN');
  foot.style.display = 'block';
}

function cdQty(idx, dir) {
  const cart = getCart();
  cart[idx].qty = Math.max(1, cart[idx].qty + dir);
  setCart(cart);
  renderCartDrawer();
}
function cdRemove(idx) {
  const cart = getCart();
  cart.splice(idx, 1);
  setCart(cart);
  renderCartDrawer();
}
function goCheckoutFromCart() {
  const cart = getCart();
  if (!cart.length) return;
  closeCartDrawer();
  openCheckoutModal(cart);
}

/* ══════════════════════════════════════
   CHECKOUT MODAL
══════════════════════════════════════ */
var chkItems = [];

function openCheckoutModal(items) {
  chkItems = items;
  renderChkSummary();
  resetChkForm();
  document.getElementById('checkoutModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeCheckoutModal() {
  document.getElementById('checkoutModal').classList.remove('open');
  document.body.style.overflow = '';
}

function renderChkSummary() {
  let sub = 0;
  const rows = chkItems.map(i => {
    sub += i.price * i.qty;
    const meta = [i.size_label, i.color_label].filter(Boolean).join('/');
    return `<div class="chk-m-item">
      <span>${i.name}${meta?' ('+meta+')':''} × ${i.qty}</span>
      <span>₹${(i.price*i.qty).toLocaleString('en-IN')}</span>
    </div>`;
  }).join('');

  const shipping = sub >= CHK_FREE_ABOVE ? 0 : CHK_SHIPPING;
  const total    = sub + shipping;

  document.getElementById('chkSummary').innerHTML = rows +
    `<div class="chk-m-item"><span>Shipping</span><span>${shipping===0?'FREE':'₹'+shipping}</span></div>` +
    `<div class="chk-m-total"><span>Total</span><span>₹${total.toLocaleString('en-IN')}</span></div>`;
}

function resetChkForm() {
  const form = document.getElementById('checkoutForm');
  if (form) {
    form.reset();
    document.getElementById('chkCity').value  = '';
    document.getElementById('chkState').value = '';
    document.getElementById('pinStatus').textContent = '';
    document.getElementById('pinStatus').className = 'pin-status';
  }
  const body = document.getElementById('chkModalBody');
  // Agar success state tha toh form wapas dikhao
  if (body && !body.querySelector('#checkoutForm')) {
    location.reload(); // fallback
  }
}

/* Pincode auto city/state */
document.addEventListener('DOMContentLoaded', function() {
  const pinEl = document.getElementById('chkPincode');
  if (!pinEl) return;

  pinEl.addEventListener('input', function() {
    const pin = this.value.trim();
    const statusEl = document.getElementById('pinStatus');
    if (pin.length !== 6) { statusEl.textContent = ''; return; }

    statusEl.textContent = 'Checking...';
    statusEl.className = 'pin-status';

    fetch(`https://api.postalpincode.in/pincode/${pin}`)
      .then(r => r.json())
      .then(data => {
        if (data[0]?.Status === 'Success' && data[0].PostOffice?.length) {
          const po = data[0].PostOffice[0];
          document.getElementById('chkCity').value  = po.District;
          document.getElementById('chkState').value = po.State;
          statusEl.textContent = '✓ ' + po.District + ', ' + po.State;
          statusEl.className = 'pin-status ok';
        } else {
          document.getElementById('chkCity').value  = '';
          document.getElementById('chkState').value = '';
          statusEl.textContent = '✕ Invalid pincode';
          statusEl.className = 'pin-status err';
        }
      })
      .catch(() => {
        statusEl.textContent = '✕ Check nahi ho saka';
        statusEl.className = 'pin-status err';
      });
  });

  /* Pay option toggle */
  document.querySelectorAll('.pay-option').forEach(opt => {
    opt.addEventListener('click', function() {
      document.querySelectorAll('.pay-option').forEach(o => o.classList.remove('active'));
      this.classList.add('active');
    });
  });
});

/* Submit */
function submitCheckout(e) {
  e.preventDefault();
  if (!chkItems.length) { alert('Cart khali hai'); return; }

  const btn = document.getElementById('placeOrderBtn');
  btn.disabled = true;
  btn.textContent = 'Processing...';

  const payload = {
    customer_name:  document.getElementById('chkName').value,
    customer_email: document.getElementById('chkEmail').value,
    customer_phone: document.getElementById('chkPhone').value,
    address_line:   document.getElementById('chkAddress').value,
    locality:       document.getElementById('chkLocality').value,
    city:           document.getElementById('chkCity').value,
    state:          document.getElementById('chkState').value,
    pincode:        document.getElementById('chkPincode').value,
    payment_method: document.querySelector('input[name="payment_method"]:checked')?.value || 'cod',
    items: chkItems.map(i => ({
      product_id: i.id,
      size_id:    i.size_id   || null,
      color_id:   i.color_id  || null,
      qty:        i.qty,
    })),
  };

  fetch(CHK_CREATE_URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify(payload),
  })
  .then(r => r.json())
  .then(data => {
    if (!data.success) {
      alert(data.message || 'Kuch galat hua');
      btn.disabled = false;
      btn.textContent = 'Place Order';
      return;
    }

    if (data.cod) {
      clearCartAfterOrder();
      showChkSuccess(data.order_number);
      return;
    }

    /* Razorpay */
    const rzp = new Razorpay({
      key: data.razorpay_key,
      amount: data.amount,
      currency: 'INR',
      name: 'Vardiyash',
      description: 'Order #' + data.order_number,
      order_id: data.razorpay_order_id,
      prefill: { name: data.name, email: data.email, contact: data.phone },
      theme: { color: '#111111' },
      handler: function(response) {
        fetch(CHK_VERIFY_URL, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({
            order_id: data.order_id,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_order_id: response.razorpay_order_id,
            razorpay_signature: response.razorpay_signature,
          }),
        })
        .then(r => r.json())
        .then(res => {
          if (res.success) {
            clearCartAfterOrder();
            showChkSuccess(data.order_number);
          } else {
            alert('Payment verify nahi ho saka. Support se contact karo.');
            btn.disabled = false;
            btn.textContent = 'Place Order';
          }
        });
      },
      modal: {
        ondismiss: function() {
          btn.disabled = false;
          btn.textContent = 'Place Order';
        }
      }
    });
    rzp.open();
  })
  .catch(() => {
    alert('Network error — dobara try karo');
    btn.disabled = false;
    btn.textContent = 'Place Order';
  });
}

function clearCartAfterOrder() {
  localStorage.removeItem('cart');
  refreshCart();
}

function showChkSuccess(orderNumber) {
  document.getElementById('chkModalBody').innerHTML = `
    <div class="chk-success">
      <i class="bi bi-check-circle-fill"></i>
      <h3>Order Confirmed! 🎉</h3>
      <p>Order #${orderNumber} place ho gaya hai.<br>
         Jald hi process kiya jaayega.</p>
      <button class="chk-success-btn" onclick="closeCheckoutModal()">
        Continue Shopping
      </button>
    </div>`;
}

/* On page load — badge update */
document.addEventListener('DOMContentLoaded', refreshCart);