@extends('layouts.landingpageconcert.landingconcert')
@section('contentlandingconcert')
  <div class="slider-item overlay" data-stellar-background-ratio="0.5"
    style="background-image: url('{{ asset('cardboard-assets/img/hero_2.jpg') }}');">
    <div class="container">
      <div class="row slider-text align-items-center justify-content-center">
        <div class="col-lg-12 text-center col-sm-12">
          <h1 class="mb-4" data-aos="fade-up" data-aos-delay="100">Experience the Magic<br>Feel the Music.</h1>
          <p class="text-white lead" data-aos="fade-up" data-aos-delay="200">The most exclusive access to the world's most
            anticipated concerts.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="section portfolio-section">
    <div class="container">
      <div class="row mb-5 justify-content-center" data-aos="fade-up">
        <div class="col-md-8 text-center">
          <h2 class="mb-4 section-title">Featured Concerts</h2>
          <p>Discover the most prestigious musical events across the globe. From grand stadiums to intimate stages, find
            your rhythm here.</p>
          <p><a href="#" class="btn btn-outline-black">View All Concerts</a></p>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row mb-5 no-gutters">
        <div class="col-sm-6 col-md-6 col-lg-6" data-aos="fade" data-aos-delay="100">
          <a href="{{ route('concert1') }}" class="work-thumb">
            <div class="work-text">
              <h2>Radiohead</h2>
              <p>Jakarta International Stadium, Indonesia</p>
            </div>
            <img src="{{ asset('cardboard-assets/img/concert_1.jpg') }}" alt="Radiohead" class="img-fluid">
          </a>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6" data-aos="fade" data-aos-delay="200">
          <a href="{{ route('concert2') }}" class="work-thumb">
            <div class="work-text">
              <h2>Coldplay</h2>
              <p>Music of the Spheres World Tour - Jakarta</p>
            </div>
            <img src="{{ asset('cardboard-assets/img/coldplay.png') }}" alt="Coldplay" class="img-fluid">
          </a>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6" data-aos="fade" data-aos-delay="300">
          <a href="#" class="work-thumb">
            <div class="work-text">
              <h2>Taylor Swift</h2>
              <p>The Eras Tour - Exclusive Access</p>
            </div>
            <img src="{{ asset('cardboard-assets/img/taylorswift.png') }}" alt="Taylor Swift" class="img-fluid">
          </a>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6" data-aos="fade" data-aos-delay="400">
          <a href="#" class="work-thumb">
            <div class="work-text">
              <h2>Arctic Monkeys</h2>
              <p>Live in Jakarta - 2023 Asia Tour</p>
            </div>
            <img src="{{ asset('cardboard-assets/img/arcticmonkeys.png') }}" alt="Arctic Monkeys" class="img-fluid">
          </a>
        </div>

      </div>

      <div class="row mt-5">
        <div class="col-12 text-center">
          <p><a href="#" class="btn btn-outline-white px-4 py-3">More Upcoming Events</a></p>
        </div>
      </div>
    </div>
  </div>

  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 ml-auto mb-5 order-2">
          <span class="d-block text-uppercase text-primary">Unforgettable Experiences</span>
          <h2 class="mb-4 section-title">Bringing You Closer to the Artists You Love.</h2>
          <p>At LuxTix, we believe that music is more than just sound—it's a memory in the making. Our platform is
            designed to provide seamless access to the most sought-after concerts with an emphasis on elegance and
            security.</p>
          <p class="mb-5">We curate only the best events, ensuring that every seat is a gateway to an extraordinary night.
          </p>
          <p><a href="#" class="btn btn-outline-black">Our Mission</a></p>
        </div>
        <div class="col-lg-6 order-1">
          <figure class="img-dotted-bg">
            <img src="{{ asset('cardboard-assets/img/niki.png') }}" alt="NIKI Concert" class="img-fluid">

            <img src="{{ asset('cardboard-assets/img/work_1.jpg') }}" alt="Concert Atmosphere"
              class="img-fluid img-absolute" data-aos="fade-left">

          </figure>

        </div>
      </div>
    </div>
  </div>

  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 mb-4">
          <div class="service" data-aos="fade-up" data-aos-delay="">
            <span class="icon icon-lock mb-4 d-block"></span>
            <h3>Secure Ticketing</h3>
            <p>Blockchain-backed security ensures your tickets are unique, official, and safe from fraud.</p>
          </div>
        </div>
        <div class="col-lg-3 mb-4">
          <div class="service" data-aos="fade-up" data-aos-delay="100">
            <span class="icon icon-star mb-4 d-block"></span>
            <h3>VIP Access</h3>
            <p>Exclusive backstage passes and lounge access for our premium members.</p>
          </div>
        </div>
        <div class="col-lg-3 mb-4">
          <div class="service" data-aos="fade-up" data-aos-delay="200">
            <span class="icon icon-paper-plane mb-4 d-block"></span>
            <h3>Instant Delivery</h3>
            <p>Receive your digital tickets instantly via our secure app or email.</p>
          </div>
        </div>
        <div class="col-lg-3 mb-4">
          <div class="service" data-aos="fade-up" data-aos-delay="300">
            <span class="icon icon-support mb-4 d-block"></span>
            <h3>24/7 Concierge</h3>
            <p>Our dedicated support team is available around the clock to assist your journey.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="section bg-light block-11">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-md-8 text-center">
          <h2 class="mb-4 section-title">What Fans Say</h2>
        </div>
      </div>
      <div class="nonloop-block-11 owl-carousel">
        <div class="item">
          <div class="block-33 h-100">
            <div class="vcard d-flex mb-3">
              <div class="image align-self-center"><img src="{{ asset('cardboard-assets/img/person_1.jpg') }}" alt="Fan">
              </div>
              <div class="name-text align-self-center">
                <h2 class="heading">Alicia V.</h2>
                <span class="meta">Verified Attendee</span>
              </div>
            </div>
            <div class="text">
              <blockquote>
                <p>&rdquo; LuxTix made attending the Radiohead concert so easy. The VIP entrance was seamless and the
                  seats were exactly as described. A truly premium experience. &ldquo;</p>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="item">
          <div class="block-33 h-100">
            <div class="vcard d-flex mb-3">
              <div class="image align-self-center"><img src="{{ asset('cardboard-assets/img/person_2.jpg') }}" alt="Fan">
              </div>
              <div class="name-text align-self-center">
                <h2 class="heading">David K.</h2>
                <span class="meta">Music Enthusiast</span>
              </div>
            </div>
            <div class="text">
              <blockquote>
                <p>&rdquo; I've never had a more reliable experience buying tickets. No bots, no hidden fees, just pure
                  music. Recommended for any serious concert-goer. &ldquo;</p>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="item">
          <div class="block-33 h-100">
            <div class="vcard d-flex mb-3">
              <div class="image align-self-center"><img src="{{ asset('cardboard-assets/img/person_1.jpg') }}" alt="Fan">
              </div>
              <div class="name-text align-self-center">
                <h2 class="heading">Sarah J.</h2>
                <span class="meta">Pop Culture Critic</span>
              </div>
            </div>
            <div class="text">
              <blockquote>
                <p>&rdquo; The interface is as elegant as the concerts they host. Finding Taylor Swift tickets was a
                  breeze! &ldquo;</p>
              </blockquote>
            </div>
          </div>
        </div>

        <div class="item">
          <div class="block-33 h-100">
            <div class="vcard d-flex mb-3">
              <div class="image align-self-center"><img src="{{ asset('cardboard-assets/img/person_2.jpg') }}" alt="Fan">
              </div>
              <div class="name-text align-self-center">
                <h2 class="heading">James L.</h2>
                <span class="meta">Rock Fan</span>
              </div>
            </div>
            <div class="text">
              <blockquote>
                <p>&rdquo; Arctic Monkeys in Jakarta was a dream come true, and LuxTix helped me get front-row access
                  without any hassle. &ldquo;</p>
              </blockquote>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  <!-- END .block-4 -->
  </div>

  <div class="bg-primary py-5">
    <div class="container text-center">
      <div class="row justify-content-center">
        <div class="col-lg-7">
          <h3 class="text-white mb-2 font-weight-normal" data-aos="fade-right" data-aos-delay="">Be the First to Know</h3>
          <p class="text-white mb-4" data-aos="fade-right" data-aos-delay="100">Subscribe to our newsletter for exclusive
            pre-sale access and artist updates.</p>

          <p class="mb-0" data-aos="fade-right" data-aos-delay="200"><a href="#"
              class="btn btn-outline-white px-4 py-3">Join the Club</a></p>
        </div>
      </div>

    </div>
  </div>
@endsection

@section("ExtraJS2")
@endsection