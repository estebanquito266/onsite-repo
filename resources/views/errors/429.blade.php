@extends('errors.minimal')

@section('title', __('Demasiadas solicitudes'))
@section('code', '429')
@section('message', __($exception->getMessage() ?: 'Demasiadas solicitudes'))
