@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Create Character</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action="/owners/create">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="character-name" class="col-sm-2 col-form-label">Character Name</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="character-name" name="character-name" value="{{ Game::getRandomCharacterName() }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company-name" class="col-sm-2 col-form-label">Company</label>
                            <div class="col-sm-10">
                              <input type="text" class="form-control" id="company-name" name="company-name" value="{{ Game::getRandomCompanyName() }}">
                            </div>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-success">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
