<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Haruncpi\LaravelLogReader\LaravelLogReader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class LogReaderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getIndex()
    {
        Gate::authorize('viewAny-log');
        return view('LaravelLogReader::index');
    }

    public function getLogs(Request $request)
    {
        Gate::authorize('viewAny-log');
        
        try {
            if ($request->has('date')) {
                return (new LaravelLogReader(['date' => $request->get('date')]))->get();
            } else {
                return (new LaravelLogReader())->get();
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reading logs: ' . $e->getMessage()
            ], 500);
        }
    }

    public function postDelete(Request $request)
    {
        Gate::authorize('viewAny-log');
        
        try {
            if ($request->has('filename')) {
                $file = 'logs/' . $request->get('filename');
                if (File::exists(storage_path($file))) {
                    File::delete(storage_path($file));
                    return ['success' => true, 'message' => 'Successfully deleted'];
                }
            }
            if ($request->has('clear')) {
                if ($request->get('clear') == true) {
                    $files = glob(storage_path('logs/*.log'));

                    array_map('unlink', array_filter($files));
                    return ['success' => true, 'message' => 'All Successfully deleted'];
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting logs: ' . $e->getMessage()
            ], 500);
        }
    }
}
