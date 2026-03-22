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
