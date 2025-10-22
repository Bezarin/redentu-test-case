<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Imports\CatalogImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function form()
    {
        return view('import');
    }

    public function store(ImportRequest $request)
    {
        try {
            Excel::import(new CatalogImport, $request->file('file'));

            return redirect()->route('import.form')->with('success', 'Import started successfully!');
        } catch (\Exception $e) {
            return redirect()->route('import.form')
                ->with('error', 'Import failed: '.$e->getMessage());
        }
    }
}
