<?php

namespace App\Http\Controllers\API\Provider;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\AddonTranslation;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as Validator;

class AddonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validator = Validator::make($request->all(), [
            'name.en' => 'required',
            'name.ar' => 'required',
            'price' => 'required',
            'category_id' =>'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return $this->returnError($validator->errors()->all());
        }

        $addon = new Addon();

        $addon->category_id = $request->category_id;
        $addon->price = $request->price;

        $addon->save();

        // Retrieve the new Category ID
        $addonId = $addon->id;

        // Get the translations from the request
        $translations = [
            'en' => $request['name']['en'],
            'ar' => $request['name']['ar'],
        ];

        foreach ($translations as $locale => $translation) {
            $addonTranslation = new AddonTranslation();
            $addonTranslation->locale = $locale;
            $addonTranslation->name = $translation;
            $addonTranslation->addon_id = $addonId;
            $addonTranslation->save();
        }

        return $this->returnSuccessMessage(trans("apiaddonCreatedSuccessfully"));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $addon = Addon::find($id);

        if (!$addon) {
            return $this->returnError(trans("api.addonNotFound"));
        }

        return $this->returnData( $addon );
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
        $validator = Validator::make($request->all(), [
            'name.en' => 'required',
            'name.ar' => 'required',
            'price' => 'required',
            'category_id' =>'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return $this->returnError($validator->errors()->all());
        }

        $addon = Addon::find($id);

        if (!$addon) {
            return $this->returnError(trans("api.addonNotFound"));
        }

        $addon->category_id = $request->category_id;
        $addon->price = $request->price;
        $addon->save();

        // Get the translations from the request
        $translations = [
            'en' => $request['name']['en'],
            'ar' => $request['name']['ar'],
        ];

        foreach ($translations as $locale => $translation) {
            $addonTranslation = AddonTranslation::where('addon_id', $id)
                ->where('locale', $locale)
                ->first();

            if ($addonTranslation) {
                $addonTranslation->name = $translation;
                $addonTranslation->save();
            } else {
                $addonTranslation = new AddonTranslation();
                $addonTranslation->locale = $locale;
                $addonTranslation->name = $translation;
                $addonTranslation->addon_id = $id;
                $addonTranslation->save();
            }
        }

        return $this->returnSuccessMessage(trans("api.addonUpdatedSuccessfully"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $addon = Addon::find($id);

        if (!$addon) {
            return $this->returnError(trans("api.addonNotFound"));
        }

       AddonTranslation::where('addon_id', $id)->delete();


        $addon->delete();

        return $this->returnSuccessMessage(trans("api.addonDeletedSuccessfully"));
    }
}
