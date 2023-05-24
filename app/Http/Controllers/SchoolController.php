<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;

class SchoolController extends Controller
{
    private $school;

    public function __construct(School $school) {
        $this->school = $school;
    }

    public function index() {
        $res = $this->school->index();
    }

    public function get($id) {
        $res = $this->school->get();
    }

    public function create() {

    }

    public function store() {
        $res = $this->school->store();
    }

    public function edit() {
        $res = $this->school->get();
    }

    public function update() {
        $res = $this->school->edit();
    }

    public function destroy(Request $request) {
        $res = $this->school->destroy();
    }
}
