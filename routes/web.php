<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PoloController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\DisciplineModuleController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PreregistrationController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\MuralController;
use App\Http\Controllers\CustomerServiceController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\MultipleQuestionController;
use App\Http\Controllers\OpenQuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [SiteController::class, 'index'])->name('site');
Route::get('/about', [SiteController::class, 'about'])->name('about');
Route::get('/access', [SiteController::class, 'access'])->name('access');
Route::get('/course/{slug}', [SiteController::class, 'show'])->name('course.show');
Route::post('/course/preregistration', [SiteController::class, 'pre_registration'])->name('course.preregistration');

Route::get('/show', function () {
    return view('show');
});

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/classroom/{slug}', [DashboardController::class, 'classroom'])->middleware(['auth', 'verified'])->name('classroom');
Route::get('/classroom/customer/service', [DashboardController::class, 'customer_service'])->middleware(['auth', 'verified'])->name('classroom.customer.service');
Route::get('/classroom/new/customer/service', [DashboardController::class, 'create'])->middleware(['auth', 'verified'])->name('classroom.new.customer.service');
Route::post('/classroom/store/atendiment', [DashboardController::class, 'store_atendiment'])->middleware(['auth', 'verified'])->name('classroom.store.atendiment');
Route::post('/classroom/atendiment/comment/store', [DashboardController::class, 'store_atendiment_comment'])->middleware(['auth', 'verified'])->name('classroom.atendiment.comment.store');
Route::get('/livingroom/{course}/{discipline}', [DashboardController::class, 'livingroom'])->middleware(['auth', 'verified'])->name('livingroom');
Route::get('/livingroom/discipline/module/{id}', [DashboardController::class, 'module'])->middleware(['auth', 'verified'])->name('livingroom.discipline.module');
Route::post('/classroom/storejob', [DashboardController::class, 'store_job'])->middleware(['auth', 'verified'])->name('classroom.storejob');

Route::get('/dashboar/classroom/historic/pdf/{id}', [DashboardController::class, 'pdf'])->middleware(['auth:sanctum', 'verified'])->name('dashboard.classroom.historic.pdf');

Route::post('/livingroom/start/directchat', [DashboardController::class, 'start_chat'])->name('livingroom.start.directchat');
Route::post('/livingroom/directchatmessage', [DashboardController::class, 'direct_chat_message'])->name('livingroom.directchatmessage');
Route::get('/livingroom/char/message/{id}', [DashboardController::class, 'chat_messages'])->name('livingroom.message.chat');

Route::post('/dashboard/registration', [DashboardController::class, 'store_registration'])->middleware(['auth', 'verified'])->name('dashboard.store.registration');
Route::get('/dashboard/student/{id}', [DashboardController::class, 'edit_student'])->middleware(['auth:sanctum', 'verified'])->name('dashboard.student.edit');
Route::put('/dashboard/student/update/{id}', [DashboardController::class, 'update_student'])->middleware(['auth:sanctum', 'verified'])->name('dashboard.student.update');
Route::get('/livingroom/forum/{course}/{discipline}', [DashboardController::class, 'forum'])->middleware(['auth', 'verified'])->name('livingroom.discipline.forum');
Route::post('/livingroom/forum/comment', [DashboardController::class, 'forum_comment'])->middleware(['auth', 'verified'])->name('livingroom.forum.comment');
Route::post('/livingroom/forum/opnion', [DashboardController::class, 'forum_opnion'])->middleware(['auth', 'verified'])->name('livingroom.forum.opnion');

Route::get('/dashboard/evaluation/{course}/{discipline}', [DashboardController::class, 'evaluation'])->middleware(['auth', 'verified'])->name('dashboard.evaluation');

Route::get('/classroom/openquestion/{course}/{discipline}', [DashboardController::class, 'openquestion'])->middleware(['auth', 'verified'])->name('classroom.openquestion');
Route::post('/classroom/store/openresponse', [DashboardController::class, 'store_openresponse'])->middleware(['auth', 'verified'])->name('classroom.openresponse');

Route::get('/classroom/livingroom/multiplequestion/{course}/{discipline}', [DashboardController::class, 'multiple'])->middleware(['auth', 'verified'])->name('classroom.livingroom.multiplequestion');
Route::post('/classroom/store/multiplequestion', [DashboardController::class, 'store_multipleresponse'])->middleware(['auth', 'verified'])->name('classroom.store.multiplequestion');
Route::get('/classroom/livingroom/result/multiplequestion/{course}/{discipline}', [DashboardController::class, 'result_multiple_question'])->middleware(['auth', 'verified'])->name('classroom.multiplequestion.result');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//USERS
Route::get('/admin/users', [UserController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.users.index');
Route::get('/admin/user/create', [UserController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.user.create');
Route::post('/admin/user/store', [UserController::class, 'store'])->name('admin.user.store');
Route::get('/admin/user/edit/{id}', [UserController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.user.edit');
Route::put('/admin/user/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
Route::get('/admin/user/destroy/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');

// STUDENT
Route::get('/admin/students', [StudentController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.students.index');

Route::get('/admin/students/historic/pdf/{id}/{std}', [StudentController::class, 'pdf'])->middleware(['auth:sanctum', 'verified'])->name('admin.students.historic.pdf');

Route::get('/admin/student/show/{id}', [StudentController::class, 'show'])->middleware(['auth:sanctum', 'verified'])->name('admin.student.show');
Route::get('/admin/student/historic/{slug}/{id}', [StudentController::class, 'historic'])->middleware(['auth:sanctum', 'verified'])->name('admin.student.historic');
Route::get('/admin/student/historic/ead/{course}/{discipline}/{user}', [StudentController::class, 'ead'])->middleware(['auth:sanctum', 'verified'])->name('admin.student.ead');
Route::get('/admin/student/create', [StudentController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.student.create');
Route::post('/admin/student/store', [StudentController::class, 'store'])->name('admin.student.store');

Route::post('/admin/student/historic/store/note', [StudentController::class, 'note'])->name('admin.student.historic.store.note');

Route::get('/admin/student/edit/{id}', [StudentController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.student.edit');
Route::put('/admin/student/update/{id}', [StudentController::class, 'update'])->name('admin.student.update');
Route::get('/admin/student/destroy/{id}', [StudentController::class, 'destroy'])->name('admin.student.destroy');
Route::put('/admin/student/close/chat/{id}', [StudentController::class, 'close_chat'])->name('admin.student.close.chat');

Route::put('/admin/student/update/note/open/{id}', [StudentController::class, 'update_opennote'])->name('admin.evaluation.opennote.update');
Route::put('/admin/student/update/work/academic/{id}', [StudentController::class, 'update_workacademic'])->name('admin.dashboard.update.workacademic');

Route::delete('/admin/student/delete/multiple/questions', [StudentController::class, 'destroy_multipleresponse'])->name('admin.dashboard.destroy.multipleresponse');
Route::delete('/admin/student/delete/open/questions', [StudentController::class, 'destroy_openresponse'])->name('admin.dashboard.destroy.openresponse');
Route::delete('/admin/student/destroy/job/{id}', [StudentController::class, 'destroy_job'])->name('admin.dashboard.destroy.job');

Route::post('/student/start/directchat', [StudentController::class, 'start_chat'])->name('student.start.directchat');
Route::post('/student/directchatmessage', [StudentController::class, 'direct_chat_message'])->name('student.directchatmessage');
Route::get('/student/char/message/{id}', [StudentController::class, 'chat_messages'])->name('student.message.chat');

//POLOS
Route::get('/admin/polos', [PoloController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.polos.index');
Route::get('/admin/polo/create', [PoloController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.polo.create');
Route::post('/admin/polo/store', [PoloController::class, 'store'])->name('admin.polo.store');
Route::get('/admin/polo/edit/{id}', [PoloController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.polo.edit');
Route::put('/admin/polo/update/{id}', [PoloController::class, 'update'])->name('admin.polo.update');
Route::get('/admin/polo/destroy/{id}', [PoloController::class, 'destroy'])->name('admin.polo.destroy');

//COURSES
Route::get('/admin/courses', [CourseController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.courses.index');
Route::get('/admin/course/create', [CourseController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.course.create');
Route::post('/admin/course/store', [CourseController::class, 'store'])->name('admin.course.store');
Route::get('/admin/course/edit/{id}', [CourseController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.course.edit');
Route::put('/admin/course/update/{id}', [CourseController::class, 'update'])->name('admin.course.update');
Route::get('/admin/course/destroy/{id}', [CourseController::class, 'destroy'])->name('admin.course.destroy');

//DISCIPLINES
Route::get('/admin/disciplines', [DisciplineController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.disciplines.index');
Route::get('/admin/discipline/show/{id}', [DisciplineController::class, 'show'])->middleware(['auth:sanctum', 'verified'])->name('admin.discipline.show');
Route::get('/admin/discipline/create', [DisciplineController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.discipline.create');
Route::post('/admin/discipline/store', [DisciplineController::class, 'store'])->name('admin.discipline.store');
Route::get('/admin/discipline/edit/{id}', [DisciplineController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.discipline.edit');
Route::put('/admin/discipline/update/{id}', [DisciplineController::class, 'update'])->name('admin.discipline.update');
Route::get('/admin/discipline/destroy/{id}', [DisciplineController::class, 'destroy'])->name('admin.discipline.destroy');

//DISCIPLINE MODULE
Route::get('/admin/modules/create/{id}', [DisciplineModuleController::class, 'create'])->name('admin.module.create');
Route::get('/admin/modules/create/movie/{id}', [DisciplineModuleController::class, 'create_movie'])->name('admin.module.create.movie');
Route::get('/admin/modules/create/file/{id}', [DisciplineModuleController::class, 'create_file'])->name('admin.module.create.file');
Route::post('/admin/module/store', [DisciplineModuleController::class, 'store'])->name('admin.module.store');
Route::post('/admin/module/store/movie', [DisciplineModuleController::class, 'store_movie'])->name('admin.module.store.movie');
Route::post('/admin/module/store/file', [DisciplineModuleController::class, 'store_file'])->name('admin.module.store.file');
Route::get('/admin/module/edit/{id}', [DisciplineModuleController::class, 'edit'])->name('admin.module.edit');
Route::put('/admin/module/update/{id}', [DisciplineModuleController::class, 'update'])->name('admin.module.update');
Route::get('/admin/module/destroy/{id}', [DisciplineModuleController::class, 'destroy'])->name('admin.module.destroy');

// REGISTRATION
Route::get('/admin/registrations', [RegistrationController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.registrations.index');
Route::get('/admin/registration/create', [RegistrationController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.registration.create');
Route::post('/admin/registration/store', [RegistrationController::class, 'store'])->name('admin.registration.store');
Route::get('/admin/registration/edit/{id}', [RegistrationController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.registration.edit');
Route::put('/admin/registration/update/{id}', [RegistrationController::class, 'update'])->name('admin.registration.update');
Route::get('/admin/registration/destroy/{id}', [RegistrationController::class, 'destroy'])->name('admin.registration.destroy');

//INSCRIÇÕES
Route::get('/admin/inscriptions', [InscriptionController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.inscriptions.index');
Route::get('/admin/inscription/create', [InscriptionController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.inscription.create');
Route::get('/admin/inscription/list/{id}', [InscriptionController::class, 'list'])->name('admin.inscriptions.list');
Route::post('/admin/inscription/store', [InscriptionController::class, 'store'])->name('admin.inscription.store');
Route::get('/admin/inscription/edit/{id}', [InscriptionController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.inscription.edit');
Route::put('/admin/inscription/update/{id}', [InscriptionController::class, 'update'])->name('admin.inscription.update');
Route::get('/admin/inscription/destroy/{id}', [InscriptionController::class, 'destroy'])->name('admin.inscription.destroy');

//PRÉ REGISTRATIONS
Route::get('/admin/preregistrations', [PreregistrationController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.preregistrations.index');
Route::get('/admin/preregistration/edit/{id}', [PreregistrationController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.preregistration.edit');
Route::put('/admin/preregistration/update/{id}', [PreregistrationController::class, 'update'])->name('admin.preregistration.update');
Route::get('/admin/preregistration/destroy/{id}', [PreregistrationController::class, 'destroy'])->name('admin.preregistration.delete');

// MURAL
Route::get('/admin/murals', [MuralController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.murals.index');
Route::get('/admin/mural/create', [MuralController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.mural.create');
Route::get('/admin/murals/list/{id}', [MuralController::class, 'list'])->name('admin.murals.list');
Route::post('/admin/mural/store', [MuralController::class, 'store'])->name('admin.mural.store');
Route::get('/admin/mural/edit/{id}', [MuralController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.mural.edit');
Route::put('/admin/mural/update/{id}', [MuralController::class, 'update'])->name('admin.mural.update');
Route::get('/admin/mural/destroy/{id}', [MuralController::class, 'destroy'])->name('admin.mural.destroy');

// CUSROMER SERVICE
Route::get('/admin/customerservices', [CustomerServiceController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.customerservices.index');
Route::get('/admin/customerservice/create', [CustomerServiceController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.customerservice.create');
Route::post('/admin/customerservice/store', [CustomerServiceController::class, 'store'])->name('admin.customerservice.store');
Route::get('/admin/customerservice/edit/{id}', [CustomerServiceController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.customerservice.edit');
Route::get('/admin/customerservice/show/{id}', [CustomerServiceController::class, 'show'])->middleware(['auth:sanctum', 'verified'])->name('admin.customerservice.show');
Route::put('/admin/customerservice/update/{id}', [CustomerServiceController::class, 'update'])->name('admin.customerservice.update');
Route::get('/admin/customerservice/destroy/{id}', [CustomerServiceController::class, 'destroy'])->name('admin.customerservice.destroy');
Route::post('/admin/customerservice/comment', [CustomerServiceController::class, 'store_comment'])->name('admin.customerservice.comment');

// FORUM
Route::get('/admin/forums', [ForumController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.forums.index');
Route::get('/admin/forum/create', [ForumController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.forum.create');
Route::get('/admin/forum/show/{id}', [ForumController::class, 'show'])->middleware(['auth:sanctum', 'verified'])->name('admin.forum.show');
Route::get('/admin/forums/list/{id}', [ForumController::class, 'list'])->name('admin.forums.list');
Route::post('/admin/forum/store', [ForumController::class, 'store'])->name('admin.forum.store');
Route::post('/admin/forum/comments', [ForumController::class, 'comments'])->name('admin.forum.comments');
Route::post('/admin/forum/opinions', [ForumController::class, 'opinions'])->name('admin.forum.opinions');
Route::get('/admin/forum/edit/{id}', [ForumController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.forum.edit');
Route::put('/admin/forum/update/{id}', [ForumController::class, 'update'])->name('admin.forum.update');
Route::get('/admin/forum/destroy/{id}', [ForumController::class, 'destroy'])->name('admin.forum.destroy');

// MULTIPLE QUESTIONS
Route::get('/admin/multiplequestions', [MultipleQuestionController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.multiplequestions.index');
Route::get('/admin/multiplequestion/create', [MultipleQuestionController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.multiplequestion.create');
Route::get('/admin/multiplequestions/list/{id}', [MultipleQuestionController::class, 'list'])->name('admin.multiplequestions.list');
Route::post('/admin/multiplequestion/store', [MultipleQuestionController::class, 'store'])->name('admin.multiplequestion.store');
Route::get('/admin/multiplequestion/edit/{id}', [MultipleQuestionController::class, 'edit'])->name('admin.multiplequestion.edit');
Route::put('/admin/multiplequestion/update/{id}', [MultipleQuestionController::class, 'update'])->middleware(['auth:sanctum', 'verified'])->name('admin.multiplequestion.update');
Route::get('/admin/multiplequestion/destroy/{id}', [MultipleQuestionController::class, 'destroy'])->name('admin.multiplequestion.destroy');

// OPEN QUESTIONS
Route::get('/admin/openquestions', [OpenQuestionController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.openquestions.index');
Route::get('/admin/openquestion/create', [OpenQuestionController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.openquestion.create');
Route::get('/admin/openquestions/list/{id}', [OpenQuestionController::class, 'list'])->name('admin.openquestions.list');
Route::post('/admin/openquestion/store', [OpenQuestionController::class, 'store'])->name('admin.openquestion.store');
Route::get('/admin/openquestion/edit/{id}', [OpenQuestionController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.openquestion.edit');
Route::put('/admin/openquestion/update/{id}', [OpenQuestionController::class, 'update'])->name('admin.openquestion.update');
Route::get('/admin/openquestion/destroy/{id}', [OpenQuestionController::class, 'destroy'])->name('admin.openquestion.destroy');

//TEACHRES
Route::get('/admin/teachers', [TeacherController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.teachers.index');
Route::get('/admin/teacher/create', [TeacherController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.teacher.create');
Route::post('/admin/teacher/store', [TeacherController::class, 'store'])->name('admin.teacher.store');
Route::get('/admin/teacher/edit/{id}', [TeacherController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.teacher.edit');
Route::put('/admin/teacher/update/{id}', [TeacherController::class, 'update'])->name('admin.teacher.update');
Route::get('/admin/teacher/destroy/{id}', [TeacherController::class, 'destroy'])->name('admin.teacher.destroy');

//POLOS
Route::get('/admin/directchats', [DirectChatController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.directchats.index');
Route::get('/admin/directchat/destroy/{id}', [DirectChatController::class, 'destroy'])->name('admin.directchat.destroy');

//CONTRACT
Route::get('/admin/contracts', [ContractController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('admin.contracts.index');
Route::get('/admin/contracts/create', [ContractController::class, 'create'])->middleware(['auth:sanctum', 'verified'])->name('admin.contracts.create');
Route::post('/admin/contracts/store', [ContractController::class, 'store'])->name('admin.contracts.store');
Route::get('/admin/contracts/edit/{id}', [ContractController::class, 'edit'])->middleware(['auth:sanctum', 'verified'])->name('admin.contracts.edit');
Route::put('/admin/contracts/update/{id}', [ContractController::class, 'update'])->name('admin.contracts.update');
Route::get('/admin/contracts/destroy/{id}', [ContractController::class, 'destroy'])->name('admin.contracts.destroy');

