<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Inquiry;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
use DOMDocument;
use function Livewire\str;

class GlobalSearchController extends Controller
{

    private DOMDocument $document;

    /**
     * @throws \ReflectionException
     */
    public function __invoke()
    {
        $this->document = new DOMDocument();

        collect((new \ReflectionClass($this))->getMethods())->each(function ($method) {
            if (str($method->name)->startsWith('handle')) {
                $method->invoke($this);
            }
        });

        return response()->json($this->document->saveHTML());
    }

    private function handleUser(): void
    {
        $users = User::query()
            ->withTrashed()
            ->where('name', 'like', '%' . request('query') . '%')
            ->when(is_numeric(request('query')), function ($query) {
                $query->orWhere('id', request('query'));
            })
            ->limit(4)
            ->get(['id', 'name', 'created_at', 'deleted_at']);

        if ($users->isNotEmpty()) {
            $this->createTitle('Users');

            $users->each(
                fn(User $users) => $this->createContext(
                    route('users.index', [
                        'search' => $users->name,
                    ]),
                    'fa fa-user mr-2',
                    $users->deleted_at ? "$users->name (deleted)" : $users->name,
                    Carbon::parse($users->created_at)->format('Y-m-d'),
                )
            );
        }
    }

    private function handleWorks(): void
    {
        $works = Work::query()
            ->with(['client', 'user'])
            ->when(is_numeric(request('query')), function ($query) {
                $query->where('id', request('query'));
            })
            ->orWhereHas('client', function ($query) {
                $query->where('fullname', 'LIKE', '%' . request('query') . '%');
            })
            ->orWhere('code', 'LIKE', '%' . request('query') . '%')
            ->limit(4)
            ->get(['id', 'user_id', 'created_at', 'client_id']);

        if ($works->isNotEmpty()) {
            $this->createTitle('Works');

            $works->each(
                fn(Work $work) => $this->createContext(

                    route('works.index', [
                        'client_id' => $work->client_id
                    ]),
                    'fa fa-file-chart-pie mr-2',
                    "{$work->client->fullname} ({$work->user->fullname})",
                    Carbon::parse($work->created_at)->format('Y-m-d'),
                )
            );
        }
    }

    private function handleClient(): void
    {
        $clients = Client::query()
            ->where('fullname', 'like', '%' . request('query') . '%')
            ->when(is_numeric(request('query')), function ($query) {
                $query->orWhere('id', request('query'));
            })
            ->limit(4)
            ->get(['id', 'fullname', 'created_at']);

        if ($clients->isNotEmpty()) {
            $this->createTitle('Clients');

            $clients->each(
                fn(Client $client) => $this->createContext(
                    route('clients.index', [
                        'search' => $client->fullname,
                    ]),
                    'fa fa-users mr-2',
                    $client->fullname,
                    Carbon::parse($client->created_at)->format('Y-m-d'),
                )
            );
        }
    }

    private function handleInquiries(): void
    {
        $inquiries = Inquiry::query()
            ->with(['client', 'user'])
            ->when(is_numeric(request('query')), function ($query) {
                $query->where('id', request('query'));
            })
            ->orWhereHas('client', function ($query) {
                $query->where('fullname', 'LIKE', '%' . request('query') . '%');
            })
            ->orWhereHas('user', function ($query) {
                $query->where('name', 'LIKE', '%' . request('query') . '%');
            })
            ->orWhere('code', 'LIKE', '%' . request('query') . '%')
            ->limit(4)
            ->get(['id', 'user_id', 'client_id', 'created_at']);

        if ($inquiries->isNotEmpty()) {
            $this->createTitle('Inquiries');
            $inquiries->each(
                fn(Inquiry $inquiry) => $this->createContext(
                    route('inquiry.index', ['code' => $inquiry->code]),
                    'fa fa-phone mr-2',
                    "{$inquiry->client->fullname} ({$inquiry->user->fullname})",
                    Carbon::parse($inquiry->created_at)->format('Y-m-d'),
                )
            );
        }
    }

    private function createTitle($title): void
    {
        $liHeader = $this->document->createElement('li');
        $h6 = $this->document->createElement('h6', $title);
        $h6->setAttribute('class', 'dropdown-header');
        $liHeader->appendChild($h6);
        $this->document->appendChild($liHeader);
    }

    private function createContext($url, $icon, $name, $date): void
    {
        $li = $this->document->createElement('li');
        $a = $this->document->createElement('a');
        $i = $this->document->createElement('i');
        $i->setAttribute('class', $icon);
        $a->appendChild($i);
        $textNode = $this->document->createTextNode($name);
        $a->appendChild($textNode);
        $a->setAttribute('href', $url);
        $a->setAttribute('class', 'dropdown-item list-content');
        $span = $this->document->createElement('span', $date);
        $span->setAttribute('class', 'float-right date');
        $a->appendChild($span);
        $li->appendChild($a);
        $this->document->appendChild($li);
    }
}
