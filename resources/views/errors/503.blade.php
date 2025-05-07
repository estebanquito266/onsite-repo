@extends('errors.minimal')

@section('title', __('Servicio no Disponible'))
@section('code', '503')
@section('message', __($exception->getMessage() ?: 'Servicio no Disponible'))
