@extends('errors.minimal')

@section('title', __('Error de sistema'))
@section('code', ($code ?? '404'))
@section('message', __($message ?: 'Página no encontrada'))