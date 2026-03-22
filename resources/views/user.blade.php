@extends('layouts.landingpageconcert.landingconcert')
@section('contentlandingconcert')
  <style>
    /* Custom Override for Concert Cards Hover Effect */
    .work-thumb {
        overflow: hidden !important; 
        border: 4px solid #fff !important; /* Keep the white border static on the container */
    }
    .work-thumb img {
        border: none !important; 
        transition: transform 0.5s ease !important; 
        width: 100%;
        display: block;
    }
    .work-thumb:hover img {
        transform: scale(1.1) !important; 
    }
    .work-thumb:before {
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
    }

    /* New Elegant Concert Cards */
    .concert-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        margin-bottom: 30px;
        border: 1px solid rgba(0,0,0,0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .concert-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.12);
    }
    .concert-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .concert-card-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .concert-card-title {
        font-family: 'DM Serif Display', serif;
        font-size: 1.4rem;
        color: #333;
        margin-bottom: 12px;
        font-weight: 400;
    }
    .concert-meta {
        font-size: 0.9rem;
        color: #777;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    .concert-meta i {
        margin-right: 8px;
        color: #9d50bb;
        width: 16px;
    }
    .concert-price-box {
        margin-top: auto;
        padding-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    .price-label {
        font-size: 0.8rem;
        color: #999;
        display: block;
    }
    .price-value {
        font-weight: bold;
        color: #333;
        font-size: 1.1rem;
    }
    .btn-buy {
        background: linear-gradient(to right, #9d50bb, #6e48aa);
        color: #fff !important;
        border: none;
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    .btn-buy:hover {
        opacity: 0.9;
        box-shadow: 0 4px 15px rgba(157, 80, 187, 0.3);
    }
  </style>
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
  @auth
  <div class="section pb-0" data-aos="fade-up">
    <div class="container">
        <div class="promo-banner-container" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('cardboard-assets/img/hero_1.jpg') }}'); background-size: cover; background-position: center; border-radius: 20px; padding: 60px; color: white; position: relative; overflow: hidden; box-shadow: 0 15px 40px rgba(0,0,0,0.2);">
            <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: linear-gradient(90deg, #9d50bb, transparent); opacity: 0.6;"></div>
            <div class="row align-items-center" style="position: relative; z-index: 2;">
                <div class="col-md-7">
                    <span class="badge badge-danger mb-3 px-3 py-2" style="border-radius: 50px; font-weight: 600;">UPCOMING DEALS</span>
                    <h2 class="display-4 font-weight-bold mb-4" style="font-family: 'DM Serif Display', serif; color: #fff;">Special Ticket Pre-Sale!</h2>
                    <p class="lead mb-4">Exclusive for registered members. Get early access to the most anticipated concerts of 2026/2027 before everyone else.</p>
<<<<<<< HEAD
                    <a href="#concert" class="btn btn-white px-5 py-3" style="border-radius: 50px; font-weight: bold; background: white; color: #333; text-decoration: none;">Browse Available Tickets</a>
=======
                    <a href="{{ route('ticket') }}" class="btn btn-white px-5 py-3" style="border-radius: 50px; font-weight: bold; background: white; color: #333; text-decoration: none;">Browse Available Tickets</a>
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
                </div>
                <div class="col-md-5 d-none d-md-block text-right">
                    <img src="{{ asset('cardboard-assets/img/concert_1.jpg') }}" alt="Featured" style="width: 250px; height: 250px; object-fit: cover; border-radius: 20px; border: 5px solid rgba(255,255,255,0.3); transform: rotate(5deg);">
                </div>
            </div>
        </div>
    </div>
  </div>
  @endauth

<<<<<<< HEAD
  @auth
=======
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
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
<<<<<<< HEAD
    <div class="container-fluid" id="concerts">
      <div class="row mb-5 no-gutters">
        @forelse($events as $event)
        <div class="col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
          <div class="concert-card">
            <img src="{{ $event->banner_url ? Storage::url($event->banner_url) : asset('cardboard-assets/img/concert_' . (($loop->iteration % 4) + 1) . '.jpg') }}" alt="{{ $event->title }}">
            <div class="concert-card-body">
              <h3 class="concert-card-title">{{ $event->title }}</h3>
              <div class="concert-meta">
                <i class="fa fa-calendar"></i> {{ $event->schedule_time ? $event->schedule_time->format('d M Y') : 'Date TBD' }}
              </div>
              <div class="concert-meta">
                <i class="fa fa-map-marker"></i> {{ $event->location ?? 'Venue TBD' }}
              </div>
              <div class="concert-price-box">
                <div>
                  <span class="price-label">Starting from</span>
                  <span class="price-value">Rp. {{ number_format($event->ticket_price ?? 0, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('public.event.show', $event->event_id) }}" class="btn-buy">Detail</a>
=======
    <div class="container-fluid">
      <div class="row mb-5 no-gutters">
        <div class="col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
          <div class="concert-card">
            <img src="{{ asset('cardboard-assets/img/concert_1.jpg') }}" alt="Radiohead">
            <div class="concert-card-body">
              <h3 class="concert-card-title">Radiohead</h3>
              <div class="concert-meta">
                <i class="fa fa-calendar"></i> 12 Oct 2026
              </div>
              <div class="concert-meta">
                <i class="fa fa-map-marker"></i> JIS, Jakarta
              </div>
              <div class="concert-price-box">
                <div>
                  <span class="price-label">Mulai dari</span>
                  <span class="price-value">Rp. 850.000</span>
                </div>
                <a href="{{ route('concert1') }}" class="btn-buy">Detail</a>
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
              </div>
            </div>
          </div>
        </div>
<<<<<<< HEAD
        @empty
        <div class="col-12 text-center py-5">
           <p class="text-muted">No featured concerts available at moment. Please check back later!</p>
        </div>
        @endforelse
=======

        <div class="col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
          <div class="concert-card">
            <img src="{{ asset('cardboard-assets/img/coldplay.png') }}" alt="Coldplay">
            <div class="concert-card-body">
              <h3 class="concert-card-title">Coldplay</h3>
              <div class="concert-meta">
                <i class="fa fa-calendar"></i> 15 Nov 2026
              </div>
              <div class="concert-meta">
                <i class="fa fa-map-marker"></i> Gelora Bung Karno
              </div>
              <div class="concert-price-box">
                <div>
                  <span class="price-label">Mulai dari</span>
                  <span class="price-value">Rp. 1.200.000</span>
                </div>
                <a href="{{ route('concert2') }}" class="btn-buy">detail</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
          <div class="concert-card">
            <img src="{{ asset('cardboard-assets/img/taylorswift.png') }}" alt="Taylor Swift">
            <div class="concert-card-body">
              <h3 class="concert-card-title">Taylor Swift</h3>
              <div class="concert-meta">
                <i class="fa fa-calendar"></i> 20 Dec 2026
              </div>
              <div class="concert-meta">
                <i class="fa fa-map-marker"></i> Exclusive Arena
              </div>
              <div class="concert-price-box">
                <div>
                  <span class="price-label">Mulai dari</span>
                  <span class="price-value">Rp. 2.500.000</span>
                </div>
                <a href="{{ route('concert3') }}" class="btn-buy">detail</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
          <div class="concert-card">
            <img src="{{ asset('cardboard-assets/img/arcticmonkeys.png') }}" alt="Arctic Monkeys">
            <div class="concert-card-body">
              <h3 class="concert-card-title">Arctic Monkeys</h3>
              <div class="concert-meta">
                <i class="fa fa-calendar"></i> 10 Jan 2027
              </div>
              <div class="concert-meta">
                <i class="fa fa-map-marker"></i> Stadium Jakarta
              </div>
              <div class="concert-price-box">
                <div>
                  <span class="price-label">Mulai dari</span>
                  <span class="price-value">Rp. 650.000</span>
                </div>
                <a href="{{ route('concert4') }}" class="btn-buy">detail</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="500">
          <div class="concert-card">
            <img src="{{ asset('cardboard-assets/img/BrunoMars.png') }}" alt="Bruno Mars">
            <div class="concert-card-body">
              <h3 class="concert-card-title">Bruno Mars</h3>
              <div class="concert-meta">
                <i class="fa fa-calendar"></i> 22 Feb 2027
              </div>
              <div class="concert-meta">
                <i class="fa fa-map-marker"></i> Sentul International
              </div>
              <div class="concert-price-box">
                <div>
                  <span class="price-label">Mulai dari</span>
                  <span class="price-value">Rp. 950.000</span>
                </div>
                <a href="{{ route('concert5') }}" class="btn-buy">detail</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="600">
          <div class="concert-card">
            <img src="{{ asset('cardboard-assets/img/the-weeknd.jpg') }}" alt="The Weeknd">
            <div class="concert-card-body">
              <h3 class="concert-card-title">The Weeknd</h3>
              <div class="concert-meta">
                <i class="fa fa-calendar"></i> 30 Mar 2027
              </div>
              <div class="concert-meta">
                <i class="fa fa-map-marker"></i> GBK Madya Stadium
              </div>
              <div class="concert-price-box">
                <div>
                  <span class="price-label">Mulai dari</span>
                  <span class="price-value">Rp. 1.100.000</span>
                </div>
                <a href="{{ route('concert6') }}" class="btn-buy">detail</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="700">
          <div class="concert-card">
            <img src="{{ asset('cardboard-assets/img/edsheeran.jpg') }}" alt="Ed Sheeran">
            <div class="concert-card-body">
              <h3 class="concert-card-title">Ed Sheeran</h3>
              <div class="concert-meta">
                <i class="fa fa-calendar"></i> 15 May 2027
              </div>
              <div class="concert-meta">
                <i class="fa fa-map-marker"></i> JIS, Jakarta
              </div>
              <div class="concert-price-box">
                <div>
                  <span class="price-label">Mulai dari</span>
                  <span class="price-value">Rp. 900.000</span>
                </div>
                <a href="{{ route('concert7') }}" class="btn-buy">detail</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="800">
          <div class="concert-card">
            <img src="{{ asset('cardboard-assets/img/billie-eilish.jpg') }}" alt="Billie Eilish">
            <div class="concert-card-body">
              <h3 class="concert-card-title">Billie Eilish</h3>
              <div class="concert-meta">
                <i class="fa fa-calendar"></i> 20 Jun 2027
              </div>
              <div class="concert-meta">
                <i class="fa fa-map-marker"></i> ICE BSD, Tangerang
              </div>
              <div class="concert-price-box">
                <div>
                  <span class="price-label">Mulai dari</span>
                  <span class="price-value">Rp. 1.300.000</span>
                </div>
                <a href="{{ route('concert8') }}" class="btn-buy">detail</a>
              </div>
            </div>
          </div>
        </div>
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
      </div>

      <div class="row mt-5">
        <div class="col-12 text-center">
          <p><a href="#" class="btn btn-outline-white px-4 py-3">More Upcoming Events</a></p>
        </div>
      </div>
    </div>
  </div>
<<<<<<< HEAD
  @endauth

  <div class="section" id="my-tickets">
    <div class="container">
      @auth
        <div class="row mb-5" data-aos="fade-up">
          <div class="col-md-8">
            <h2 class="mb-4 section-title" style="font-family: 'DM Serif Display', serif;">My Tickets</h2>
            <p>Your current and past concert experiences are gathered here. Access your tickets anytime with ease.</p>
          </div>
        </div>

        @if($myTickets->isEmpty())
        <div class="row" data-aos="fade-up">
          <div class="col-md-12 text-center py-5 bg-white rounded-lg shadow-sm" style="border: 1px dashed #ccc;">
            <div class="mb-3">
              <i class="fa fa-ticket text-muted" style="font-size: 50px; opacity: 0.3;"></i>
            </div>
            <h4 class="text-muted">You haven't ordered any tickets yet.</h4>
            <p>Discover our featured concerts above to start your musical journey!</p>
            <a href="#concerts" class="btn btn-primary btn-sm px-4 py-2 mt-2" style="border-radius: 50px; background: #9d50bb; border: none;">Browse Concerts</a>
          </div>
        </div>
        @else
        <div class="row no-gutters" data-aos="fade-up">
          @foreach($myTickets as $ticket)
          <div class="col-lg-4 col-md-6 mb-4 px-2">
            <div class="ticket-history-card p-4 rounded-lg bg-white" style="box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eee; transition: all 0.3s ease; height: 100%;">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <span class="badge {{ $ticket->isActive() ? 'badge-success' : 'badge-secondary' }} px-3 py-1" style="border-radius: 20px;">
                        {{ $ticket->ticket_status }}
                    </span>
                    <small class="text-muted">{{ $ticket->created_at ? $ticket->created_at->format('d M Y') : 'N/A' }}</small>
                </div>
                
                <h4 class="font-weight-bold mb-1" style="color: #333; font-size: 1.1rem;">{{ $ticket->ticketType->event->title ?? 'Concert Event' }}</h4>
                <p class="small text-muted mb-3"><i class="fa fa-map-marker mr-1"></i> {{ $ticket->ticketType->event->location ?? 'Venue Location' }}</p>
                
                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                    <div>
                        <p class="mb-0 small text-uppercase font-weight-bold text-muted" style="font-size: 0.7rem;">Category</p>
                        <p class="mb-0 font-weight-bold" style="color: #9d50bb;">{{ $ticket->ticketType->name }}</p>
                    </div>
                    <a href="{{ route('tickets.view', $ticket->ticket_id) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 20px; border-color: #9d50bb; color: #9d50bb;">View Detail</a>
                </div>
            </div>
          </div>
          @endforeach
        </div>
        @endif
        
        <style>
          .ticket-history-card:hover {
              transform: translateY(-5px);
              box-shadow: 0 15px 45px rgba(0,0,0,0.1) !important;
              border-color: #9d50bb !important;
          }
        </style>
      @else
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
      @endauth
=======

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
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40
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

<<<<<<< HEAD
=======
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
>>>>>>> 2f5d83ff45da2a0b3e68ae99aade8a7880dd8a40

@endsection


@section("ExtraJS2")
@endsection