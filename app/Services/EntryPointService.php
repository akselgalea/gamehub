<?php

namespace App\Services;

use Exception;
use App\Models\{ EntryPoint };
use App\Services\{ EncryptService };

class EntryPointService
{
    private $entry;
    private $encrypt;

    public function __construct(EntryPoint $entry, EncryptService $encrypt) {
        $this->entry = $entry;
        $this->encrypt = $encrypt;
    }

    public function get($id) {
        return $this->entry->find($id);
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Entrypoint no encontrado'];
    }

    public function store($req) {
        
        $validated = $req->validated();
        try {
            $entrypoint = EntryPoint::create($validated);

            return ['status' => 200, 'message' => 'Entrypoint creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function update($id, $req) {

        $validated = $req->validated();

        try {
            $this->entry->findOrFail($id)->update($validated);
            return ['status' => 200, 'message' => 'Datos del entrypoint actualizado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function erase($req) {

        try {

            $entrypoint = EntryPoint::findOrFail($req->id);
            $entrypoint->delete();

            return ['status' => 200, 'message' => 'Entrypoint eliminado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => 'Ha ocurrido un error al eliminar el entrypoint.'];
        }
    }
}
