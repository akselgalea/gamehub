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

    public function findByLink($link) {
        return $this->entry->firstWhere('link', $link);
    }

    public function notFoundText() {
        return ['status' => 404, 'message' => 'Entrypoint no encontrado'];
    }

    public function store($req) {
        
        $validated = $req->validated();
        try {
            $entrypoint = EntryPoint::create($validated);
            $this->setLink($entrypoint);            
            return ['status' => 200, 'message' => 'Entrypoint creado con éxito!'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function update($id, $req) {
        $validated = $req->validated();

        try {
            $entry = $this->entry->findOrFail($id);
            $entry->update($validated);
            $this->setLink($entry);

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

    public function setLink($entry): void {
        $entry->link = $entry->obfuscated ? $this->encrypt->encrypt($entry->slug) : $entry->slug;
        $entry->save();
    }
}
