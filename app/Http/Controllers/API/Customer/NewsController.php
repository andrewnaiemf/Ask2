<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->header('per_page', 10);

        $news = News::where('city_id',auth()->user()->city_id)->simplePaginate($perPage);
        return $this->returnData( $news );
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

        $validatedData  =  $this->validateNewsrData( $request );

        if (!is_array( $validatedData)) {
            return $validatedData ;
        }

        $news = News::create( $validatedData);


        if ($request->hasFile('images')) {
            $this->saveNewsImages($news, $request->file('images'));
        }

        return $this->returnSuccessMessage( trans("api.newsCreatedSuccessfully") );

    }

    public function validateNewsrData($request){

        $users_ids = User::where('can_share_news' , 1)->pluck('id')->toArray();;
        $userIdsString = implode(',', $users_ids);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|in:'. $userIdsString,
            'type' => 'required|in:advertisement,documentary,event',
            'city_id' => 'required|exists:cities,id',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'title' => 'required|string',
            'content.en' => 'required|string',
            'content.ar' => 'required|string',
            'content.eu' => 'required|string',
            'url' => 'nullable|url',
            'phone' => 'nullable|string',
            'whatsapp_phone' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError(401,$validator->errors()->all());
        }
        return $validator->validated();

    }

    public function saveNewsImages($news, $images){

        $userId = auth()->user()->id;
        $imagePath = 'public/news/' . $userId;

        // Create the directory if it doesn't exist
        if (!Storage::exists($imagePath)) {
            Storage::makeDirectory($imagePath);
        }

        $imagesPathes = [];

        foreach ($images as $image) {
            $imageName = $image->hashName();
            $image->storeAs($imagePath, $imageName);
            $imageFullPath = 'news/' . $userId . '/' . $imageName;
            array_push($imagesPathes , $imageFullPath);
        }

        $news->update(['images'=> $imagesPathes]);

    }

    public function askForAddNews(){

        $sender_id = auth()->user()->id;
        $resciever_id = User::where('account_type','admin')->first()->id;
        $type = 'addNews';

        Notification::create([
            'user_id' =>  $sender_id,
            'notified_user_id' =>  $resciever_id,
            'type' =>  $type,
            'screen' =>  $type,
            'data' => '{}'
        ]);

        User::find( $sender_id)->update(['can_share_news'=>1]);

        return $this->returnSuccessMessage( trans("api.newsRequestSentSuccessfully") );

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
