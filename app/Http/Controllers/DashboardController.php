<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\File;
use App\Models\User;
use App\Models\Polo;
use App\Models\Course;
use App\Models\Discipline;
use App\Models\DisciplineModule;
use App\Models\CustomerService;
use App\Models\CustomerServiceComment;
use App\Models\Registration;
use App\Models\Preregistration;
use App\Models\Inscription;
use App\Models\Mural;
use App\Models\DirectChat;
use App\Models\DirectChatMessage;
use App\Models\Forum;
use App\Models\ForumComment;
use App\Models\ForumOpnion;
use App\Models\Job;
use App\Models\OpenQuestion;
use App\Models\OpenResponse;
use App\Models\MultipleQuestion;
use App\Models\MultipleResponse;
use App\Models\Access;

class DashboardController extends Controller
{
    private $user;
    private $polo;
    private $course;
    private $discipline;
    private $customerservice;
    private $customerserviceComment;
    private $registration;
    private $preregistration;
    private $inscription;
    private $mural;
    private $directchat;
    private $directchatmessage;
    private $forum;
    private $forumcomment;
    private $forumopnion;
    private $job;
    private $openquestion;
    private $openresponse;
    private $multiplequestion;
    private $multipleresponse;
    private $access;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        User $user, 
        Polo $polo, 
        Course $course, 
        Discipline $discipline, 
        CustomerService $customerservice, 
        CustomerServiceComment $customerservicecomment, 
        Preregistration $preregistration, 
        Registration $registration, 
        Inscription $inscription, 
        Mural $mural, 
        DisciplineModule $disciplinemodule,
        DirectChat $directchat, 
        DirectChatMessage $directchatmessage, 
        Forum $forum, 
        ForumComment $forumcomment, 
        ForumOpnion $forumopnion,
        Job $job, 
        OpenQuestion $openquestion,
        OpenResponse $openresponse, 
        MultipleQuestion $multiplequestion,
        MultipleResponse $multipleresponse,
        Access $access)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->polo = $polo;
        $this->course = $course;
        $this->discipline = $discipline;
        $this->customerservice = $customerservice;
        $this->customerservicecomment = $customerservicecomment;
        $this->preregistration = $preregistration;
        $this->registration = $registration;
        $this->inscription = $inscription;
        $this->mural = $mural;
        $this->disciplinemodule = $disciplinemodule;
        $this->directchat = $directchat;
        $this->directchatmessage = $directchatmessage;
        $this->forum = $forum;
        $this->forumcomment = $forumcomment;
        $this->forumopnion = $forumopnion;
        $this->job = $job;
        $this->openquestion = $openquestion;
        $this->openresponse = $openresponse;
        $this->multiplequestion = $multiplequestion;
        $this->multipleresponse = $multipleresponse;
        $this->access = $access;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = $this->user->all();
        $polos = $this->polo->all();
        $courses = $this->course->orderBy('title')->get();
        $disciplines = $this->discipline->all();
        $customerservices = $this->customerservice->all();
        $preregistrations = $this->preregistration->where('status','inactive')->get();
        $registrations = $this->registration->orderBy('id','DESC')->get();
        $inscriptions = $this->inscription->all();
        $jobs = $this->job->where('note', 0)->get();
        $openresponses = $this->openresponse->where('note', 0)->get();

        $directchats = $this->directchat->orderBy('created_at')->get();

        $directchat = DB::table('users')
            ->where('nivel','student')
            ->join('direct_chats', 'direct_chats.user_id', '=', 'users.id')
            ->select('direct_chats.*')
            ->get()
            ->groupBy('user_id');

        $student = $this->user->where('id', auth()->user()->id)->first();

        return view('dashboard',[
            'users' => $users, 
            'polos' => $polos, 
            'courses' => $courses, 
            'disciplines' => $disciplines, 
            'customerservices' => $customerservices,
            'registrations' => $registrations,
            'preregistrations' => $preregistrations,
            'inscriptions' => $inscriptions,
            'student' => $student,
            'directchats' => $directchats,
            'jobs' => $jobs,
            'openresponses' => $openresponses
        ]);
    }

    /**
     * verify quantity inscription discipline student by course
     */
    public static function inscriptions_student ($course) 
    {
        $user = auth()->user()->id;

        $inscrition = DB::table('inscriptions')
                        ->where('user_id', $user)
                        ->where('course_id', $course)
                        ->where('status', 'pago')
                        ->count();
        return $inscrition;
    }

    /**
     * calculator date 
     */
    public static function expiration_date ($course, $discipline) 
    {
        $user = auth()->user()->id;

        $expiraction = DB::table('inscriptions')
                        ->where('user_id',$user)
                        ->where('course_id', $course)
                        ->where('discipline_id', $discipline)
                        ->first();
        return $expiraction;
    }

    /*
    * consult inscription student
    */
    public static function verify_inscription ($course, $discipline) 
    {
        $user = auth()->user()->id;

        $inscrition = DB::table('inscriptions')
                        ->where('user_id', $user)
                        ->where('course_id', $course)
                        ->where('discipline_id', $discipline)
                        ->where('status', 'pago')
                        ->count();
        return $inscrition;
    }

    /*
    * Access function classroom
    */
    public function classroom (string $slug) 
    {
        // consult if course exist
        $course = $this->course->where('slug', $slug)->first();
        if (!$course) {
            return redirect('dashboard')->with('error', 'Curso não encontrado.');
        }

        // consult if student this registration in course
        $verify_registrion = $this->registration->where('course_id', $course->id)->where('payment','yes')->where('user_id', auth()->user()->id)->count();
        if ($verify_registrion == 0) {
            return redirect('dashboard')->with('error', 'Você não está matrículado neste curso.');
        }

        // consult disciplines by course
        $disciplines = $this->discipline->where('course_id', $course->id)->get();
        // consult mural by course
        $murals = $this->mural->where('course_id', $course->id)->get();
        // consult customer service comment by student
        $customerservices = $this->customerservice->where('user_id', auth()->user()->id)->whereIn('status',['open','pending'])->count() ;
        // consult request inscription in user
        $requestinscriptions = $this->inscription->where('user_id', auth()->user()->id)->where('status','pendente')->get();

        return view('classroom',[
            'course' => $course, 
            'disciplines' => $disciplines, 
            'murals' => $murals,
            'customerservices' => $customerservices,
            'requestinscriptions' => $requestinscriptions
        ]);
    }

    /**
     * Generate PDF
     */
    public function pdf (int $id) 
    {
        $student = $this->user->where('id', auth()->user()->id)->first();
        $course = $this->course->find($id);
        $disciplines = $this->discipline->where('course_id', $course->id)->get();

        //return \PDF::loadView('admin.students.pdf', ['courses' => $courses])->setPaper('a4', 'landscape')->download('arquivo-pdf-gerado.pdf');  
        return \PDF::loadView('pdf', [
            'course' => $course,
            'disciplines' => $disciplines,
            'student' => $student,
            //'inscriptions' => $inscriptions
        ])->setPaper('a4', 'portrait')->stream('arquivo-pdf-gerado.pdf');  
    }

    /*
    * Access function living room
    */
    public function livingroom (string $course_slug, string $discipline_slug) 
    {
        // consult if course exist
        $course = $this->course->where('slug', $course_slug)->first();
        if (!$course) {
            return redirect('dashboard')->with('error', 'Curso não encontrado.');
        }

        // consult if student is registered in course
        $verify_registrion = $this->registration->where('course_id', $course->id)->where('payment','yes')->where('user_id', auth()->user()->id)->count();
        if ($verify_registrion == 0) {
            return redirect('dashboard')->with('error', 'Você não está matrículado no curso solicitado.');
        }

        // consult if discipline exist
        $discipline = $this->discipline->where('slug', $discipline_slug)->first();
        if (!$discipline) {
            return redirect('classroom/' . $course_slug)->with('error', 'Disciplina não encontrada.');
        }

        //const if student is registered in discipline
        $verify_inscription = $this->inscription->where('course_id', $course->id)->where('discipline_id', $discipline->id)->where('user_id', auth()->user()->id)->where('status','pago')->count();
        if (!$verify_inscription) {
            return redirect('classroom/' . $course_slug)->with('error', 'Você não está inscrito na disciplina solicitada.');
        }

        $user = auth()->user()->id;

        // consult mural by course
        $murals = $this->mural->where('discipline_id', $discipline->id)->orderBy('id','DESC')->get();
        // consult customer service comment by student
        $customerservicecomments = $this->customerservicecomment->where('user_id', $user)->where('view_user', true)->count() ;
        // consult discipline modules by discipline
        $disciplinemodules = $this->disciplinemodule->where('discipline_id', $discipline->id)->orderBy('id','DESC')->get();
        // list data direct chat
        $directchat = $this->directchat->where('course_id', $discipline->course_id)
                                       ->where('discipline_id', $discipline->id)
                                       ->where('user_id', $user)
                                       ->where('active', 1)
                                       ->first();
        // update messages user direct chat

        if ($directchat) {
            $affected = DB::table('direct_chat_messages')
                  ->where('direct_chat_id', $directchat->id)->where('user_id', $user)
                  ->update(['check_admin' => 0]);
        }
        
        return view('livingroom',[
            'course' => $course, 
            'discipline' => $discipline, 
            'disciplinemodules' => $disciplinemodules, 
            'murals' => $murals,
            'customerservicecomments' => $customerservicecomments,
            'directchat' => $directchat
        ]);
    }

    /**
     * consult description module discipline
     */
    public function module (string $id) 
    {
        $disciplinemodule = $this->disciplinemodule->find($id);
        return view('module',['disciplinemodule' => $disciplinemodule]);
    }

    /**
     * init conversation at chat
     */
    public function start_chat (Request $request) 
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'subject' => 'required',
        ]);

        $data['user_id'] = auth()->user()->id;

        if($this->directchat->create($data)) {
            return redirect('livingroom/' . $data['course_slug'] . '/' . $data['discipline_slug'])->with('success', 'Você iniciou o chat, sejá bem-vindo.');
        } else {
            return redirect('livingroom/' . $data['course_slug'] . '/' . $data['discipline_slug'])->with('error', 'O chat não pode ser iniciado!');
        }
    }

    /*
    * Storage data new direct chat mesage
    */
    public function direct_chat_message (Request $request) 
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'message' => 'required',
        ]);

        $data['check_admin'] = false;
        $data['check_student'] = true;
        $data['user_id'] = auth()->user()->id;

        if ($validator->passes()) {

            DirectChatMessage::create($data);

            return response()->json(['success'=>'Mensagem']);
        }
        return response()->json(['error'=>$validator->errors()]);
    }

    /**
     * listing data direct chat message
     */

    public function chat_messages (int $id) 
    {
        $directchatmessages = $this->directchatmessage->where('direct_chat_id', $id)->orderBy('id','DESC')->get();
        return view('chat_table',['directchatmessages' => $directchatmessages]);
    }

    /**
     * access functoin types of evaluations
     */
    public function evaluation (string $course_lug, string $discipline_slug) 
    {
        $user = auth()->user()->id;

        // consult if course exist
        $course = $this->course->where('slug', $course_lug)->first();
        if (!$course) {
            return redirect('dashboard')->with('error', 'Curso não encontrado.');
        }

        // consult if student is registered in course
        $verify_registrion = $this->registration->where('course_id', $course->id)->where('payment','yes')->where('user_id', $user)->count();
        if ($verify_registrion == 0) {
            return redirect('dashboard')->with('error', 'Você não está matrículado no curso solicitado.');
        }

        // consult if discipline exist
        $discipline = $this->discipline->where('slug', $discipline_slug)->first();
        if (!$discipline) {
            return redirect('classroom/' . $course_slug)->with('error', 'Disciplina não encontrada.');
        }

        //const if student is registered in discipline
        $verify_inscription = $this->inscription->where('course_id', $course->id)->where('discipline_id', $discipline->id)->where('status','pago')->where('user_id', $user)->count();
        if (!$verify_inscription) {
            return redirect('classroom/' . $course_slug)->with('error', 'Você não está inscrito na disciplina solicitada.');
        }

        //checks if there is any file uploaded by the user
        $verify_file = $this->job->where('user_id', $user)->where('course_id', $course->id)->where('discipline_id', $discipline->id)->count();
        $verify_open_question = $this->openresponse->where('user_id', $user)->where('course_id', $course->id)->where('discipline_id', $discipline->id)->count();
        $verify_multiple_question = $this->multipleresponse->where('user_id', $user)->where('course_id', $course->id)->where('discipline_id', $discipline->id)->count();

        $inscriptions = $this->inscription->where('discipline_id', $discipline->id)->where('status','pago')->get();

        $access_multiple = $this->access->where('user_id', auth()->user()->id)->where('discipline_id', $discipline->id)->where('type','multiple')->count();
        $access_open = $this->access->where('user_id', auth()->user()->id)->where('discipline_id', $discipline->id)->where('type','open')->count();

        return view('evaluation',[ 
            'inscriptions' => $inscriptions, 
            'discipline' => $discipline,
            'course' => $course,
            'verify_file' => $verify_file,
            'verify_open_question' => $verify_open_question,
            'verify_multiple_question' => $verify_multiple_question,
            'access_multiple' => $access_multiple,
            'access_open' => $access_open
        ]);
    }

    /**
     * Store file work academic at table jobs
     */
    public function store_job (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'course_id' => 'required',
            'discipline_id' => 'required',
            'file' => ['required', File::types(['pdf', 'doc','docx'])->max(10 * 1024)],
        ])->validate();

        $user = $this->user->where('id', auth()->user()->id)->first();
        
        $data['user_id'] = auth()->user()->id;
        $data['note'] = 0;

        if($request->hasFile('file') && $request->file('file')->isValid()){

            $file = $request->file('file');
            $orignal_filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); 
            $fileName = Str::slug($user->name, '-') . '.' . $extension; 

            $fileRename = $request->file('file')->storeAs('jobs', $fileName, 'public');
            //$file = $request->file->store('jobs','public');
            $data['file'] = $fileRename;
        }
        
        if($this->job->create($data)) {
            return redirect('dashboard/evaluation/' . $data['course'] . '/' . $data['discipline'])->with('success', 'Arquivo enviado com sucesso!');
        }else{
            return redirect('dashboard/evaluation/' . $data['course'] . '/' . $data['discipline'])->with('error', 'Erro ao enviar o arquivo!');
        }
    }

    /**
     * access functoin avaliation open question
     */
    public function openquestion (string $course_lug, string $discipline_slug) 
    {
        // consult if course exist
        $course = $this->course->where('slug', $course_lug)->first();
        if (!$course) {
            return redirect('dashboard')->with('error', 'Curso não encontrado.');
        }

        // consult if student is registered in course
        $verify_registrion = $this->registration->where('course_id', $course->id)->where('payment','yes')->where('user_id', auth()->user()->id)->count();
        if ($verify_registrion == 0) {
            return redirect('dashboard')->with('error', 'Você não está matrículado no curso solicitado.');
        }

        // consult if discipline exist
        $discipline = $this->discipline->where('slug', $discipline_slug)->first();
        if (!$discipline) {
            return redirect('classroom/' . $course_slug)->with('error', 'Disciplina não encontrada.');
        }

        //const if student is registered in discipline
        $verify_inscription = $this->inscription->where('course_id', $course->id)->where('discipline_id', $discipline->id)->where('status','pago')->where('user_id', auth()->user()->id)->count();
        if (!$verify_inscription) {
            return redirect('classroom/' . $course_slug)->with('error', 'Você não está inscrito na disciplina solicitada.');
        }

        $access = $this->access->where('user_id', auth()->user()->id)->where('discipline_id', $discipline->id)->where('type','open')->count();
        if ($access == 4) {
            return redirect('classroom/' . $course_slug)->with('error', 'Seu limite de acesso a avaliação acabou.');
        }

        // insere o acesso do usuário a avaliação.
        $this->access->create([
            'user_id' => auth()->user()->id,
            'course_id' => $course->id,
            'discipline_id' => $discipline->id,
            'type' => 'open'
        ]);

        $openquestions = $this->openquestion->where('course_id', $course->id)->where('discipline_id', $discipline->id)->get();

        return view('openquestion',[ 
            'discipline' => $discipline,
            'course' => $course,
            'openquestions' => $openquestions
        ]);
    }

    /**
     * Store questions open
     */
    public function store_openresponse (Request $request) 
    {
        $data = $request->all();
        for ($i = 0; $i < 2; $i++) {
            $this->openresponse->create([
                'open_question_id' => $data['open_question_id'][$i],
                'user_id' => auth()->user()->id,
                'course_id' => $data['course_id'] ,
                'discipline_id' => $data['discipline_id'],
                'resposta' => $data['resposta'][$i],
                'note' => 0,
            ]);
        }

        return redirect('classroom/' . $data['slug'])->with('success', 'Resposta enviada com sucesso.');
    }

    /**
     * access functoin avaliation open question
     */
    public function multiple (string $course_slug, string $discipline_slug) 
    {
        // consult if course exist
        $course = $this->course->where('slug', $course_slug)->first();
        if (!$course) {
            return redirect('classroom/' . $course_slug)->with('error', 'Curso não encontrado.');
        }

        // consult if student is registered in course
        $verify_registrion = $this->registration->where('course_id', $course->id)->where('payment','yes')->where('user_id', auth()->user()->id)->count();
        if ($verify_registrion == 0) {
            return redirect('classroom/' . $course_slug)->with('error', 'Você não está matrículado no curso solicitado.');
        }

        // consult if discipline exist
        $discipline = $this->discipline->where('slug', $discipline_slug)->first();
        if (!$discipline) {
            return redirect('classroom/' . $course_slug)->with('error', 'Disciplina não encontrada.');
        }

        //const if student is registered in discipline
        $verify_inscription = $this->inscription->where('course_id', $course->id)->where('discipline_id', $discipline->id)->where('status','pago')->where('user_id', auth()->user()->id)->count();
        if (!$verify_inscription) {
            return redirect('classroom/' . $course_slug)->with('error', 'Você não está inscrito na disciplina solicitada.');
        }

        $multiplequestions = $this->multiplequestion->where('course_id', $course->id)->where('discipline_id', $discipline->id)->get();

        $access = $this->access->where('user_id', auth()->user()->id)->where('discipline_id', $discipline->id)->where('type','multiple')->count();
        if ($access == 4) {
            return redirect('classroom/' . $course_slug)->with('error', 'Seu limite de acesso a avaliação acabou.');
        }

        // insere o acesso do usuário a avaliação.
        $this->access->create([
            'user_id' => auth()->user()->id,
            'course_id' => $course->id,
            'discipline_id' => $discipline->id,
            'type' => 'multiple'
        ]);

        return view('multiplequestion',[ 
            'discipline' => $discipline,
            'course' => $course,
            'multiplequestions' => $multiplequestions
        ]);
    }

    /**
     * Store questions open
     */
    public function store_multipleresponse (Request $request) 
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;

        $count = count($data['multiple_question_id']);

        for($i = 0; $i < $count; $i++) {

            $this->multipleresponse->create([
                'user_id' => $data['user_id'],
                'course_id' => $data['course_id'],
                'discipline_id' => $data['discipline_id'],
                'multiple_question_id' => $data['multiple_question_id'][$i],
                'gabarito' => $data['gbt'][$i],
                'option' => $data['option'][$data['multiple_question_id'][$i]],
            ]);
        }

        return redirect('classroom/' . $data['slug'])->with('success', 'Resposta enviada com sucesso.');
    }

    public function result_multiple_question (string $course_slug, string $discipline_slug) 
    {
        // consult if course exist
        $course = $this->course->where('slug', $course_slug)->first();
        if (!$course) {
            return redirect('classroom/' . $course_slug)->with('error', 'Curso não encontrado.');
        }

        // consult if student is registered in course
        $verify_registrion = $this->registration->where('course_id', $course->id)->where('payment','yes')->where('user_id', auth()->user()->id)->count();
        if ($verify_registrion == 0) {
            return redirect('classroom/' . $course_slug)->with('error', 'Você não está matrículado no curso solicitado.');
        }

        // consult if discipline exist
        $discipline = $this->discipline->where('slug', $discipline_slug)->first();
        if (!$discipline) {
            return redirect('classroom/' . $course_slug)->with('error', 'Disciplina não encontrada.');
        }

        //const if student is registered in discipline
        $verify_inscription = $this->inscription->where('course_id', $course->id)->where('discipline_id', $discipline->id)->where('status','pago')->where('user_id', auth()->user()->id)->count();
        if (!$verify_inscription) {
            return redirect('classroom/' . $course_slug)->with('error', 'Você não está inscrito na disciplina solicitada.');
        }

        $resultmultiples = $this->multipleresponse->where('course_id', $course->id)->where('discipline_id', $discipline->id)->where('user_id', auth()->user()->id)->get();

        return view('result',[ 
            'discipline' => $discipline,
            'course' => $course,
            'resultmultiples' => $resultmultiples
        ]);
    }

    /**
     * get note avaliation multiple questions
     */
    public static function getNoteMultiple ($course, $discipline, $user) 
    {
        $data = DB::table('multiple_responses')
                    ->where('multiple_responses.course_id', $course)
                    ->where('multiple_responses.discipline_id', $discipline)
                    ->where('multiple_responses.user_id',$user)
                    ->join('multiple_questions', 'multiple_responses.multiple_question_id', '=', 'multiple_questions.id')
                    ->get();


        if ($data->count() == 0) {
            return 0;
        } else {
            $nota = 0;
            foreach ($data as $key => $response) {
                if ($response->option == $response->gabarito) {
                    $nota++;
                }
            }
            return $nota;
        }
    }

    /**
     * get note avaliation open questions
     */
    public static function getNoteOpen ($course, $discipline, $user) 
    {
        $data = DB::table('open_responses')
                    ->where('open_responses.course_id', $course)
                    ->where('open_responses.discipline_id', $discipline)
                    ->where('open_responses.user_id',$user)
                    ->join('open_questions', 'open_responses.open_question_id', '=', 'open_questions.id')
                    ->get();


        if ($data->count() == 0) {
            return 0;
        } else {
            $nota = 0;
            foreach ($data as $key => $response) {
                    $nota += $response->note;
            }
            return $nota;
        }
    }

    /*
     * get note job
     */
    public static function getNoteJob ($course, $discipline, $user) 
    {
        $data = DB::table('jobs')
                    ->where('course_id', $course)
                    ->where('discipline_id', $discipline)
                    ->where('user_id',$user)
                    ->first();

        if (!$data) {
            return 0;
        } else {
            return $data->note;
        }
    }

    /*
    * Storage data new registration student
    */
    public function store_registration (Request $request) 
    {
        $data = $request->all();

        $data['user_id'] = auth()->user()->id;
        $data['payment'] = 'no';

        if($this->registration->create($data)) {
            return redirect('dashboard')->with('success', 'Sua solicitação de matrícula do realizada com sucesso!');
        } else {
            return redirect('dashboard')->with('error', 'Erro ao solicitar sua matrícula!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_student (int $id)
    {
        if ($id == auth()->user()->id) 
        {
            $student = $this->user->find($id); // consulta a tabela de users
            if ($student) {
                return view('perfil', ['student' => $student]);
            } else {
                return redirect('dashboard')->with('error', 'Ocorreu um erro, aluno não encontrado.'); 
            }
        }
        
        return redirect('dashboard')->with('error', 'Ocorreu um erro, aluno não encontrado.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_student (Request $request, string $id)
    {
        $data = $request->all(); // recebe os dados do form.
        
        $id_login = auth()->user()->id; //get id user login

        $student = $this->user->find($id_login); // search table users the id_login

        if (!$student) {
            return redirect('admin/students')->with('alert', 'Registro não encontrado!');
        }

        if(!$data['password']):
            unset($data['password']);
        endif;

        Validator::make($data, [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:16',
            'email' => ['required','string','email','max:100',Rule::unique('users')->ignore($id_login)],
            'password' => 'sometimes|required|string|min:6',
            'image' => 'sometimes|required|mimes:jpg,jpeg,gif,png',
            'rg' => ['required','string','max:30',Rule::unique('users')->ignore($id_login)],
            'cpf' => ['required','string','max:15',Rule::unique('users')->ignore($id_login)],
            'zip_code' => 'required|string|max:9',
            'address' => 'required|string|max:250',
            'number' => 'required|string|max:5',
            'district' => 'required|string|max:50',
            'city' => 'required|string|max:50',
            'state' => 'required|string|max:2',
        ])->validate();

        if($request->password){
            $data['password'] =  bcrypt($request->password);
        }

        if($request->hasFile('image') && $request->file('image')->isValid()){
            if($student['image'] != null){
                if(Storage::exists($student['image'])) {
                    Storage::delete($student['image']);
                }
            }
            
            $new_file = $request->image->store('students','public');
            $data['image'] = $new_file;
        }

        if($student->update($data)) {
            return redirect('dashboard')->with('success', 'Registro alterado com sucesso!');
        } else {
            return redirect('dashboard')->with('error', 'Erro ao alterado o registro!');
        }
    }

    /**
     * list data forum
     */
    public function forum (string $course_lug, string $discipline_slug) 
    {
        // consult if course exist
        $course = $this->course->where('slug', $course_lug)->first();
        if (!$course) {
            return redirect('dashboard')->with('error', 'Curso não encontrado.');
        }

        // consult if student is registered in course
        $verify_registrion = $this->registration->where('course_id', $course->id)->where('payment','yes')->where('user_id', auth()->user()->id)->count();
        if ($verify_registrion == 0) {
            return redirect('dashboard')->with('error', 'Você não está matrículado no curso solicitado.');
        }

        // consult if discipline exist
        $discipline = $this->discipline->where('slug', $discipline_slug)->first();
        if (!$discipline) {
            return redirect('classroom/' . $course_slug)->with('error', 'Disciplina não encontrada.');
        }

        //const if student is registered in discipline
        $verify_inscription = $this->inscription->where('course_id', $course->id)->where('discipline_id', $discipline->id)->where('status','pago')->where('user_id', auth()->user()->id)->count();
        if (!$verify_inscription) {
            return redirect('classroom/' . $course_slug)->with('error', 'Você não está inscrito na disciplina solicitada.');
        }

        // verifica se o forum foi criado
        $forum = $this->forum->where('discipline_id', $discipline->id)->first();
        if (!$forum) {
            return redirect('classroom/' . $course->slug)->with('error', 'No momento o Forum não está disponível, aguarde!');
        }

        // consult mural by course
        $murals = $this->mural->where('discipline_id', $discipline->id)->orderBy('id','DESC')->get();

        $forumcomments = $this->forumcomment->where('forum_id', $forum->id)->orderBy('id','DESC')->get();
        $forumopnions = $this->forumopnion->orderBy('id','DESC')->get();

        $inscriptions = $this->inscription->where('discipline_id', $discipline->id)->where('status','pago')->get();

        if ($forum) {
            return view('forum',[
                'forum' => $forum, 
                'inscriptions' => $inscriptions, 
                'forumcomments' => $forumcomments, 
                'forumopnions' => $forumopnions,
                'discipline' => $discipline,
                'course' => $course,
                'murals' => $murals
            ]);
        }
    }

    /**
     * Request forum comment store
     */
     public function forum_comment (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'forum_id' => 'required',
            'comment' => 'required|string|min:2|max:5000',
        ])->validate();

        $data['user_id'] = auth()->user()->id;

        if($this->forumcomment->create($data)) {
            return redirect('/livingroom/forum/' . $data['course_slug'].'/'.$data['discipline_slug'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('/livingroom/forum/' . $data['course_slug'].'/'.$data['discipline_slug'])->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Request forum opnion store
     */
    public function forum_opnion (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'forum_comment_id' => 'required',
            'opnion' => 'required|string|min:1|max:1000',
        ])->validate();

        $data['user_id'] = auth()->user()->id;

        if($this->forumopnion->create($data)) {
            return redirect('/livingroom/forum/' . $data['course_slug'].'/'.$data['discipline_slug'])->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('/livingroom/forum/' . $data['course_slug'].'/'.$data['discipline_slug'])->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * Dashboard customer service student
     */
     public function customer_service () 
     {
        $user = auth()->user()->id;
        $student = $this->user->where('id', $user)->first();

        $customerservices = $this->customerservice->where('user_id', $user)->orderBy('id','DESC')->get();

        return view('customerservice',[
            'customerservices' => $customerservices,
            'student' => $student,
        ]);
     }

    /**
     * Create new customer service
    */

    public function create () 
    {
        return view('new_atendiment');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_atendiment (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'subject' => 'required|string',
            'description' => 'required|string',
        ])->validate();

        $data['user_id'] = auth()->user()->id;

        if($this->customerservice->create($data)) {
            return redirect('/classroom/customer/service')->with('success', 'Registro inserido com sucesso!');
        } else {
            return redirect('/classroom/customer/service')->with('error', 'Erro ao inserir o registro!');
        }
    }

    /**
     * store new create comment customer service atendiment
     */
    public function store_atendiment_comment (Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'comment' => 'required|string',
        ])->validate();
        
        $data['user_id'] = auth()->user()->id;
        $data['view_user'] = false;
        $data['view_student'] = true;

        if($this->customerservicecomment->create($data)) {

            // update view comment student
            $affected = DB::table('customer_service_comments')->where('customer_service_id', $data['customer_service_id'])->update(['view_user' => 0]);

            return redirect('/classroom/customer/service')->with('success', 'Comentário inserido com sucesso!');
        } else {
            return redirect('/classroom/customer/service')->with('error', 'Erro ao inserir o comentário!');
        }
    }
}
