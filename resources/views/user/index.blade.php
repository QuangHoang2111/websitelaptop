@extends('layouts.app')
@section('content')
<main class ="pt-90">
    <div class = "mb-4 pb-4"></div>
    <section class = "my-account container mb-4">
        <h2 class ="page-title mb-4"> Tài khoản của tôi </h2>
        <div class = "row">
            <div class = "col-lg-3">
                @include("user.account-nav")
            </div>
            <div class = "col-lg-9"> 
                <div class = "page-content my-account_dashboard">
                    <p> Xin chào </p>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

