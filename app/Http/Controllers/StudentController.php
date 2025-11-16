<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        try {

            return view('admin.students.index');

        } catch (Exception $e) {

            info('Error showing Students!', [$e]);

            return redirect()->back()->with('error', 'Students showing failed!.');

        }
    }

    /**
     * Show the specified resource.
     *
     * @param  Students  $singularTableName
     * @return \Illuminate\View\View
     */
    public function create(): View|RedirectResponse
    {
        try {

            return view('admin.students.create');

        } catch (Exception $e) {

            info('Error showing Students!', [$e]);

            return redirect()->back()->with('error', 'Students showing failed!.');

        }
    }

    /**
     * Edit the specified resource.
     *
     * @param  Request  $request
     * @param  Students  $singularTableName
     * @return \Illuminate\View\View
     */
    public function edit(): View|RedirectResponse
    {
        try {

            return view('admin.students.edit');

        } catch (Exception $e) {

            info('Error showing Students!', [$e]);

            return redirect()->back()->with('error', 'Students showing failed!.');

        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\View\View
     */
    public function show(): View|RedirectResponse
    {
        try {

            return view('admin.students.view');

        } catch (\Exception $e) {

            info('Students data showing failed!', [$e]);

            return redirect()->back()->with('error', 'Students showing failed!.');
        }
    }
}
