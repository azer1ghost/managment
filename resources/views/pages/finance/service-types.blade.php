@extends('layouts.main')

@section('title', 'Xidmət Növləri')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Yeni Xidmət Əlavə Et</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('service-types.store') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="name" class="form-control" placeholder="Xidmətin adı" required maxlength="500">
                    <button type="submit" class="btn btn-success ml-2">Əlavə et</button>
                </form>
                @if($errors->has('name'))
                    <div class="text-danger mt-2">{{ $errors->first('name') }}</div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Xidmət Növləri ({{ $serviceTypes->count() }})</h4>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0" id="service-types-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Xidmətin Adı</th>
                            <th style="width: 100px;">Əməliyyat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($serviceTypes as $i => $type)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $type->name }}</td>
                                <td>
                                    <form action="{{ route('service-types.destroy', $type) }}" method="POST"
                                          onsubmit="return confirm('Silmək istədiyinizə əminsiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#service-types-table').DataTable({ "order": [[0, "asc"]], pageLength: 25 });
    </script>
@endsection
