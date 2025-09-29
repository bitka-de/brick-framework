@extends('app')

@section('title', $title)

@section('content')
    <div class="container">
        <h1>🧱 Brick Framework Template Demo</h1>
        <p class="lead">Diese Seite demonstriert die wichtigsten Template-Features des Brick Frameworks.</p>

        @if($showAlert)
            <div class="alert alert-info">
                <strong>Info:</strong> {{ $alertMessage }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <h3>👥 Benutzer</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>E-Mail</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    @if($user['active'])
                                        <span class="badge bg-success">Aktiv</span>
                                    @else
                                        <span class="badge bg-secondary">Inaktiv</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h3>📦 Produkte</h3>
                @foreach($products as $product)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product['name'] }}</h5>
                            <p class="card-text">
                                <strong>Preis:</strong> €{{ number_format($product['price'], 2) }}<br>
                                <strong>Lagerbestand:</strong> {{ $product['stock'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5>🧱 Framework Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Framework:</strong> Brick Framework v1.0.0</p>
                <p><strong>PHP Version:</strong> {{ phpversion() }}</p>
                <div class="mt-3">
                    <a href="/debug/routes" class="btn btn-outline-info btn-sm">Route Debug</a>
                    <a href="/api/stats" class="btn btn-outline-success btn-sm">API Stats</a>
                    <a href="/" class="btn btn-outline-primary btn-sm">Zur Startseite</a>
                </div>
            </div>
        </div>
    </div>
@endsection