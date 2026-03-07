<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::where('user_id', $request->user()->id)->get();

        return response()->json($events);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'occurrence' => 'required|date',
            'description' => 'nullable|string'
        ]);
        $newEvent = Event::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'occurrence' => $request->occurrence,
            'description' => $request->description
        ]);
        return response()->json($newEvent, 201);
    }

    public function update(Request $request, $id)
    {
            $request->validate([
            'title' => 'sometimes|string|max:255',
            'occurrence' => 'sometimes|date',
            'description' => 'sometimes|string'
        ]);
        $event = Event::where('id', $id)->where('user_id', $request->user()->id)->first();

        if(!$event)
        {
            return response()->json([
                'message' => "Event not found"
            ], 404);
        }
        $event->update([
            'title' => $request->title,
            'occurrence' => $request->occurrence,
            'description' => $request->description
        ]);
        
        return response()->json($event);
    }


    public function destroy(Request $request, $id)
    {
        $event = Event::where('id', $id)->where('user_id', $request->user()->id)->first();

        if(!$event)
        {
            return response()->json([
                'message' => "Event not found"
            ], 404);
        }
        $event->delete();
        return response()->json([
            'message' => 'Event deleted'
        ], 200);
    }
}
