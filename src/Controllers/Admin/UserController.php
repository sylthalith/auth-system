<?php

namespace App\Controllers\Admin;

use App\Builders\FilterBuilder;
use App\Enums\FilterOperator;
use App\Pagination\Paginator;
use App\Repositories\UserRepository;
use App\Request;

class UserController
{
    public function __construct(
        private Request $request,
        private UserRepository $users,
    ) {}

    public function index() {
        $page = $this->request->getQueryInt('page', 0);

        $filters = array_filter([
            'name' => $this->request->getQueryString('name'),
            'is_admin' => $this->request->getQueryInt('is_admin'),
            'is_blocked' => $this->request->getQueryInt('is_blocked'),
        ], function ($value) {
            return $value !== null;
        });

        $paginator = new Paginator($this->users, 5, $page, 3, $filters);

        template('admin/users', ['paginator' => $paginator]);
    }

    public function show($id) {
        dd($id);
    }
}