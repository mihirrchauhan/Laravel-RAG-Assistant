<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    public function documents(Request $request)
    {
        return view('admin.pages.documents');
    }

    public function create()
    {
        return view('admin.pages.documents-create');
    }
}
