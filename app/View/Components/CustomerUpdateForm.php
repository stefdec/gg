<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CustomerUpdateForm extends Component
{
    public $customerId;
    public $departureDate;
    public $lastName;
    public $firstName;
    public $email;
    public $phone;
    public $country;
    public $notes;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($customerId, $departureDate, $lastName, $firstName, $email, $phone, $country, $notes)
    {
        // $customerId, $departureDate, $lastName, $firstName, $email, $phone, $country, $notes
        $this->customerId = $customerId;
        $this->departureDate = $departureDate;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->email = $email;
        $this->phone = $phone;
        $this->country = $country;
        $this->notes = $notes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.customer-update-form');
    }
}
