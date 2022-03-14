<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StaffUpdateForm extends Component
{
    public $staffId;
    public $startDate;
    public $staffName;
    public $email;
    public $password;
    public $phone;
    public $description;
    public $salary;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($staffId, $startDate, $staffName, $email, $password, $phone, $description, $salary)
    {
        $this->staffId = $staffId;
        $this->startDate = $startDate;
        $this->staffName = $staffName;
        $this->email = $email;
        $this->password = $password;
        $this->phone = $phone;
        $this->description = $description;
        $this->salary = $salary;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.staff-update-form');
    }
}
