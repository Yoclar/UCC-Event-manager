<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::where('user_id', $request->user()->id)->get();

        return response()->json($events);
    }


    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        $newEvent = Event::create([
            'user_id' => $data->user()->id,
            'title' => $data['title'],
            'occurrence' => $data['occurrence'],
            'description' => $data['description'],
        ]);
        return response()->json($newEvent, 201);
    }

    public function update(UpdateEventRequest $request, $id)
    {
        $data = $request->validated();
        $event = Event::where('id', $id)->where('user_id', $request->user()->id)->first();

        if(!$event)
        {
            return response()->json([
                'message' => "Event not found"
            ], 404);
        }
        $event->update($data);
        
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
