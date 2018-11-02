@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                      <pre>
                          {{print_r($data,null)}}
                      </pre>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
