@extends('layouts.admin')

@section('admin-content')

<div class="admin-profile-settings">
    <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}

                                <button type="button"
                                        class="btn-close"
                                        data-bs-dismiss="alert"
                                        aria-label="Close">
                                </button>
                            </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Veuillez corriger les erreurs suivantes :</strong>

                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
    </div>
    <div class="row g-4">
        <!-- Profile -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header border-bottom kama-card-header">
                    <h5 class="mb-0 d-flex align-items-center gap-2">
                        <span class="section-icon profile">
                            <i class="bi bi-person-vcard"></i>
                        </span>
                       Modification des coordonnées {{ $user->firstname }} {{ $user->lastname }}
                    </h5>
                </div>


                <form class="row g-3" method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- firstname -->
                            <div class="col-md-4">

                                <label class="form-label">
                                    <i class="bi bi-person text-danger me-1"></i>
                                    Prénom
                                </label>

                                <input type="text" class="form-control" name="firstname" value="{{ $user->firstname }}">
                            </div>

                            <!-- lastname -->

                            <div class="col-md-4">

                                <label class="form-label">
                                    <i class="bi bi-person-lines-fill text-danger me-1"></i>
                                    Nom
                                </label>

                                <input type="text" class="form-control" name="lastname" value="{{ $user->lastname }}">

                            </div>

                            <!-- Email -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-envelope-fill text-primary me-1"></i>
                                    Email
                                </label>
                                <input type="email" class="form-control" name="email" value="{{ $user->email }}">
                            </div>

                            <!-- Phone -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-telephone-fill text-success me-1"></i>
                                    Téléphone
                                </label>
                                <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
                            </div>

                            <!-- Country -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-globe text-warning me-1"></i>
                                    Pays
                                </label>

                                <input type="text" class="form-control" name="country" value="{{ $user->country }}">
                            </div>

                            <!-- Gender -->
                            <div class="col-md-4">
                                <label class="form-label">
                                    <i class="bi bi-gender-ambiguous text-info me-1"></i>
                                    Genre
                                </label>

                                <div>

                                    <input type="radio"
                                        class="btn-check"
                                        name="gender"
                                        id="male"
                                        value="male"
                                        {{ $user->gender=='male'?'checked':'' }}>

                                    <label class="btn btn-outline-danger rounded-start"
                                        for="male">
                                        Homme
                                    </label>

                                    <input type="radio"
                                        class="btn-check"
                                        name="gender"
                                        id="female"
                                        value="female"
                                        {{ $user->gender=='female'?'checked':'' }}>

                                    <label class="btn btn-outline-danger"
                                        for="female">
                                        Femme
                                    </label>

                                    <input type="radio"
                                        class="btn-check"
                                        name="gender"
                                        id="other"
                                        value="other"
                                        {{ $user->gender=='other'?'checked':'' }}>

                                    <label class="btn btn-outline-danger rounded-end"
                                        for="other">
                                        Autre
                                    </label>
                                </div>
                            </div>

                            @if ($user->role->name == 'writer' || $user->role->name == 'admin' )
                                <!-- Bio -->
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-chat-square-text-fill text-primary me-1"></i>
                                        Biographie
                                    </label>

                                    <textarea class="form-control" name="bio"
                                            rows="4"
                                            placeholder="Parlez-nous de vous...">{{ old('bio', $user->bio) }}</textarea>
                                </div>
                            @endif
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn kama-btn" type="submit">
                                <i class="bi bi-check-circle me-2"></i>
                                Modifier le profil
                            </button>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>

    </div>


</div>
@endsection