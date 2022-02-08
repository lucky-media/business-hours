<?php

namespace LuckyMedia\BusinessHours\Http\Controllers;

use Illuminate\Http\Request;
use LuckyMedia\BusinessHours\Blueprints\BusinessHours;
use Statamic\Http\Controllers\CP\CpController;
use Statamic\Facades\File;
use Statamic\Facades\YAML;

class BusinessHoursController extends CpController
{
    protected $path;

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index() {
        $blueprint = BusinessHours::blueprint();
        $data = BusinessHours::values();

        $fields = $blueprint->fields()->addValues($data)->preProcess();

        return view('business-hours::cp.settings.index', [
            'blueprint' => $blueprint->toPublishArray(),
            'values'    => $fields->values(),
            'meta'      => $fields->meta(),
        ]);
    }

    public function update(Request $request)
    {
        $blueprint = BusinessHours::blueprint();

        $fields = $blueprint->fields()->addValues($request->all());

        $fields->validate();

        File::put(config('statamic.business_hours.path'), YAML::dump($fields->process()->values()->all()));
    }
}
