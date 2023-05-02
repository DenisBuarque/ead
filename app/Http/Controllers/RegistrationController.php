<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Registration;
use App\Models\Course;

class RegistrationController extends Controller
{
    private $user;
    private $registration;
    private $course;

    public function __construct(User $user, Registration $registration, Course $course) 
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->registration = $registration;
        $this->course = $course;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $records = $this->registration->all();
        $courses = $this->course->where('status','active')->get();

        $query = $this->registration->query();

        $search_course = $request->course;
        $search_payment = $request->payment;

        if (!empty($search_course)) {
            $query->where('course_id', $search_course);
        }

        if (!empty($search_payment)) {
            $query->where('payment', $search_payment);
        }

        $registrations = $query->orderBy('id','DESC')->paginate(10);

        return view('admin.registrations.index',[
            'records' => $records,
            'courses' => $courses,
            'search_course' => $search_course,
            'search_payment' =>$search_payment,
            'registrations' => $registrations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = $this->course->where('status','active')->get();
        $students = $this->user->where('access','active')->where('nivel','student')->orderBY('name','ASC')->get();
        return view('admin.registrations.create', [
            'courses' => $courses, 
            'students' => $students
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        Validator::make($data, [
            'course_id' => 'required',
            'user_id' => 'required',
        ])->validate();

        if($this->registration->create($data)) {
            return redirect('admin/registrations')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('admin/registrations')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $courses = $this->course->where('status','active')->get();
        $students = $this->user->where('access','active')->where('nivel','student')->get();

        $registration = $this->registration->find($id);
        if ($registration) {
            return view('admin.registrations.edit', [
                'registration' => $registration, 
                'courses' => $courses, 
                'students' => $students,
            ]);
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
        $registration = $this->registration->find($id);

        if (!$registration) {
            return redirect('admin/registrations')->with('alert', 'Registro não encontrado!');
        }

        Validator::make($data, [
            'course_id' => 'required',
            'user_id' => 'required',
        ])->validate();

        if($registration->update($data)) {
            return redirect('admin/registrations')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('admin/registrations')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = $this->registration->find($id);
        if ($data->delete()) {
            return redirect('admin/registrations')->with('success', 'Registro excluído com sucesso!');
        } else {
            return redirect('admin/registrations')->with('error', 'Erro ao excluir o registro!');
        }
    }
}
