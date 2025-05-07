@extends('errors.minimal')

@section('title', __('Página no encontrada'))
@section('code', '404')
@section('message', __($exception->getMessage() ?: 'Error del Servidor'))
