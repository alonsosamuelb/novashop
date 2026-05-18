<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

class PageController extends Controller
{
    public function about(): void
    {
        $this->view('pages.about', ['title' => 'Quienes somos']);
    }

    public function contact(): void
    {
        $this->view('pages.contact', ['title' => 'Contacto']);
    }

    public function shipping(): void
    {
        $this->view('pages.shipping', ['title' => 'Envio']);
    }

    public function terms(): void
    {
        $this->view('pages.terms', ['title' => 'Condiciones generales']);
    }

    public function returns(): void
    {
        $this->view('pages.returns', ['title' => 'Politica de devoluciones']);
    }
}
