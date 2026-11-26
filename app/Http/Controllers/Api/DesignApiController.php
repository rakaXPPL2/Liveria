<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;

class DesignApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Design::with('bomItems');

        if ($request->filled('type') && in_array($request->string('type')->toString(), ['solidworks', 'blender', 'hybrid'], true)) {
            $query->where('workflow_type', $request->string('type')->toString());
        }

        $designs = $query->latest()->get()->map(function (Design $design) {
            $design->glb_url = $design->glb_file ? asset('storage/'.$design->glb_file) : null;
            $design->stl_url = $design->stl_file ? asset('storage/'.$design->stl_file) : null;
            $design->blend_url = $design->blend_file ? asset('storage/'.$design->blend_file) : null;

            return $design;
        });

        return response()->json($designs);
    }
}
