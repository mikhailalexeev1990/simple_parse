<?php

namespace App\Http\Controllers;

use App\Models\News;

class Pages extends Controller
{
    public function index()
    {
        $news = News::with('image')->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('index', [
            'news' => $news,
        ]);
    }

    public function getNewsById($id)
    {
        $news_item = News::with('image')->where('id', $id)->first();

        return view('news-item', [
            'news_item' => $news_item
        ]);
    }

    public function getImage($image_path)
    {
        $dir_name = 'images';
        $file_name = "$dir_name/$image_path";

        if (!$image_path && !\Storage::exists($file_name)) {
            abort(404);
        }

        $file = \Storage::get($file_name);
        $type = \Storage::mimeType($file_name);

        return response($file, 200)
            ->header('Content-Type', $type)
            ->header('Pragma', 'public')
            ->header('Content-Disposition', "inline; filename=$image_path")
            ->header('Cache-Control', 'max-age=60, must-revalidate');
    }
}
