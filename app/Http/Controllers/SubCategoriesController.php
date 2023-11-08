<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categories;
use App\Section;
use App\Video;
use App\Template;
use DB;
class SubCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $imagePath="/uploads/category/";
    public function index()
    {
        $categories=Categories::where('parent_id','!=',0)->paginate(15);
        //->get();
        //paginate(15);
        return view('subCategories.index', ['categories' =>$categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=Categories:: where(['parent_id'=>0])->where('status','=',1)->get();
         $videoList =Section:: where('status','=',1)->get();
       return view('subCategories.create',['title'=>'Add Sub Category','categoryList' =>$categories,'videoList'=>$videoList]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['required'],
            'video_type'=>['required']
        ]);
        $category = new Categories;
        $category->name = strip_tags($request->name);
        $category->parent_id = $request->parent_id;
        $category->video_type = $request->video_type;

        if ($request->hasFile('cat_icon')) {
            $filenameWithExt = $request->file('cat_icon')->getClientOriginalName ();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('cat_icon')->getClientOriginalExtension();
            $fileNameToStore = $filename. '_'. time().'.'.$extension;
            $request->cat_icon->move(public_path('uploads/category'), $fileNameToStore);
        }
        else {
            $fileNameToStore = 'No-image.jpeg';
        }
        $category->cat_icon = $fileNameToStore;
       
        $category->save();
        return redirect()->route('subCategories.index')->withStatus(__('Sub Category successfully created.'));
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
        $categories = Categories:: where(['parent_id'=>0,'status'=>1])->get();
           $videoList =Section:: where('status','=',1)->get();
        return view('subCategories.edit', ['categories' =>Categories::where(['id'=>$id])->first(),'categoryList' =>$categories,'videoList'=>$videoList]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {       
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
             'video_type'=>['required']
        ]);
        
        if ($request->hasFile('cat_icon')) {
            $filenameWithExt = $request->file('cat_icon')->getClientOriginalName ();
            // Get Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Extension
            $extension = $request->file('cat_icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = $filename. '_'. time().'.'.$extension;
            $request->cat_icon->move(public_path('uploads/category'), $fileNameToStore);
            
            $categories['cat_icon'] = $fileNameToStore;
            
        }

        $categories['parent_id'] = $request->parent_id;
        $categories['name'] = $request->name;
          $categories['video_type'] = $request->video_type;
        
        Categories::where(['id'=>$request->id])->update($categories);

        return redirect()->route('subCategories.index')->withStatus(__('Category successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Categories::findorfail($id);
       
        if($category)
        {

            $path = public_path()."/uploads/category/".$category->cat_icon;
            unlink($path);
            $affectedRows = Categories::where('id', '=', $id)->delete();
        }
        return redirect()->route('subCategories.index')->withStatus(__('Sub Category successfully deleted.'));
    }

    
    public function getSubCategoryList($id){
        $data['subcategory_list'] = Categories::where(['parent_id'=>$id])->where(['status'=>1])->orderBy('name','ASC')->get();
        $data['path'] =  asset("uploads/category/") ;
        return response()->json([
            'data' =>$data,
            'status' => true,
            'errMsg' => ''
        ]);
    }
     public function status($id,$status)
    {  
        $categories = Categories::findorfail($id);
        if( $categories->status==1){
            $categories->status=0;
        }else{
            $categories->status=1;
        }
       // $categories->status=$status;
        $categories->update();
        echo true;
        
    }
    public function clone($id)
    { 
        $categories = Categories:: where(['parent_id'=>0,'status'=>1])->get();
        $videoList =Section:: where('status','=',1)->get();
        return view('subCategories.clone', ['categories' =>Categories::where(['id'=>$id])->first(),'categoryList' =>$categories,'videoList'=>$videoList]);
    }

    public function cloneSubcategory(Request $request,$id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
             'video_type'=>['required'],
             'cat_icon'=>['required']
        ]);
        
        $categories =  new Categories();
        
        if ($request->hasFile('cat_icon')) {
            $filenameWithExt = $request->file('cat_icon')->getClientOriginalName ();
            // Get Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Extension
            $extension = $request->file('cat_icon')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = $filename. '_'. time().'.'.$extension;
            $request->cat_icon->move(public_path('uploads/category'), $fileNameToStore);
            
            $categories['cat_icon'] = $fileNameToStore;
            
        }

        $categories->parent_id = $request->parent_id;
        $categories->name = $request->name;
        $categories->video_type = $request->video_type;
        
        $categories->save();
        
        //return redirect()->route('subCategories.index')->withStatus(__('SubCategory successfully cloned.'));
       
        
        $videodata = Video::select('category','subcategory')->whereRaw("find_in_set('$id',subcategory)")->first();
        if($videodata!=null){
            
             $category_array =explode(',',$videodata->category);
             if(in_array($categories->parent_id, $category_array )){
                 $videodata['category']=$videodata->category;
             }else{
               $videodata['category']=$videodata->category.','.$categories->parent_id;
             }
             
             $videodata['subcategory']=$videodata->subcategory.','.$categories->id;
             
        } 
          
        $videotemplate = Template::select('subcategory')->whereRaw("find_in_set('$id',subcategory)")->first();
        if($videotemplate!=null){
           $subcat = $videotemplate->subcategory.','.$categories->id;
        }
        else{
            $subcat = $categories->id;
        }
        DB::table('templates')->whereRaw("find_in_set('$id',subcategory)")->update(['subcategory'=>$subcat]);
   
        if($videodata!=null){
            DB::table('video')->whereRaw("find_in_set('$id',subcategory)")->update(['subcategory'=>$videodata['subcategory'],'category'=>$videodata['category']]);
            return redirect()->route('subCategories.index')->withStatus(__('Subcategory successfully cloned.'));
        }else{
            return redirect()->route('subCategories.index')->withStatus(__('Subcategory successfully not cloned because this subcategory data is not present.'));
        }
        
    }
}
