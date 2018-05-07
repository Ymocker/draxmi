<?php
namespace App\Http\Controllers\Frontend;

use App\News;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    //list of all articles with status=1
    public function index() {
        $news = News::where('status', 1)->orderBy('id', 'desc')->simplePaginate(5);
        return view('frontend/news.list', ['newslist'=>$news]);
    }
    
    // one article with id
    public function getNews($id) {
        if (!is_numeric($id)) { // $id is not numeric
            return redirect('news');
        }
        $news = News::find($id);
        if ($news['status'] == 0) { //user can't see unpublished news
            return redirect('news');
        }
        return view('frontend/news.news', ['one'=>$news]);
    }
}