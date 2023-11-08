<?php

namespace App\Http\Controllers\API\Provider;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provider = auth()->user()->provider;

        $categories =  $provider->categories;
        $categories->load('addons');

        return $this->returnData( $categories );
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
        ]);

        if ($validator->fails()) {
            return $this->returnError($validator->errors()->all());
        }
        $provider = auth()->user()->provider;

        $category = new Category();

        $category->department_id = $provider->subdepartment_id;

        $category->save();

        // Retrieve the new Category ID
        $categoryId = $category->id;

        // Get the translations from the request
        $translations = [
            'en' => $request['name']['en'],
            'ar' => $request['name']['ar'],
        ];

        foreach ($translations as $locale => $translation) {
            $categoryTranslation = new CategoryTranslation();
            $categoryTranslation->locale = $locale;
            $categoryTranslation->name = $translation;
            $categoryTranslation->category_id = $categoryId;
            $categoryTranslation->save();
        }

        $provider->categories()->attach($category);
        return $this->returnSuccessMessage(trans("api.categoryCreatedSuccessfully"));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with(['products','addons'])->find($id);

        if (!$category) {
            return $this->returnError(trans("api.categoryNotFound"));
        }

        return $this->returnData( $category );
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
    public function update(Request $request, $categoryId)
    {
        $validator = Validator::make($request->all(), [
            'name.en' => 'required',
            'name.ar' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->returnError($validator->errors()->all());
        }

        $category = Category::find($categoryId);

        if (!$category) {
            return $this->returnError(trans("api.categoryNotFound"));
        }

        $category->department_id = auth()->user()->provider->subdepartment_id;
        $category->save();

        // Get the translations from the request
        $translations = [
            'en' => $request['name']['en'],
            'ar' => $request['name']['ar'],
        ];

        foreach ($translations as $locale => $translation) {
            $categoryTranslation = CategoryTranslation::where('category_id', $categoryId)
                ->where('locale', $locale)
                ->first();

            if ($categoryTranslation) {
                $categoryTranslation->name = $translation;
                $categoryTranslation->save();
            } else {
                $categoryTranslation = new CategoryTranslation();
                $categoryTranslation->locale = $locale;
                $categoryTranslation->name = $translation;
                $categoryTranslation->category_id = $categoryId;
                $categoryTranslation->save();
            }
        }

        return $this->returnSuccessMessage(trans("api.categoryUpdatedSuccessfully"));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return $this->returnError(trans("api.categoryNotFound"));
        }

        // Delete the translations related to the category
        CategoryTranslation::where('category_id', $categoryId)->delete();

        // Detach the category from providers or any other relationships
        $category->providers()->detach(); // Assuming a relationship exists

        // Finally, delete the category
        $category->delete();

        return $this->returnSuccessMessage(trans("api.categoryDeletedSuccessfully"));
    }

}
