<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    /**
     * Template 1: Dark Cinematic Hero with Auto-Slider (default)
     */
    public function template1()
    {
        return view('welcome.1');
    }

    /**
     * Template 2: Light Premium Championship Branding
     */
    public function template2()
    {
        return view('welcome.2');
    }

    /**
     * Template 3: Bold Split-Screen with Rundown Section
     */
    public function template3()
    {
        return view('welcome.3');
    }

    /**
     * Template 4: Glassmorphism / Gradient Blurred Hero
     */
    public function template4()
    {
        return view('welcome.4');
    }

    /**
     * Template 5: Minimal & Elegant Championship Invitation
     */
    public function template5()
    {
        return view('welcome.5');
    }
}
