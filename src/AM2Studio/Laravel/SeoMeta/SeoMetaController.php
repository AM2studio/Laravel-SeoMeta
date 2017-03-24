<?php

namespace AM2Studio\Laravel\SeoMeta;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SeoMetaController extends Controller
{
    public function update(Request $request, string $model_type, int $model_id)
    {
        $model = $model_type::find($model_id);
        $model->update($request->all());

        return back()->with(['success' => 'Meta successfully updated.']);
    }
}
