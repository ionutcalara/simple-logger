<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Validator;

class LogController extends Controller
{


    /**
     * Store all items in request except for token and tag
     *
     * @param Request $request
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), ['tag' => 'required', 'key' => 'required']);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $this->storeTag($request->all());
        $view = $request->input('view', 'json');
        if ($view == 'html') {
            return view('success', ['message' => 'Success!']);
        }
        return ['message' => 'Success!'];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\View\View|mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function read(Request $request)
    {
        $validator = Validator::make($request->all(), ['tag' => 'required']);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tag = $request->input('tag');
        $file_name = $tag . '.json';
        if (!Storage::disk('local')->exists($file_name)) {
            return response()->json(['message' => 'No info found'], 422);
        }

        $data = $this->getTagData($tag);
        uasort($data, function ($item1, $item2) {
            return $item2['created'] <=> $item1['created'];
        });
        $view = $request->input('view', 'json');
        if ($view == 'html') {
            return view('report', ['data' => $data]);
        }

        if ($view == 'svg') {
            $first_key = key($data);
            $key = $request->input('key', 'ecommerce');
            $label = $request->input('label', 'WooCommerce');
            $background = $request->input('background', '555');
            $contents = View::make('svg')->with([
                'version' => $data[$first_key][$key],
                'label' => $label,
                'background' => $background
            ]);
            $response = Response::make($contents, 200);
            $response->header('Content-Type', 'image/svg+xml');
            return $response;
        }
        return $data;

    }


    /**
     * @param $data
     * @return LogController
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function storeTag($data)
    {
        $existing_data = $this->getTagData($data['tag']);
        if (!$existing_data) {
            $existing_data = [];
        }
        unset($data['api_token']);
        unset($data['view']);
        $data['created'] = Carbon::now()->toIso8601String();
        $existing_data[$data['key']] = $data;
        $this->putTagData($data['tag'], $existing_data);
        return $this;
    }

    private function putTagData($tag, $data)
    {
        $file_name = $tag . '.json';
        Storage::disk('local')->put($file_name, json_encode($data));
        return $this;
    }

    /**
     * @param $tag
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getTagData($tag)
    {
        $file_name = $tag . '.json';
        if (!Storage::disk('local')->exists($file_name)) {
            Storage::disk('local')->put($file_name, '{}');
        }
        $data = Storage::disk('local')->get($file_name);
        return json_decode($data, true);

    }

}
