<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class CategoryController extends Controller
{


    public function getAllCategories()
    {
        $categories = Category::all();

        if (count($categories)){
            $response = [
                'data' => $categories,
                'message' => 'Fetched Data Successfully',
                'status' => 200
            ];

        } else {
            $response = [
                'data' => $categories,
                'message' => 'Fetched Data Successfully but there is no data',
                'status' => 200
            ];

        }

        return response($response, 200);
    }

    public function getCategory($id)
    {
        if ($id == null){
            $response = [
                'data' => null,
                'message' => 'Please enter id in path',
                'status' => 401
            ];
            return response($response, 401);

        }

        $cat = Category::find($id);

        if ($cat) {
            $response = [
                'data' => $cat,
                'message' => 'Fetched Data Successfully',
                'status' => 200
            ];

            return response($response, 200);

        } else {

            $response = [
                'data' => null,
                'message' => 'Wrong Id',
                'status' => 401
            ];

            return response($response, 401);
        }

    }

    // public function getSpecifecCategory()
    // {
    //     $id = request("id");

    //     if ($id == null){
    //         $response = [
    //             'data' => null,
    //             'message' => 'Please enter id in path',
    //             'status' => 401
    //         ];
    //         return response($response, 401);

    //     }

    //     $cat = Category::find($id);


    //     $questionsArray = array();


    //     if ($cat) {

    //         foreach ($cat->questions as $question) {
    //             $newmodel = new NewModel();
    //             $newmodel->id = $question->id;
    //             $newmodel->title = $question->title;
    //             $newmodel->answers = $question->answers;
    //             $questionsArray[] = $newmodel;
    //         }

    //         return response($questionsArray , 200);
    //     } else {

    //         $response = [
    //             'data' => null,
    //             'message' => 'Ther\'s no Category with this id',
    //             'status' => 401
    //         ];
    //         return response($response , 401);

    //     }
    // }
    // public function getSpecifecCategory()
    // {
    //     $id = request("id");
    
    //     if ($id == null) {
    //         return response([
    //             'data' => null,
    //             'message' => 'Please enter id in path',
    //             'status' => 401
    //         ], 401);
    //     }
    
    //     $cat = Category::find($id);
    
    //     if (!$cat) {
    //         return response([
    //             'data' => null,
    //             'message' => 'There\'s no Category with this id',
    //             'status' => 401
    //         ], 401);
    //     }
    
    //     $questions = $cat->questions->shuffle()->take(24)->map(function ($question) {
    //         return [
    //             'id' => $question->id,
    //             'title' => $question->title,
    //             'answers' => $question->answers
    //         ];
    //     });
    
    //     return response($questions, 200);
    // }
    public function getSpecifecCategory()
    {
        $id = request("id");
    
        if ($id == null) {
            return response([
                'data' => null,
                'message' => 'Please enter id in path',
                'status' => 401
            ], 401);
        }
    
        $cat = Category::find($id);
    
        if (!$cat) {
            return response([
                'data' => null,
                'message' => 'There\'s no Category with this id',
                'status' => 401
            ], 401);
        }
    
        // Cache key unique to the category
        $cacheKey = 'category_questions_' . $id;
        
        // Get cached questions or generate new ones
        $questions = Cache::remember($cacheKey, now()->addMinutes(2), function () use ($cat) {
            return $cat->questions->shuffle()->take(24)->map(function ($question) {
                return [
                    'id' => $question->id,
                    'title' => $question->title,
                    'answers' => $question->answers
                ];
            });
        });
    
        return response($questions, 200);
    }

    public function addCategory(Request $request)
    {
        $name = $request->name;

        if ($name == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter name query',
                'status' => 401
            ];
            return response($response, 401);
        }

        $imageName = null;

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $imageName = time() . ".". $extension;
            $file->move('uploads/CategoriesImages/', $imageName);
        }

        $image = $imageName;



        $cat = Category::create([
            'name' => $name,
            'image' => $image
        ]);



        if ($cat) {

            $response = [
                'data' => $cat,
                'message' => 'Added Successfully',
                'status' => 200
            ];

            return response($response, 200);
        } else {

            $response = [
                'data' => null,
                'message' => 'Something went wrong',
                'status' => 401
            ];

            return response($response, 401);
        }


    }

    public function updateCategory(Request $request)
    {

        $id = $request->id;
        $name = $request->name;

        if ($id == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter id query',
                'status' => 401
            ];
            return response($response, 401);
        }

        if ($name == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter name query',
                'status' => 401
            ];
            return response($response, 401);
        }


        $imageName = null;

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $imageName = time() . ".". $extension;
            $file->move('uploads/CategoriesImages/', $imageName);
        }

        if ($imageName){
            $image = asset("uploads/CategoriesImages/".$imageName);
        } else {
            $image =null;
        }


        $cat = Category::find($id);

        if ($cat) {

            $cat->update([
                'name' => $name,
                'image' => $image == null ? $cat->image : $image
            ]);


            $response = [
                'data' => $cat,
                'message' => 'Updated Successfully',
                'status' => 200
            ];

            return response($response, 200);

        } else {

            $response = [
                'data' => null,
                'message' => 'Wrong Id',
                'status' => 401
            ];

            return response($response, 401);

        }

    }

    public function deleteCategory()
    {

        $id = request('id');


        if ($id == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter id query',
                'status' => 401
            ];
            return response($response, 401);
        }

        $cat = Category::find($id);

        if ($cat) {

            foreach ($cat->questions as $question) {

                foreach ($question->answers as $answer) {

                    $file = null;

                    if ($answer->image) {
                        $file = 'uploads/AnswersImages/' . $answer->image;
                    }

                    if (file_exists($file)) {
                        unlink($file);
                    }
                }


            }

            $file = null;

            if ($cat->image) {
                $file = 'uploads/CategoriesImages/' . $cat->image;
            }

            if (file_exists($file)) {
                unlink($file);
            }

            $cat->delete();

            $response = [
                'data' => null,
                'message' => 'Deleted Successfully',
                'status' => 200
            ];

            return response($response, 200);

        } else {

            $response = [
                'data' => null,
                'message' => 'Wrong Id',
                'status' => 401
            ];

            return response($response, 401);

        }
    }




    public function categoryPage()
    {
        $categories = Category::all();
        return view('category', ['categories' => $categories]);
    }

    public function updateCategoryView(Request $request)
    {
        $id = $request->category_id;
        $cat = Category::find($id);
        return view('update_category', ['category' => $cat]);
    }

    public function updateCategoryFun(Request $request)
    {

        $category_id = $request->category_id;
        $name = $request->name;

        if ($category_id == null || $name == null) {

            return redirect()->route('category');
        }

        $cat = Category::find($category_id);


        $imageName = null;


        if ($request->hasFile('image')){

            //Delete Previous Img
            if ($cat->image){
                $file ='uploads/CategoriesImages/'.$cat->image;
                if (file_exists($file)){
                    unlink($file);
                }
            }



            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $imageName = time() . ".". $extension;
            $file->move('uploads/CategoriesImages/', $imageName);

        }

        $image = $imageName;


        if ($cat) {

            $cat->update([
                'name' => $name,
                'image' => $image == null ? $cat->image : $image
            ]);
        }

        return redirect()->route('category');
    }

    public function addCategoryView()
    {
        return view('add_category');
    }

    public function addCategoryFun(Request $request)
    {

        $name = $request->name;

        if ($name == null) {
            return redirect()->route('category');
        }

        $imageName = null;

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $imageName = time() . ".". $extension;
            $file->move('uploads/CategoriesImages/', $imageName);
        }

        $image = $imageName;

        Category::create([
            'name' => $name,
            'image' => $image
        ]);

        return redirect()->route('category');
    }

    public function deleteCategoryView()
    {

        $category_id = request('category_id');


        if ($category_id == null) {

            return redirect()->route('category');
        }


        $cat = Category::find($category_id);

        if ($cat) {

            foreach ($cat->questions as $question) {

                foreach ($question->answers as $answer) {

                    $file = null;

                    if ($answer->image){
                        $file ='uploads/AnswersImages/'.$answer->image;
                    }

                    $answer->delete();

                    if (file_exists($file)){
                        unlink($file);
                    }
                }


            }

            $file = null;

            if ($cat->image){
                $file ='uploads/CategoriesImages/'.$cat->image;
            }

            $cat->delete();

            if (file_exists($file)){
                unlink($file);
            }


            $cat->delete();

            return redirect()->route('category');


        }
    }
}

class NewModel {
    public $id;
    public $title;
    public $image;
    public $answers;
}
