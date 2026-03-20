@extends('layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Create New Event</h4>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Event Information</div>
                    </div>
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="title">Event Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter Event Title" required value="{{ old('title') }}">
                                        @error('title')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Enter Event Description" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date">Event Date</label>
                                        <input type="datetime-local" class="form-control @error('date') is-invalid @enderror" id="date" name="date" required value="{{ old('date') }}">
                                        @error('date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" placeholder="Enter Location" required value="{{ old('location') }}">
                                        @error('location')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="template_id">Event Page Design (Template)</label>
                                        <select class="form-control @error('template_id') is-invalid @enderror" id="template_id" name="template_id" required>
                                            <option value="1" {{ old('template_id') == '1' ? 'selected' : '' }}>Template 1 (Radiohead Style)</option>
                                            <option value="2" {{ old('template_id') == '2' ? 'selected' : '' }}>Template 2 (Coldplay Style)</option>
                                            <option value="3" {{ old('template_id') == '3' ? 'selected' : '' }}>Template 3 (Taylor Swift Style)</option>
                                            <option value="4" {{ old('template_id') == '4' ? 'selected' : '' }}>Template 4 (Arctic Monkeys Style)</option>
                                            <option value="5" {{ old('template_id') == '5' ? 'selected' : '' }}>Template 5 (Bruno Mars Style)</option>
                                            <option value="6" {{ old('template_id') == '6' ? 'selected' : '' }}>Template 6 (The Weeknd Style)</option>
                                            <option value="7" {{ old('template_id') == '7' ? 'selected' : '' }}>Template 7 (Ed Sheeran Style)</option>
                                            <option value="8" {{ old('template_id') == '8' ? 'selected' : '' }}>Template 8 (Billie Eilish Style)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="banner">Banner Image</label>
                                        <input type="file" class="form-control-file @error('banner') is-invalid @enderror" id="banner" name="banner">
                                        @error('banner')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <button type="submit" class="btn btn-success">Create Event</button>
                            <a href="{{ route('events.index') }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
