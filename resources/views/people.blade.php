@extends('layouts.app')

@section('content')
<style>
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.title {
  color: grey;
  font-size: 18px;
}

button {
    margin: 0;
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}

a {
  text-decoration: none;
  font-size: 22px;
  color: black;
}

button:hover, a:hover {
  opacity: 0.7;
}
</style>
<h2>File #{{ \App\Helper\PeopleViewHelper::getId($main['person']) }}: {{ $main['person']->name }}</h2>
<div class="card">
  <img  src="{{ $main['imageUrl'] }}" style="width:100%">
  <h1>{{ $main['person']->name }}</h1>
  <p>Hair color: {{$main['person']->hair_color}}</p>
  <p>Skin color: {{$main['person']->skin_color}}</p>
  <p class="title">BMI : {{$main['bmi']}}</p>
 
  <a href="/people/{{ \App\Helper\PeopleViewHelper::getId($main['person']) }}"><button>Reload Me</button></a>
</div>

<div >
<div class="card float-left">
  <img  src="{{ $leftImageUrl }}" style="width:100%">
  <h1>{{ $left['name'] }}</h1>
 
  <p class="title">BMI : {{$left['bmi']}}</p>
 
  <a href="/people/{{$left['id']}}"><button class="btn-success">Explore Me</button></a>

</div>

<div class="card float-right">
  <img  src="{{ $rightImageUrl }}" style="width:100%">
  <h1>{{ $right['name'] }}</h1>
 
  <p class="title">BMI : {{$right['bmi']}}</p>
 
  <a href="/people/{{$right['id']}}"><button class="btn-success">Explore Me</button></a>

</div>


</div>



@endsection