<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PHPHtmlParser\Dom;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ScraperController extends Controller
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
        $request->validate([
            'url' => ['required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
        ]);

        $dom = new Dom;
        $items = [];

        try {
            $dom->loadFromFile($request->url);
            $contents = $dom->find('.results-by-facets .product');
            foreach ($contents as $key => $content) {
                $child   = $content->firstChild();
                $sibling = $child->nextSibling();
                $dom->loadStr($sibling->outerHtml);

                $title = $dom->find('.product-title a')[0];
                if ($title) {
                    $items[$key]['title'] = trim($title->text);
                }

                $image = $dom->find('.product-image-front img[src]')[0];
                if ($image) {
                    $image = $image->getAttribute('src');
                    $items[$key]['image'] = trim($image);

                    // Get the image contents from the URL
                    $contents = file_get_contents($image);

                    // Store the image in the storage/app/public directory
                    $fileName = basename($image);
                    Storage::put("public/images/$fileName", $contents);
                }

                $price = $dom->find('.amount')[0];
                if ($price) {
                    $items[$key]['price'] = trim($price->text);
                }

                $author = $dom->find('.wd_product_categories a')[0];
                if ($author) {
                    $items[$key]['author'] = trim($author->text);
                }
            }

            $jsonData = json_encode($items, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($jsonData) {
                Storage::disk('public')->put('scraped_data.json', $jsonData);
            }
            return view('pages.scraped_data', compact('items'));
        } catch (\Throwable $th) {
            Log::error($th);
        }

        return redirect()->back()->withErrors(["url" => "This URL Not supported"]);
    }

    public function scrapedImagesDownload()
    {
        try {
            $zip = new ZipArchive;
            $filePath = public_path('storage/images');
            $fileName = 'images' . '.zip';

            if ($zip->open($fileName, \ZipArchive::CREATE) == TRUE) {
                $files = File::files($filePath);
                foreach ($files as $key => $value) {
                    $relativeName = basename($value);
                    $zip->addFile($value, $relativeName);
                }
                $zip->close();
            }
            return response()->download(public_path($fileName));
        } catch (\Throwable $th) {
            Log::error($th);
        }
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

    public function jsonDataSortingForm()
    {
        return view('pages.json_data');
    }

    public function jsonDataSorting(Request $request)
    {
        try {
            $file = $request->file('json-file');
            if ($file->getClientOriginalExtension() == 'json') {
                $contents = file_get_contents($file);
                $data = json_decode($contents, true);
                $getData = [];
                foreach ($data as $value) {
                    if (array_key_exists('saved_search', $value) && $value['saved_search'] !== []) {
                        $getSavedData = $value['saved_search'][0];
                        $getData[] = $getSavedData['keys'];
                    }
                }
                if (count($getData) > 0) {
                    $jsonData = json_encode($getData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    Storage::disk('public')->put('json_data.json', $jsonData);
                    $filePath = storage_path('app/public/json_data.json');
                    return response()->download($filePath);
                } else {
                    return redirect()
                        ->back()
                        ->withErrors(["json-file" => "File is not supported, Please Upload raw_saved_search.json file"]);
                }
            } else {
                return redirect()
                    ->back()
                    ->withErrors(["json-file" => "File is not supported"]);
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
