<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShowSocials extends Component
{
    public object $company;
    public array  $socials;
    public ?string $action;

    public array $socialNetworks = [
        'facebook'  => 'Facebook',
        'instagram' => 'Instagram',
        'linkedin'  => 'Linkedin',
        'youtube'   => 'Youtube',
        'twitter'   => 'Twitter'
    ];

    public function mount($company)
    {
        $this->socials = $company ? $this->company->socials()->select('id','name','url')->get()->toArray() : null;
    }

    public function update()
    {
        $this->emit('updated');
    }

    public function addSocial()
    {
        $newArr = ["id" => null, "name" => null, "url" => null];
        $this->socials[] = $newArr;
    }

    public function removeSocial($index)
    {
        unset($this->socials[$index]);
    }

    public function render()
    {
        return view('panel.pages.companies.components.show-socials');
    }
}
