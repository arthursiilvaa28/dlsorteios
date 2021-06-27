@extends('Layouts/site')
@section('css', asset('Css/contatos.css'))
@section('content')
    <section class="contact">
        <header class="header-contact ">
            <h1>Olá :) Olha como é fácil entrar em contato conosco...</h1>
            <h2>Para relatar problemas, duvidas, entre em contato aqui conosco !</h2>
        </header>
        <ul class="conteiner-contact-us">
            <li class="content-contact-us">
                <a href="https://api.whatsapp.com/send?phone=5585991102986&text=Olá, estou entrando em contato devido:" class="items-contact-us">
                    <img src="{{ asset('Imagens/whatsapp.png') }}" alt="WhatsApp">
                    <div>Nosso WhatsApp</div>
                </a>
            </li>
            <li class="content-contact-us">
                <a href="###" class="items-contact-us" class="items-contact-us">
                    <img src="{{ asset('Imagens/telegram.png') }}" alt="Telegram">
                    <div>Nosso Telegram</div>
                </a>
            </li>
        </ul>
    </section>
@endsection