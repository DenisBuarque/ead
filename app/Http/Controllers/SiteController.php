<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Polo;
use App\Models\Course;
use App\Models\Preregistration;

class SiteController extends Controller
{
    private $user;
    private $polo;
    private $course;
    private $preregistration;

    public function __construct (User $user, Polo $polo, Course $course, Preregistration $preregistration) 
    {
        $this->user = $user;
        $this->polo = $polo;
        $this->course = $course;
        $this->preregistration = $preregistration;
    }

    /**
     * Making Login student
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }

        return redirect('/access')->with('error', 'As credenciais fornecidas não correspondem aos nossos registros');
 
    }


    /**
     * Display a listing of the resource.
     */
    public function index ()
    {
        $polos = $this->polo->all();
        $courses = $this->course->where('status','active')->get();
        return view('site',[
            'polos' => $polos, 
            'courses' => $courses
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function about ()
    {
        $polos = $this->polo->all();
        return view('about',['polos' => $polos]);
    }

    /**
     * Display a listing of the resource.
     */
    public function access ()
    {
        return view('access');
    }

    /**
     * Display a listing of the resource.
     */
    public function show (string $slug)
    {
        $polos = $this->polo->all();
        $courses = $this->course->where('status','active')->get();

        $course = $this->course->where('slug',$slug)->first();
        if (!$course) {
            return redirect('/')->with('success', 'Curso não encontrado');
        }

        return view('show',[
            'polos' => $polos, 
            'course' => $course,
            'courses' => $courses
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function pre_registration (Request $request)
    {
        $data = $request->all();

        $polos = $this->polo->all();
        $courses = $this->course->where('status','active')->get();
        $course = $this->course->where('slug',$data['slug'])->first();

        Validator::make($data, [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:16',
            'email' => 'required|string|max:100',
        ])->validate();

        if($this->preregistration->create($data)) {

            return redirect('/')->with('success', 'Sua solicitação de matrícula foi enviada, aguarde nosso contato');

        } else {
            return redirect('/')->with('error', 'Ocorreu uma erro ao solicitar sua matrícula!');
        }
    }
}
