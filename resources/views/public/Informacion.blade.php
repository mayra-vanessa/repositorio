@extends('layouts.plantilla_general')

@section('title', 'Información sobre Enfermedades')

@section('content')
 <ul class="breadcrumb">
        <li><a href="{{ route('Inicio') }}" class="breadcrumb-link"><i class="fa fa-fw fa-home"></i> Inicio</a></li>
        <li><i class="fa fa-file-text-o"></i> Información</li>
    </ul>
<div class="container">
   

    <h1 class="text-center">Información sobre Enfermedades</h1>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="enfermedad">
                <div class="enfermedad-info">
                    <h2>Diabetes</h2>
                    <p>La diabetes es una enfermedad crónica que afecta a millones de personas en todo el mundo. Se caracteriza por niveles elevados de azúcar en la sangre debido a la incapacidad del cuerpo para producir o utilizar la insulina de manera eficiente.</p>
                </div>
                <div class="enfermedad-img">
                    <img src="https://www.farmaciagt.com/blog/wp-content/uploads/2021/10/istockphoto-953795236-612x612-1.jpg" alt="Imagen de Diabetes" class="img-fluid">
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="enfermedad">
                <div class="enfermedad-img">
                    <img src="https://clinicalasmonjas.com/wp-content/uploads/2020/12/LA-EPILEPSIA-EN-EL-DESARROLLO-DEL-NINO.jpg" alt="Imagen de Epilepsia" class="img-fluid">
                </div>
                <div class="enfermedad-info">
                    <h2>Epilepsia</h2>
                    <p>La epilepsia es un trastorno neurológico que causa convulsiones recurrentes. Estas convulsiones pueden variar en intensidad y duración y afectar la calidad de vida de quienes las padecen.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="enfermedad">
                <div class="enfermedad-info">
                    <h2>Alzheimer</h2>
                    <p>El Alzheimer es una enfermedad neurodegenerativa que afecta principalmente a personas mayores. Se caracteriza por la pérdida gradual de la memoria y otras funciones cognitivas, lo que puede llevar a la incapacidad para realizar tareas cotidianas.</p>
                </div>
                <div class="enfermedad-img">
                    <img src="https://img.freepik.com/foto-gratis/3d-rendering-of-people-looking-at-human-brain_23-2150723102.jpg?t=st=1696096620~exp=1696097220~hmac=bf607d8550e06034cf0bdc856ffa64d4c02a72a4fadd19cd09182f32e7f016d2" alt="Imagen de Alzheimer" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
