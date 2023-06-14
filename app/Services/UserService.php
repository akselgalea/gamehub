<?php
namespace App\Services;

use App\Models\{User, Student};
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;


class UserService
{

    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    function index() {
        return $this->user->all();
    }

    function get($slug) {
        $user = $this->user->findBySlug($slug);
        
        if(empty($user))
            return ['status' => 404, 'message' => 'No se ha encontrado el usuario'];

        return ['user' => $user];
    }

    public function store($req) {

        $validated = $req->validated();

        try {
            $validated['password'] = Hash::make($validated['password']); // se cifra la contraseña
            $user = User::create($validated);

            return ['status' => 200, 'message' => 'Experimento creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function update($req) {

        $validated = $req->validated();

        try {
            $this->update($validated);
            return ['status' => 200, 'message' => 'Datos del experimento actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function storeBatch($request){
        
        if ($request->hasFile('user_file')) {
            
            try {
                Excel::import(new UsersImport, request()->file('user_file'));

                return ['status' => 200, 'message' => 'Usuarios cargados exitosamente.'];
            } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

                $failures = $e->failures();
                return ['status' => 500, 'message' => $e->getMessage()];
            }

            return redirect(route('users.index'));
        }else{

            return ['status' => 500, 'message' => 'No se pudo cargar el archivo csv.'];

        }
    }
}