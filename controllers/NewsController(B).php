<?php
namespace App\Http\Controllers\Backend;

use App\News;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\News\NewsFormRequest;
use Illuminate\Support\Facades\Redirect;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $news = News::orderBy('id', 'desc')->simplePaginate(10);   
        return view('backend.news.list', ['newslist'=>$news]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backend.news.addnews');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(NewsFormRequest $request)
    {
        $news = new News(array(
            'header' => $request->get('header'),
            'article' => $request->get('article'),
            'status' => ($request->get('publish')) ? 1 : 0,
        ));
        $news->save();
        if ($request->file('image')) {
            $imageName = date('dmy', time()) . '_' . $news->id . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(base_path() . '/public/img/news/', $imageName);
            $news->picture = $imageName;
            $news->save();
        }

        return Redirect::route('admin.news.index'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $news = News::find($id);
        return view('backend.news.editnews', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(NewsFormRequest $request, $id)
    {
        $news = News::find($id);
        
        $news->header = $request->get('header');
        $news->article = $request->get('article');
        $news->status = ($request->get('status')) ? 1 : 0;
        
        $imageName = $news->picture;
        if ($request->get('del') == 'delete') {
            $imageName = null;
        }
                
        if ($request->file('picture')) {
            $imageName = $news->created_at->format('dmy') . '_' . $news->id . '.' . $request->file('picture')->getClientOriginalExtension();
            $request->file('picture')->move(base_path() . '/public/img/news/', $imageName);
        }
        $news->picture = $imageName;
        $news->save();
        return Redirect::route('admin.news.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $news = News::find($id);
        $news->delete();
        
        return Redirect::route('admin.news.index'); 
    }
}
