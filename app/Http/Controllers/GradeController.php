<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;

class GradeController extends Controller
{
    private $grade;

    public function __construct(Grade $grade) {
        $this->grade = $grade;
    }

    public function index() {
        $res = $this->grade->index();
    }

    public function get($id) {
        $res = $this->grade->get();
    }

    public function create() {

    }

    public function store() {
        $res = $this->grade->store();
    }

    public function edit() {
        $res = $this->grade->get();
    }

    public function update() {
        $res = $this->grade->edit();
    }

    public function destroy(Request $request) {
        $res = $this->grade->destroy();
    }
}
