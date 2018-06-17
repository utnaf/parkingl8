@extends('errors::layout')

@section('title', 'Not Authorized')

@section('message', $exception->getMessage())
