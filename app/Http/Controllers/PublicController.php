<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        // Ambil event yang statusnya published
        $events = Event::where('status', 'published')->orderBy('date', 'asc')->get();
        return view('user', compact('events'));
    }

    public function showEvent(Event $event)
    {
        // Pastikan event published atau user adalah owner/admin
        if ($event->status !== 'published') {
            if (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $event->organizer_id)) {
                abort(404);
            }
        }

        $template = $event->template_id ?: 1;
        
        // Cek apakah file blade template ada, jika tidak default ke concert1
        $viewName = "concert" . $template;
        if (!view()->exists($viewName)) {
            $viewName = "concert1";
        }

        return view($viewName, compact('event'));
    }
}
