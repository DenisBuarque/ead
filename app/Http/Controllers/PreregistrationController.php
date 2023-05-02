<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preregistration;
use App\Models\Course;

class PreregistrationController extends Controller
{
    private $preregistration;
    private $course;

    public function __construct(Preregistration $preregistration, Course $course) 
    {
        $this->middleware('auth');
        $this->preregistration = $preregistration;
        $this->course = $course;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $records = $this->preregistration->all();
        $courses = $this->course->where('status','active')->get();

        $query = $this->preregistration->query();

        $search_name = $request->name;

        if (!empty($search_name)) {
            $query->where('name', $search_name);
        }

        $preregistrations = $query->orderBy('id','DESC')->paginate(10);

        return view('admin.preregistrations.index',[
            'search_name' =>$search_name,
            'preregistrations' => $preregistrations,
            'records' => $records,
            'courses' => $courses
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $preregistration = $this->preregistration->find($id);
        if ($preregistration) {
            return view('admin.preregistrations.edit', ['preregistration' => $preregistration, ]);
        } else {
            return redirect('admin/registrations')->with('alert', 'Registro não encontrado!'); 
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $preregistration = $this->preregistration->find($id);

        if (!$preregistration) {
            return redirect('admin/preregistrations')->with('alert', 'Registro não encontrado!');
        }

        if($preregistration->update($data)) {
            return redirect('admin/preregistrations')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/preregistrations')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->preregistration->find($id);
        if ($data->delete()) {
            return redirect('admin/preregistrations')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/preregistrations')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
