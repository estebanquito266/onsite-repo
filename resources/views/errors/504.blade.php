@extends('errors.minimal')

@section('title', __('Tiempo de espera agotado'))
@section('code', '504')
@section('message', __($exception->getMessage() ?: 'Tiempo de espera agotado'))