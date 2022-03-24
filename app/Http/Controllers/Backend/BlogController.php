<?php

namespace App\Http\Controllers\Backend;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $blogs = Blog::query();

        if ($request->searchKeyword) {
            $blogs->where('title', 'LIKE', "%$request->searchKeyword%")
                    ->orWhere('published_date', 'LIKE', "%$request->searchKeyword%");
        }

        $blogs = $blogs->orderBy('title')->paginate(10);

        return view('backend.blog.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.blog.create');
    }

    /**
     * Store the blog details file.
     *
     * @return \Illuminate\Http\Response
     */
    public function fileUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $fileName = $request->file('upload')->store('blog/details');
   
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('storage/'. $fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:191',
            'short_description' => 'required',
            'details' => 'required',
            'published_date' => 'required|date',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'status' => 'required|in:Draft,Published',
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->short_description = $request->short_description;
        $blog->details = $request->details;
        $blog->published_date = $request->published_date;
        $blog->status = $request->status;
        $blog->photo = $request->file('photo')->store('blog');
        $blog->save();
        
        Alert::toast('Blog successfully created', 'success');

        return redirect()->route('admin.blog.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('backend.blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Blog $blog)
    {
        $this->validate($request, [
            'title' => 'required|max:191',
            'short_description' => 'required',
            'details' => 'required',
            'published_date' => 'required|date',
            'status' => 'required|in:Draft,Published',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($request->hasFile('photo')) {
            
            if (Storage::exists($blog->photo)) {
                Storage::delete($blog->photo);
            }

            $blog->photo = $request->file('photo')->store('blog');
        }

        $blog->title = $request->title;
        $blog->short_description = $request->short_description;
        $blog->details = $request->details;
        $blog->published_date = $request->published_date;
        $blog->status = $request->status;
        $blog->save();
        
        Alert::toast('Blog successfully updated', 'success');

        return redirect()->route('admin.blog.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        if (Storage::exists($blog->photo)) {
            Storage::delete($blog->photo);
        }
        
        $blog->delete();

        Alert::toast('Blog successfully deleted', 'success');

        return redirect()->route('admin.blog.index');
    }
}
