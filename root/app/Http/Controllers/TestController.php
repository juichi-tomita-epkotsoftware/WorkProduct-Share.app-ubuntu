<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\Resident\ResidentService;
use App\Http\Requests\StoreResidentRequest;

class TestController extends Controller
{

    public function __construct(
        private ResidentService $residentService
    ){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Responsec
     */
    public function index()
    {
        $testdatas = Resident::all();
        return view('admin.tests.index',compact('testdatas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResidentRequest $request)
    {
        // dd();
        $validated = $request->validated();
        $this->residentService->create(
            data: collect($validated)->except(['image','phots'])->toArray(),
            image:$request->file('image'),
            photos:$request->file('photos',[]),
            //ここの記述がイマイチ理解できない
        );

        return redirect()->route('admin.tests.index');
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
    public function destroy(Resident $testdata)
    {
        $testdata->delete();
        return redirect()->route('admin.tests.index');
    }
}
