@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Beli Tiket: {{ $event->name }}</h4>
                </div>
                <div class="card-body">
                    @php
                        $activeBatch = $event->active_batch;
                        $targetTime = null;
                        $countdownLabel = "";

                        if ($activeBatch == 1) {
                            $targetTime = $event->batch1_ended_at;
                            $countdownLabel = "Batch 1 Berakhir Dalam:";
                        } elseif ($activeBatch == 2) {
                            $targetTime = $event->batch2_ended_at;
                            $countdownLabel = "Batch 2 Berakhir Dalam:";
                        } elseif ($event->batch1_ended_at && $event->batch1_ended_at->isPast() && $event->batch2_start_at && $event->batch2_start_at->isFuture()) {
                            $targetTime = $event->batch2_start_at;
                            $countdownLabel = "Batch 2 Dimulai Dalam:";
                        }
                    @endphp

                    @if($targetTime)
                        <div id="countdown-container" class="alert alert-dark text-center mb-4 border-0 shadow-sm" 
                            style="background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); border-left: 4px solid #dc3545 !important;">
                            <h6 class="mb-2 text-white-50 small text-uppercase tracking-wider">{{ $countdownLabel }}</h6>
                            <div id="countdown-timer" class="h2 mb-0 font-weight-bold text-danger" style="text-shadow: 0 0 10px rgba(220, 53, 69, 0.5);">
                                00:00:00
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const targetDate = new Date("{{ $targetTime->format('Y-m-d H:i:s') }}").getTime();
                                const timerElement = document.getElementById('countdown-timer');
                                
                                function updateTimer() {
                                    const now = new Date().getTime();
                                    const distance = targetDate - now;
                                    
                                    if (distance < 0) {
                                        timerElement.innerHTML = "WAKTU HABIS";
                                        timerElement.classList.remove('text-danger');
                                        timerElement.classList.add('text-secondary');
                                        setTimeout(() => window.location.reload(), 3000);
                                        return;
                                    }
                                    
                                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    
                                    timerElement.innerHTML = 
                                        (hours < 10 ? "0" : "") + hours + ":" + 
                                        (minutes < 10 ? "0" : "") + minutes + ":" + 
                                        (seconds < 10 ? "0" : "") + seconds;
                                }

                                updateTimer();
                                setInterval(updateTimer, 1000);
                            });
                        </script>
                    @endif

                    <form action="{{ route('orders.store', $event->event_id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label">Event: <strong>{{ $event->name }}</strong></label>
                            <p class="text-muted small"><i class="fas fa-calendar"></i> {{ $event->schedule_time->format('d M Y, H:i') }}</p>
                        </div>

                        <div class="mb-3">
                            <label for="ticket_type_id" class="form-label">Jenis Tiket</label>
                            <select name="ticket_type_id" id="ticket_type_id" class="form-select @error('ticket_type_id') is-invalid @enderror" required>
                                @foreach($ticketTypes as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }} - Rp {{ number_format($type->price, 0, ',', '.') }} 
                                        ({{ $type->quantity_total - $type->quantity_sold }} Tersisa)
                                    </option>
                                @endforeach
                            </select>
                            @error('ticket_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah Tiket</label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="1" min="1" max="10" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Metode Pembayaran</label>
                            <div class="form-check border p-3 rounded mb-2">
                                <input class="form-check-input ms-0" type="radio" name="payment_method" id="va" value="Virtual Account" checked>
                                <label class="form-check-label ms-4" for="va">Virtual Account</label>
                            </div>
                            <div class="form-check border p-3 rounded">
                                <input class="form-check-input ms-0" type="radio" name="payment_method" id="qris" value="QRIS">
                                <label class="form-check-label ms-4" for="qris">QRIS</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Konfirmasi Pesanan</button>
                            <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-light">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
