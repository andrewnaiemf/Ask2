<?php

namespace App\Http\Controllers\API\Provider;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Provider;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->header('per_page', 10);
        $provider = Provider::where('user_id',auth()->user()->id)->first();

        $rooms = Room::with('roomType')
                ->where('provider_id', $provider->id)
                ->simplePaginate($perPage);

        return $this->returnData($rooms);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation =  $this->validateRoomData( $request );

        if ( $validation) {
            return $validation;
        }

        $request['provider_id'] = auth()->user()->provider->id;
        $roomData =  $request->except('images');

        $room = Room::create($roomData);

        $beds = Bed::whereIn('id',$request['beds'])->get();
        foreach ($beds as $bed) {
            $room->beds()->attach($bed);
        }

        if ($request['images']) {
            $this->updateRoomImages($room, $request['images']);
        }
        return $this->returnSuccessMessage( trans("api.room.createdSuccessfully") );

    }

    public function updateRoomImages($room, $images){

        foreach ($room->images as $image_path) {
            Storage::delete('public/'.$image_path);
        }

        $userId = auth()->user()->id;

        $path = 'Provider/' .$userId. '/rooms/';
        $pathes = [];
       foreach ($images as $image) {

            $imageName = $image->hashName();
            $image->storeAs('public/'.$path,$imageName);
            $full_path = $path.$imageName;
            array_push($pathes , $full_path);
        }
        $room->update(['images' => $pathes]);
    }


    public function validateRoomData ( $request ) {

        $validator = Validator::make($request->all(), [

            'room_type_id' => 'required|exists:room_types,id',
            'beds' => 'array|required',
            'beds.*' => 'exists:beds,id',
            'numbers' => 'integer|required',
            'adults' => 'integer|required',
            'kids' => 'integer|required',
            'outdoor' => 'required|string|in:Balcony,Head,View',
            'cost' => 'string|required',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::with('roomType')->findOrFail($id);

        return $this->returnData($room);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_room(Request $request, $id)
    {
        $validation = $this->validateRoomData($request);

        if ($validation) {
            return $validation;
        }

        $room = Room::findOrFail($id);
        $roomData = $request->except('images');
        $room->update($roomData);

        $beds = Bed::whereIn('id', $request['beds'])->get();
        $room->beds()->sync($beds);

        if ($request['images']) {
            $this->updateRoomImages($room, $request['images']);
        }

        return $this->returnSuccessMessage(trans("api.room.updatedSuccessfully"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return $this->returnSuccessMessage(trans("api.room.deletedSuccessfully"));
    }
}
