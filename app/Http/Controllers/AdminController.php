<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Determine the user's role and redirect accordingly
        if (auth()->user()) {
            return view('admin.home'); // View for admin dashboard
        }

        return redirect('/'); // Redirect to home or login page if the role is not recognized
    }

    public function optimize()
    {
        Artisan::call('optimize');

        return response()->json(['message' => 'Application optimized successfully']);
    }

    public function index()
    {
        return view('admin.home');
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $updateColumn = $request->columnName;
            $getModel = $this->findUrlInfo($request->url);
            $modelInstance = new $getModel;
            $query = $modelInstance->findOrFail($id);
            $query->$updateColumn = $request->status;
            $query->save();

            return success('Status updated successfully.');
        } catch (\Exception $e) {

            info('Error toggling status for ID '.$id.': '.$e->getMessage());

            return error('Failed to toggle status'.$e->getMessage());
        }
    }

    private function findUrlInfo($url)
    {
        $segments = explode('/', rtrim(parse_url($url, PHP_URL_PATH), '/'));
        $lastSegment = end($segments);

        $modelName = '\App\Models'.\Illuminate\Support\Str::studly(\Illuminate\Support\Str::singular($lastSegment));

        return $modelName;
    }
}
