<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Food;
use App\Category;
class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foods =Food::latest()->paginate(2);
        return view('food.index',compact('foods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("food.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'description'=>'required',
            'price'=>'required|integer',
            'category'=>'required',
            'image'=>'required|mimes:jpg,jpeg,png',

        ]);
     $image = $request->file('image');
     $image_name = time().'.'.$image
                   ->getClientOriginalExtension();
                   
     $destinationPath = public_path('/images');
     $image->move($destinationPath,$image_name);
      Food::create([
           'name'=>$request->get('name'),
            'description'=>$request->get('description'),
            'price'=>$request->get('price'),
            'category_id'=>$request->get('category'),
            'image'=>$image_name
      ]);
    return redirect()->back()->with('success','Food Created');
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
        $food =Food::find($id);
        return view('food.edit',compact('food'));
       
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
        $this->validate($request,[
            'name'=>'required',
            'description'=>'required',
            'price'=>'required|integer',
            'category'=>'required',
            'image'=>'mimes:jpg,jpeg,png',

        ]);
        $food = Food::find($id);
        $name  = $food->image;
        if ($request->hasFile('image')) {
        $image = $request->file('image');
       $image_name = time().'.'.$image
                   ->getClientOriginalExtension();
                   
     $destinationPath = public_path('/images');
     $image->move($destinationPath,$image_name);
        }
        $food->name  =$request->get('name');
        $food->description  =$request->get('description');
        $food->price  =$request->get('price');
        $food->category_id  =$request->get('category');
        $food->image  = $name;
        $food->save();
         return redirect()->route('food.index')
               ->with('message','Food list Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $food   =Food::find($id);
        $food->delete();
        return redirect()->route('food.index')
               ->with('message','Food category Deleted!');
    }

    public function listFood()
    {
         $categories  = Category::with('food')->get();
         return view('index',compact('categories'));
    }

  public function view($id)
  {
      $food  =Food::find($id);
      return view('food.deatails',compact('food'));
  }
    
}
