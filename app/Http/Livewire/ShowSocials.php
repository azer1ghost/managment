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

    public function mount()
    {
        $this->socials = $this->company->socials()->get(['id','name','url'])->toArray();
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
